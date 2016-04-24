<?php

namespace Surva\Middleware;

abstract class Singleton
{
    protected static $config = [];
    protected static $enabled = false;

    public static function init($config = null)
    {
        if (isset($config)) {
            foreach ($config as $key => $value) {
                static::$config[$key] = $value;
            }
        }
        static::boot();
    }

    public static function enable()
    {
        static::$enabled = true;
    }

    public static function disable()
    {
        static::$enabled = false;
    }

    public static function boot()
    {
        // Overwrite in child class
        static::enable();
    }
}
