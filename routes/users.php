<?php

$app->get('/api/users', function ($id) use ($app, $dbname){

});

$app->get('/api/users/:id', function ($id) use ($app, $dbname){

  $users = $dbname->users;
  $user  = $users->findOne([
      '_id' => new MongoId($id)
  ]);

  $user['_id']    = (string) $user['_id'];
  // $user['apikey'] = (string) $user['apikey'];

  unset($user['login']);
  unset($user['password']);
  unset($user['apikey']);

  $app->response->setBody(json_encode([
    'user' => $user
  ]));

});

#create new user
$app->post('/api/users', function () use ($app, $dbname){
    $users = $dbname->users;
    $req   = $app->request->getBody();

    $user = [
      'name'       => $req['name'],
      'login'      => $req['login'],
      'password'   => $req['password'],
      'created_at' => date("D M j G:i:s T Y"),
      'updated_at' => date("D M j G:i:s T Y"),
    ];

    $dbname->users->insert($user);

    $user_id = $user['_id'];

    $apikey = [
      'user'       => new MongoId($user_id),
      'token'      => uniqid('', true),
      'token_type' => 'bearer'
    ];

    $dbname->apikeys->insert($apikey);

    $dbname->users->update([
        '_id' => new MongoId($user_id)
    ], ['$set' => [
      'apikey' => new MongoId($apikey['_id'])
    ]]);

   echo 'ok';
});

$app->put('/api/users/:id', function ($id) use ($app, $dbname){

  $req     = $app->request->getBody()['user'];
  $newData = ['$set' => [
      'name'       => $req['name'],
      'updated_at' => $req['updated_at'],
      'posts'      => $req['posts']
  ]];

  $dbname->users->update([
      '_id' => new MongoId($id)
  ], $newData);

  unset($req['login']);
  unset($req['password']);
  unset($req['apikey']);

  $app->response->setBody(json_encode([
    'user' => $req
  ]));

});

$app->delete('/api/users/:id', function ($id) use ($app, $dbname){

});

?>