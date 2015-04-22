<?php

require '/db.php';

require '/Slim-2.6.2/Slim/Slim.php';     //include the framework in the project
\Slim\Slim::registerAutoloader();       //register the autoloader


$app = new \Slim\Slim(array(
  'debug' => true
));

$app->add(new \Slim\Middleware\ContentTypes());

$app->options('/(:name+)', function() use ($app) {
    //...return correct headers...
  $app->response()->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

require "/routes.php";       //include the file which contains all the routes/route inclusions

$app->run();

?>
