define(function ( require ) {

  var Marionette = require('marionette'),
      Backbone = require('backbone'),
      jQuery = require('jquery');

  /**
   * Initialize application
   * @type App_L1.Marionette.Application
   */
  var Gallery = new Marionette.Application();

  Gallery.PanelTitle = Marionette.ItemView.extend({
    template: '<h3>Select Album on the left</h3>',
    el: ".panel-title"
  });

  /**
   * Initialize layout
   */
  Gallery.Layout = Marionette.LayoutView.extend({
    el: '#container',
    regions: {
      menu: "#albums",
      heading: "#panelHeading",
      content: "#imagesContainer"
    },
    onBeforeShow: function () {
      this.showChildView('header', new Gallery.PanelTitle());
    }
  });



  Gallery.on("before:start", function ( options ) {

  });


  var dataController = Marionette.Object.extend({
    initialize: function ( options ) {
      this.data;
      this.url = options.url;
    },
    load: function ( ) {
      this.triggerMethod("load", this.url);

      console.log("Fetching %s", this.url);

      var url = this.url;
      var promise = $.ajax({
        url: url,
        method: 'get',
        dataType: 'json',
        success: function ( data ) {
          return data;
        },
        error: function ( jqXHR ) {
          console.error(jqXHR);
        }
      });

      $.when(promise).then(_.bind(this.populate, this));
    },
    populate: function ( data, status, response ) {
      if (status == 'success') {
        this.triggerMethod('announce', data);
      } else {
        throw "Error while loading albums data";
      }
    }
  });

  var buttonsDataLoader = new dataController({ url: '/json/albums' });
  buttonsDataLoader.load();

  buttonsDataLoader.on('announce', function ( response ) {

    console.log(response);

    var itemsCollection = Backbone.Collection.extend({
      model: Backbone.Model.extend()
    });

    var listItem = Marionette.ItemView.extend({
      template: "#item-template",
      onRender: function () {
        this.$el = this.$el.children();
        this.$el.unwrap();
        this.setElement(this.$el);
      },
    });

    var collectionView = Marionette.CollectionView.extend({
      childView: listItem,
      el: '#albums',
    });

    var listItems = new itemsCollection(response);
    Gallery.AlbumsList = new collectionView({
      collection: listItems
    });

    Gallery.AlbumsList.render();

  });


  /**
   * Albums controller
   * @type @exp;Marionette@pro;Object@call;extend
   */
  var controller = Marionette.Object.extend({
    showImages: function ( album_id, page ) {

      var albumUri = function ( album, page ) {
        if (!!page === false) {
          return '/json/album/' + album;
        } else {
          return '/json/album/' + album + '/page/' + page;
        }
      };

      var url = albumUri(album_id, page);
      console.log(url);
      var imagesDataLoader = new dataController({ url: url });
      imagesDataLoader.load();
    },
  });


  Gallery.UrlRouter = new Marionette.AppRouter({
    controller: new controller(),
    appRoutes: {
      "album/:id": "showImages",
      "album/:id/page/:id": "showImages",
    },
  });



  Gallery.on('start', function () {
    Backbone.history.start();
    console.log("Application started");
    new Gallery.Layout();
    new Gallery.PanelTitle().render();
    //debugger;
  });


  Gallery.start();


});