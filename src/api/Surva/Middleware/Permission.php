<?php

namespace Surva\Middleware;

use Surva\Utilities\Log;

class Permission
{
    protected static $config = [
        'admin' => 3,
        'editor' => 2,
        'subscriber' => 1,
        'guest' => 0,
    ];
    protected static $role = null;

    public static function init($config)
    {
        static::$config = $config;
        static::$role = $config['guest'];
    }

    public function __call($name, $args)
    {
        if (array_key_exists($name, static::$config)) {
            static::$role = static::$config['admin'];
            Log::info('Requires user role to be "'.$name.'"');
            $request = $args[0];
            $response = $args[1];
            $next = $args[2];
            $response = $next($request, $response);

            return $response;
        } else {
            throw new \InvalidArgumentException('Undefined role "'.$name.'"');
        }
    }

    public static function hasPermission($user)
    {
        $result = false;
        $role = null;
        if (isset($user->role)) {
            $role = $user->role;
            $result = (static::$config[$role] >= static::$role);
        }
        Log::info('User\'s role is "{role}" {verb} permission', [
            'role' => $role,
            'verb' => $result ? 'has' : 'does not have',
        ]);

        return $result;
    }
}
