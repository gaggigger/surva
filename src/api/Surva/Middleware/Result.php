<?php

namespace Surva\Middleware;

use Surva\Utilities\Log;

class Result
{
    protected static $data = null,
        $status = 200,
        $json = true,
        $enabled = true;

    public static function middleware($request, $response, $next)
    {
        $response = $next($request, $response);
        if (static::$enabled) {
            $response = $response
                ->withStatus(static::$status)
                ->withAddedHeader('Content-Type', 'application/json')
                ->write(static::generate());
        }
        Log::end();

        return $response;
    }

    public static function success($status = 200, $data = null, $json = true)
    {
        static::$status = max(static::$status, $status);
        static::$data = $data;
        static::$json = $json;
    }

    public static function error($status = 500, $message = null, $json = false)
    {
        static::$status = $status;
        static::$data = $message;
        static::$json = $json;
    }

    public static function enable()
    {
        static::$enabled = true;
    }

    public static function disable()
    {
        static::$enabled = false;
    }

    public static function show()
    {
        if (static::$enabled) {
            http_response_code(static::$status);
            header('Content-Type: application/json');
            echo static::generate();
        }
    }

    protected static function generate()
    {
        $data = null;
        if (isset(static::$data)) {
            if (static::$json) {
                $data = json_encode(static::$data);
            } else {
                $data = static::$data;
            }
        }

        return $data;
    }
}
