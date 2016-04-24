<?php

session_start();

$apiDir = dirname(__FILE__);

// Load Vendors
require $apiDir.'/vendor/autoload.php';

// Load Configurations
$mode = trim(file_get_contents($apiDir.'/mode.php'));
$config = require $apiDir."/config-{$mode}.php";

// Set Timezone
date_default_timezone_set($config['settings']['timezone']);

// Initialize Surva
\Surva\Surva::init($config['Surva']);

// Initialize Slim App
$app = new \Slim\App($config);
require $apiDir.'/dependencies.php';
require $apiDir.'/middleware.php';
require $apiDir.'/routes.php';
$app->run();
