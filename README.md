# Surva

Database-less JSON file-based CMS with administration panel and simple token-based authentication. Built with AngularJS, Slim PHP Framework.

***

### [View Demo &rarr;](http://ganeshnrao.com/surva/public)
* For administration in demo scroll down to the bottom of the page and click "Meta" in the footer menu.
* Login with username `admin` and password `helloworld`.
* Changes made in demo will be persisted until end of your session or until page is reloaded.
* The "Save Changes" button in the admin-panel will not save any changes in this demo.

***

#### Warning

**Surva** is still very young. It is not ready for production level use. I will continue to develop it, however in the mean time, feel free to fork it and play with it.

### Requirements
Make sure that you have the following commands available on your terminal.
* `git` for instructions on installing Git, visit [Getting Started Installing Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
* `composer` To get Composer and installation instructions visit [Getting Started with Composer](https://getcomposer.org/doc/00-intro.md)
* `bower` To install Bower visit [Getting Started with Bower](http://bower.io/#getting-started)
* `npm` Install Node Package Manager and its dependencies from
[Installing Node.js and updating npm](https://docs.npmjs.com/getting-started/installing-node)
* `php` The bundled Gulp app serves using built-in `PHP`, if you don't have PHP, then visit [Installation on Mac OS X](http://php.net/manual/en/install.macosx.php). Alternately, you can directly download the PHP as a binary from [PHP 5.3 to 7.0 for OS X 10.6 to 10.11 as binary package](http://php-osx.liip.ch)

### Installation
To get started with Surva, open up your terminal run the following commands. This will download this repo into a folder named `surva` and install all necessary dependencies.

    git clone https://github.com/ganeshnrao/surva.git surva
    cd surva
    bower install
    composer install --working-dir=src/api/
    npm install

Once `bower`, `composer`, and `npm` have successfully install all the dependencies you can run the build command below to create a sample site.

### Development Tools
Surva comes with a `gulp` app, with the following commands.
* `gulp serve` This compiles Surva into a `dist` folder and serves it using built-in PHP at `http://localhost:8000`
* `gulp build` This builds Surva site into the `dist` folder
* `gulp watch` This watches for changes, and on change triggers build and serve. Note that it doesn't do any live reloading at the moment.

### Directory Structure
```
├── gulp/  - Gulp tasks, add more tasks as separate files as needed
├── src/
│   ├── api/ - Slim App that serves as an API
│   │   ├── Surva/
│   ├── data/
│   │   ├── site.json - the JSON file of the site
│   ├── public/ - publicly visible section of site
│   │   ├── api/ - loads the API
│   │   ├── app/ - AngularJS app
│   │   ├── media/ - media files needed for the site
│   │   ├── sass/ - SASS files needed by site
│   │   ├── templates/ - Templates needed by site
│   │   ├── index.php - index page loaded by site
```
