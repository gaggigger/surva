<?php

$app->get('/site', 'SiteController:get');

$app->post('/site/save', 'SiteController:postSave')
    ->add('Auth:middleware')
    ->add('Permission:editor');

// Users
$app->post('/user', 'UserController:get');
$app->post('/login', 'UserController:postLogin');
$app->post('/logout', 'UserController:postLogout');

// Image uploading using Flow
$app->map(['GET', 'POST'], '/upload', 'UploadController:postUpload')
    ->add('Auth:middleware')
    ->add('Permission:editor');

$app->get('/xsrf', function ($request, $response) {
    \Surva\Middleware\Result::success(200);
});
