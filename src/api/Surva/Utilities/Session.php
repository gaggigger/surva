<?php

namespace Surva\Utilities;

class Session
{
    public static function set($key, $value)
    {
        $action = 'set';
        if (isset($_SESSION[$key])) {
            $action = 'updated';
        }
        $_SESSION[$key] = $value;
        Log::info('Session {action} "{key}" to "{value}"', [
            'action' => $action,
            'key' => $key,
            'value' => Helper::ellipsis($value),
        ]);

        return true;
    }

    public static function get($key)
    {
        $result = null;
        if (isset($_SESSION[$key])) {
            $result = $_SESSION[$key];
            Log::info(
                'Session "{key}" is "{value}"',
                ['key' => $key, 'value' => Helper::ellipsis($result)]
            );
        } else {
            Log::error(
                'Accessing unset Session key, "{key}"',
                ['key' => $key]
            );
        }

        return $result;
    }

    public static function clear($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            Log::info('Session unset "{key}"', ['key' => $key]);

            return true;
        } else {
            Log::error('Session "{key}" missing', ['key' => $key]);

            return false;
        }
    }
}
