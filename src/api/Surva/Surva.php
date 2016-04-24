<?php

namespace Surva;

class Surva
{
    protected static $config;

    public static function init($config)
    {
        static::$config = $config;
        Utilities\Log::init($config['Log']);
        Model\User::init($config['User']);
        Model\Site::init($config['Site']);
        Middleware\Xsrf::init($config['Xsrf']);
        Middleware\Auth::init($config['Auth']);
        Middleware\Permission::init($config['Permission']);
        Controller\UploadController::init($config['UploadController']);
    }
}
