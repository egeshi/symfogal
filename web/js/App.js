define(function ( require ) {

  /**
   * Dependencies
   * 
   * @type @call;require
   */
  var Marionette = require('marionette'),
      Backbone = require('backbone'),
      jQuery = require('jquery');

  /**
   * Initialize application
   * 
   * @type App_L1.Marionette.Application
   */
  var Gallery = new Marionette.Application();

  /**
   * Panel title view for any needs
   */
  Gallery.PanelTitle = Marionette.ItemView.extend({
    template: '<h3>Select Album at the left</h3>',
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
      content: "#images",
      pagination: '#pagination'
    },
    onBeforeShow: function () {
      this.showChildView('header', new Gallery.PanelTitle());
    }
  });

  Gallery.on("before:start", function ( options ) {
  });

  /**
   * Load JSON data
   * 
   * @type @exp;Marionette@pro;Object@call;extend
   */
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

  /**
   * Get Backbone model collection
   * @returns {object}
   */
  var getModelCollection = function () {
    return Backbone.Collection.extend({
      model: Backbone.Model.extend()
    });
  };

  /**
   * Get Marionette collection view
   * 
   * @param {string} itemTemplate
   * @param {string} containerId
   * @param {object} listItems
   * @returns {App_L1.getCollectionView.collectionView}
   */
  var getCollectionView = function ( itemTemplate, containerId, listItems ) {

    console.log(arguments);

    var listItem = Marionette.ItemView.extend({
      template: itemTemplate,
      onRender: function () {
        this.$el = this.$el.children();
        this.$el.unwrap();
        this.setElement(this.$el);
      },
    });

    var collectionView = Marionette.CollectionView.extend({
      childView: listItem,
      el: containerId,
      attachBuffer: function ( collectionView, buffer ) {
        collectionView.$el.html(buffer);
      },
    });

    return new collectionView({
      collection: listItems
    });

  };

  // Load albums navigation buttons
  var buttonsDataLoader = new dataController({ url: '/json/albums' });
  buttonsDataLoader.load();
  buttonsDataLoader.on('announce', function ( response ) {

    console.log(response);

    var itemsCollection = getModelCollection();
    var listItems = new itemsCollection(response);
    Gallery.AlbumsList = getCollectionView("#button-template", '#albums', listItems);
    Gallery.AlbumsList.render();

  });

  /**
   * 
   * @param {int} album
   * @param {int} page
   * @returns {String}
   */
  var albumUri = function ( album, page, isJson ) {

    var uri = '';
    if (isJson === true) {
      uri = '/json';
    }

    if (!!album === true && !!page === false) {
      return uri + '/album/' + album;
    } else if (!!album === true && !!page === true) {
      return uri + '/album/' + album + '/page/' + page;
    }
    throw "Routing error";
  };

  /**
   * Album display controller
   * 
   * @type @exp;Marionette@pro;Object@call;extend
   */
  var controller = Marionette.Object.extend({
    showImages: function ( album_id, page ) {

      var url = albumUri(album_id, page, true);
      console.log(url);

      // Load images data
      var imagesDataLoader = new dataController({ url: url });
      imagesDataLoader.load();

      // Generate and render images with pagination
      imagesDataLoader.on('announce', function ( response ) {

        console.log(response);

        var data = response[0];

        var itemsCollection = getModelCollection();
        Gallery.ImagesList = getCollectionView("#image-template", '#images', new itemsCollection(data.images));
        Gallery.ImagesList.render();

        /*
         * Very simple pagination since we can only have 2 pages
         */
        var navLinks = {
          prev: albumUri(data.album, data.currentPage - 1),
          next: albumUri(data.album, data.currentPage + 1)
        };


        if (data.currentPage == data.pagesTotal) {
          navLinks.next = null;
        }
        if (data.currentPage == 1) {
          navLinks.prev = null;
        }

        //console.log(navLinks);

        var modelData = [ {
            currentPage: data.currentPage,
            imagesTotal: data.imagesTotal,
            itemsPerPage: data.itemsPerPage,
            pagesTotal: data.pagesTotal,
            nav: {
              prev: {
                url: navLinks.prev,
                disabled: (!!navLinks.prev === false) ? true : false
              },
              next: {
                url: navLinks.next,
                disabled: (!!navLinks.next === false) ? true : false
              }
            }
          } ];

        var itemsCollection = getModelCollection();
        Gallery.PaginationRegion = getCollectionView("#pagination-template", '#pagination', new itemsCollection(modelData));
        Gallery.PaginationRegion.render();

      });



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
  });


  Gallery.start();


});