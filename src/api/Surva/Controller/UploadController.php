<?php

namespace Surva\Controller;

use Surva\Utilities\Log;
use Surva\Utilities\Helper;
use Surva\Middleware\Result;
use Surva\Middleware\Xsrf;

class UploadController
{
    protected static $config;

    public static function init($config)
    {
        static::$config = $config;
    }

    public function postUpload($request, $response)
    {
        Result::disable();
        Xsrf::disable();
        $flowConfig = new \Flow\Config();
        $flowConfig->setTempDir(static::$config['tmp']);

        $flowRequest = new \Flow\Request();

        $name = $flowRequest->getFileName();
        $size = Helper::size($flowRequest->getTotalSize());

        $dest = static::$config['dir'].$name;

        $result = \Flow\Basic::save($dest, $flowConfig, $flowRequest);

        $info = [
            'name' => $name,
            'size' => $size,
        ];

        if ($request->isGet()) {
            Log::info('Uploading "{name}", {size}', $info);
            Log::end();
            die();
        } else {
            if ($result) {
                Log::info('Uploaded "{name}", {size}', $info);
            } else {
                Log::error('Failed upload "{name}", {size}', $info);
            }
        }

        return $response;
    }
}
