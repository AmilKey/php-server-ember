<?php

$app->post('/api/token', function () use ($app, $dbname){

  parse_str($app->request->getBody(), $req);

  if ($req['grant_type'] == 'password') {
    $username = $req['username'];
    $password = $req['password'];
    $user = $dbname->users->findOne([
      'login'    => $username,
      'password' => $password
    ]);

    if ($user) {

      $apikey = $dbname->apikeys->findOne([
        'user' => new MongoId($user['_id'])
      ]);

      if ($apikey) {

        $app->response->setBody(json_encode([
          'access_token' => $apikey['token'],
          'user_id'      => (string) $user['_id']
        ]));

      } else {
        $app->halt(400, 'invalid_grant!');
      }

    } else {
      $app->halt(400, 'invalid_grant!');
    }

  } else {
    $app->halt(400, 'unsupported_grant_type!');
  }
});

$app->post('/api/revoke', function () use ($app){

  parse_str($app->request->getBody(), $req);
  $token_type_hint = $req['token_type_hint'];

  if ($token_type_hint == 'access_token' || $token_type_hint == 'refresh_token') {
    echo '';
  } else {
    $app->halt(400, 'unsupported_token_type!');
  }

});


?>