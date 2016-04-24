<?php

namespace Surva\Controller;

use Surva\Model\User;
use Surva\Middleware\Result;
use Surva\Middleware\Auth;

class UserController
{
    private $userKey = 'userId';
    private $cookieKey = 'userCookie';

    public function get($request, $response)
    {
        $user = Auth::getCurrentUser();
        Result::success(200, $user);

        return $response;
    }

    public function postLogin($request, $response)
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        Auth::login($email, $password);

        return $response;
    }

    public function postLogout($request, $response)
    {
        Auth::logout();

        return $response;
    }
}

/*

    public function postListBackups($request, $response)
    {
        Result::push('backups', User::getBackups());

        return $response;
    }

    public function postBackup($request, $response)
    {
        Result::push('backup', User::backup());

        return $response;
    }

    public function postRestore($request, $response)
    {
        $backupTime = $request->getParsedBody()['backup'];
        User::restore($backupTime);

        return $response;
    }


 */
