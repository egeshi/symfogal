# Project setup

These steps suggest that all dependencies like composer and Bower are already installed, configured and working properly. Use http server you prefer.

1. Create project directory `$ mkdir projectdir && cd projectdir`
2. Perform general Symfony2 installation `$ composer create-project symfony/framework-standard-edition . "2.8.*"`
3. Initialize GIT repo with `$ git init`
4. Set repo origin `$ git remote add origin git@github.com:egeshi/symfogal.git`
5. Reset to current state `$ git fetch --all && $ git reset --hard origin/master`
6. Run composer update to sync vendor packages `$ composer update`
7. Create database `$ php app/console doctrine:database:create`
8. Create tables schema `$ php app/console doctrine:schema:update --force`
9. Seed database with fixtures data `$ php app/console doctrine:fixtures:load`
10. cd to `web` directory and install required Bower components `$ cd web && bower install`
11. Configure http server host to point to `web` directory
12. All set up. Open http://yourserver/ with your browser
