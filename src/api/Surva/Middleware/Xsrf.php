<?php

/*
 * Xsrf
 * 
 * This class manages XSRF protection.
 * AngularJS expects a cookie sent on first GET request
 * named XSRF-TOKEN. This cookie is relayed back atomatically on
 * all consecutive $http requests as a header X-XSRF-TOKEN
 */

namespace Surva\Middleware;

use Surva\Utilities\Token;
use Surva\Utilities\Session;
use Surva\Utilities\Cookie;
use Surva\Utilities\Log;

class Xsrf extends Singleton
{
    protected static $config = [
        'headerName' => 'X-XSRF-TOKEN',
        'cookie' => [
            'name' => 'XSRF-TOKEN',
            'value' => '',
            'expires' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
        ],
        'methods' => ['post', 'put', 'delete'],
        'length' => 64,
        'characters' => 'abcdefghijklmnopqrstuvwxys0123456789',
    ];
    protected static $xsrfValue;
    protected static $cookie;

    public static function boot()
    {
        static::$cookie = static::$config['cookie'];
        foreach (static::$config['methods'] as $key => $value) {
            static::$config['methods'][$key] = strtoupper($value);
        }
        static::$xsrfValue = null;
        static::enable();
    }

    public static function middleware($request, $response, $next)
    {
        $success = true;

        if (static::isProtected($request)) {
            $success = static::hasValidHeader($request);
            if (!$success) {
                Result::error(401, 'Missing or invalid XSRF Token');
            } else {
                Log::notice('XSRF Validated');
            }
        }

        if ($success) {
            $response = $next($request, $response);
            static::updateToken();
        }

        return $response;
    }

    public static function isProtected($request)
    {
        $method = strtoupper($request->getMethod());
        $result = in_array($method, static::$config['methods']);
        if ($result) {
            Log::info("{$method} is XSRF potected");
        }

        return $result;
    }

    public static function updateToken()
    {
        if (!static::$enabled) {
            return;
        }

        $cookieName = static::$cookie['name'];
        $headerName = static::$config['headerName'];

        $length = static::$config['length'];
        $chars = static::$config['characters'];

        $newValue = Token::generate($length, $chars);

        // Set Cookie
        static::$cookie['value'] = $newValue;
        Cookie::set(static::$cookie);

        // Update session variable
        Session::set($headerName, $newValue);

        return $newValue;
    }

    public static function hasValidHeader($request)
    {
        $cookieName = static::$cookie['name'];
        $headerName = static::$config['headerName'];

        $tokenRequest = $request->getHeader($headerName)[0];
        $tokenServer = Session::get($headerName);

        return isset($tokenServer)
            && isset($tokenRequest)
            && $tokenRequest == $tokenServer;
    }
}
