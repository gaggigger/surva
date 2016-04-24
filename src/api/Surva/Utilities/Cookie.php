<?php

namespace Surva\Utilities;

class Cookie
{
    public static function destroy($cookie)
    {
        if (isset($cookie) && isset($cookie['name'])) {
            unset($_COOKIE[$cookie['name']]);
            $cookie['value'] = '';
            $cookie['expires'] = time() - 3600;
            static::set($cookie);

            return true;
        }

        return false;
    }

    public static function validate($cookieName)
    {
        $result = isset($_COOKIE[$cookieName]);
        Log::info(
            'Cookie "{name}" is {valid}',
            [
                'name' => $cookieName,
                'valid' => $result ? 'valid' : 'invalid',
            ]
        );

        return $result;
    }

    public static function set($cookie)
    {
        if (isset($cookie) && isset($cookie['name'])) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expires'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httponly']
            );
            $cookie['value'] = Helper::ellipsis($cookie['value']);
            Log::info('Cookie updated "{name}" to "{value}"', $cookie);
        }
    }
}
