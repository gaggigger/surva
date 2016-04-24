<?php

namespace Surva\Model;

class Model
{
    protected static $models;

    public static function init($opts)
    {
        $key = get_called_class();
        if (static::$models == null) {
            static::$models = [];
        }
        static::$models[$key] = new $opts['modelClass']($opts);

        return static::$models[$key];
    }

    public static function __callStatic($name, $args)
    {
        $key = get_called_class();
        if (isset(static::$models[$key])) {
            if (method_exists(static::$models[$key], $name)) {
                return call_user_func_array(
                    array(static::$models[$key], $name),
                    $args
                );
            } else {
                throw new \InvalidArgumentException(
                    "No definition for {$key}:{$name}"
                );
            }
        }
    }
}
