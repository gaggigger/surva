<?php

namespace Surva\Controller;

use Surva\Model\Site;
use Surva\Middleware\Result;
use Surva\Utilities\Log;
use Surva\Utilities\Helper;

class SiteController
{
    public function get($request, $response)
    {
        $data = Site::get();

        if (isset($data)) {
            Result::success(200, $data, false);
        } else {
            Log::emergency('Unable to load site data');
            Result::error(500, 'Could not load site due to server error.');
        }

        return $response;
    }

    public function postSave($request, $response)
    {
        $newData = $request->getParsedBody()['site'];
        if (isset($newData)) {
            $numBytes = Site::write($newData);
            if ($numBytes === null) {
                Result::error(500, 'Server Error. Failed to save.');
            } else {
                Result::success(200, 'Saved '.Helper::size($numBytes));
            }
        } else {
            Result::error(400, 'Failed to save due to server error');
        }

        return $response;
    }
}

/*
    public function postListBackups($request, $response)
    {
        $backups = Site::listBackups();
        Result::push('backups', $backups);

        return $response;
    }

    public function postBackup($request, $response)
    {
        Result::push('backup', Site::backup());

        return $response;
    }

    public function postRestore($request, $response)
    {
        $backupTime = $request->getParsedBody()['backup'];
        Result::push('backup', Site::restore($backupTime));

        return $response;
    }
 */
