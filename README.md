# Surva

A database-less JSON file-based CMS wth an administration panel and simple token-based authentication. Built with AngularJS and Slim PHP CMS.

### Installation
Run following commands

    git clone https://github.com/ganeshnrao/surva.git surva
    cd surva
    bower install
    composer install --working-dir=src/api/
    npm install

Once bower, composer and npm successfully install all the dependencies, you can build the sample site.

### Run Server
The included Gulp file launches the built-in PHP Server. To build and serve the site locally run

    gulp serve