<?php

header('Access-Control-Allow-Origin: http://uup.nu');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

require dirname(__FILE__) . '/third-party/Slim/Slim.php';
require dirname(__FILE__) . '/libs/main.php';

\Slim\Slim::registerAutoloader();

$app    = new \Slim\Slim();
$uup    = new uup();

// base router
$app->get('/', function() use ($uup) {

    echo json_encode($uup->status());

});

$app->get('/:code', function($code) use ($uup) {

    echo json_encode($uup->getMessageWithCode($code));

});

$app->post('/', function() use ($app,$uup){

    $array = (array) json_decode($app->request()->getBody());

    $code = $uup->randomString();

    $success = false;

    while($success) {

        $array = $uup->getMessageWithCode($code);

        if($array['error']) {

            $success = true;

        } else {

            $code = $uup->randomString();

        }

   }

   echo json_encode($uup->saveContent(array(
       "content"  => $array['content'],
       "code"     => $code,
       "ip"       => $_SERVER['REMOTE_ADDR']
   )));

});

$app->get('/max/', function() use ($uup) {

    echo json_encode($uup->getMaxViewedMessage());

});

$app->get('/me/', function() use ($uup) {

    echo json_encode($uup->getMessagesByIp($_SERVER['REMOTE_ADDR']));

});

$app->notFound(function () use ($uup) {

    echo json_encode($uup->errorNotFound());

});

$app->options('/', function() {

});

$app->run();