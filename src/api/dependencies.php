<?php

use Surva\Middleware\Result;

$container = $app->getContainer();

////////////////////////////////////////

$container['Result'] = function ($container) {
    return new \Surva\Middleware\Result();
};

$container['Xsrf'] = function ($container) {
    return new \Surva\Middleware\Xsrf();
};

$container['Auth'] = function ($container) {
    return new \Surva\Middleware\Auth();
};

$container['Permission'] = function ($container) {
    return new \Surva\Middleware\Permission();
};

$container['UserController'] = function ($container) {
    return new \Surva\Controller\UserController();
};

$container['SiteController'] = function ($container) {
    return new \Surva\Controller\SiteController();
};

$container['UploadController'] = function ($container) {
    return new \Surva\Controller\UploadController();
};

////////////////////////////////////////

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        Result::error(404, 'Not Found');

        return $response;
    };
};

$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        Result::error(405, 'Not Allowed');

        return $response;
    };
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $data = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => str_replace(SITE_ROOT, '', $exception->getFile()),
            'line' => $exception->getLine(),
            'trace' => [],
        ];
        $traces = explode("\n", $exception->getTraceAsString());
        //$data['full-trace'] = $traces;
        // keep only traces that match project specific files
        foreach ($traces as $trace) {
            if (!strpos($trace, 'vendor') && strpos($trace, SITE_ROOT)) {
                $pathPos = strpos($trace, SITE_ROOT);
                if ($pathPos) {
                    $start = $pathPos + strlen(SITE_ROOT);
                    $end = strpos($trace, ':') - $start;
                    $trace = substr($trace, $start, $end);
                }
                array_push($data['trace'], $trace);
            }
        }

        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($data));
    };
};
