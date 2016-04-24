<?php

namespace Surva\Middleware;

use Surva\Utilities\Token;
use Surva\Utilities\Cookie;
use Surva\Utilities\Log;
use Surva\Utilities\Session;
use Surva\Model\User;

class Auth
{
    protected static $config;
    protected static $keyCookie;
    protected static $cookie;

    private static $keyUser = 'SurvaUserId';
    private static $keyRole = 'SurvaUserRole';

    public static function init($config)
    {
        static::$config = $config;
        static::$cookie = static::$config['cookie'];
        static::$keyCookie = static::$cookie['name'];
    }

    public static function isLoggedIn()
    {
        $sessionUserId = Session::get(static::$keyUser);
        $sessionCookieName = Session::get(static::$keyCookie);
        $sessionCookieValid = Cookie::validate(static::$cookie['name']);
        $result = isset($sessionUserId)
                && isset($sessionCookieName)
                && $sessionCookieValid;

        return $result;
    }

    public static function getCurrentUser()
    {
        if (static::isLoggedIn()) {
            return User::where('id', Session::get(static::$keyUser))->first();
        }
    }

    public static function login($email, $password)
    {
        if (static::isLoggedIn()) {
            Result::error(400, 'Already logged in');
        } elseif (!isset($email)) {
            Result::error(400, 'Email not provided');
        } elseif (!isset($password)) {
            Result::error(400, 'Password not provided');
        } else {
            $user = User::where('email', $email)->first();
            if ($user !== null) {
                if (password_verify($password, $user->password)) {
                    static::setUser($user);
                    unset($user->password);
                    unset($user->id);
                    Log::notice('Logged in as "{username}"', (array) $user);
                    Result::success(200, $user);

                    return $user;
                } else {
                    Result::error(400, 'Incorrect password');
                }
            } else {
                Result::error(400, 'Incorrect email');
            }
        }
    }

    public static function logout()
    {
        if (static::isLoggedIn()) {
            Cookie::destroy(static::$cookie);
            Session::clear(static::$keyUser);
            Session::clear(static::$keyCookie);
            Session::clear(static::$keyRole);
            Result::success(200);
            Log::notice('Logged out');
        } else {
            Result::error(400, 'No user logged in');
        }
    }

    public static function setUser($user)
    {
        // Generate new cookie name and value
        static::$cookie['value'] = Token::generate(
            static::$config['length'],
            static::$config['characters']
        );

        // Set Session variables
        Session::set(static::$keyUser, $user->id);
        Session::set(static::$keyRole, $user->role);
        Session::set(static::$keyCookie, static::$cookie['value']);

        // Set Cookie
        Cookie::set(static::$cookie);
    }

    public static function middleware($request, $response, $next)
    {
        $user = static::getCurrentUser();
        if ($user !== null) {
            if (Permission::hasPermission($user)) {
                $response = $next($request, $response);
            } else {
                Result::error(401, 'No permission to perform request');
            }
        } else {
            Result::error(401);
        }

        return $response;
    }
}
