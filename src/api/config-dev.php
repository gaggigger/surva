<?php

$alpabets = 'abcdefghijklmnopqrstuvwxyz';
$numerals = '0123456789';

$dirApi = dirname(__FILE__).'/';
$dirRoot = dirname(dirname(__FILE__)).'/';

return [

    // Slim Settings
    'settings' => [
        'displayErrorDetails' => true,
        'timezone' => 'America/Chicago',
    ],

    // Surva Settings
    'Surva' => [

        'Permission' => [
            'admin' => 3,
            'editor' => 2,
            'subscriber' => 1,
            'guest' => 0,
        ],

        'Log' => [
            'file' => $dirRoot.'log.log',
            'showCaller' => false,
        ],

        'Site' => [
            'modelClass' => '\\Surva\\Utilities\\File',
            'autoBackup' => true,
            'file' => $dirRoot.'data/site.json',
        ],

        'User' => [
            'modelClass' => '\\Surva\\Utilities\\JsonFile',
            'autoBackup' => true,
            'file' => $dirRoot.'data/user.json',
        ],

        'Auth' => [
            /*
            * Auth Class Configurations
            * This static class manages the cookie for logged-in users.
            * When user logs in a cookie is created. The cookie name and value
            * is randomly generated based on settings provided below.
            * The name of the cookie is saved on the Session.
            */
            'cookie' => [
                'name' => 'SurvaUserToken',
                'value' => '',
                'expires' => time() + 7 * 24 * 3600, // 7 days
                'path' => '/',
                'domain' => '',
                'secure' => false, // HTTPS only
                'httponly' => false, // accessible to Javascript
            ],
            'length' => 128,
            'characters' => ($alpabets.$numerals),
            'emptyUser' => [
                'email' => '',
                'password' => '',
            ],
        ],

        'Xsrf' => [
            /*
             * XsrfMiddleware Class Configurations
             * This class manages XSRF protection.
             * AngularJS expects a cookie sent on first GET request
             * named XSRF-TOKEN. This cookie is relayed back atomatically on
             * all consecutive $http requests as a header X-XSRF-TOKEN
             */
            'headerName' => 'X-XSRF-TOKEN',
            'cookie' => [
                'name' => 'XSRF-TOKEN',
                'value' => '',
                'expires' => time() + 3600, // 1 hour
                'path' => '/',
                'domain' => '',
                'secure' => false, // HTTPS only
                'httponly' => false, // accessible to Javascript
            ],

            // Methods protected by XSRF
            'methods' => ['post', 'put', 'delete'],

            // Settings for RandomLib Generator
            'length' => 64,
            'characters' => ($alpabets.$numerals),
        ],

        'UploadController' => [
            'tmp' => $dirRoot.'/public/media/tmp/',
            'dir' => $dirRoot.'/public/media/',
        ],
    ],
];
