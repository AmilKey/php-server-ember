<?php

$app->get('/api/posts', function () use ($app, $dbname){

  $posts = [];
  $mongoPosts  = $dbname->posts;
  $cursor = $mongoPosts->find();

  foreach ( $cursor as $id => $value  )
  {
    $value['_id']  = (string) $value['_id'];
    $value['user'] = (string) $value['user'];
    $posts[] = $value;
  }

  $app->response->setBody(json_encode([
    'posts' => $posts
  ]));

});

$app->get('/api/posts/:id', function ($id) use ($app, $dbname){

  $posts = $dbname->posts;
  $post  = $posts->findOne([
      '_id' => new MongoId($id)
  ]);

  $post['_id']  = (string) $post['_id'];
  $post['user'] = (string) $post['user'];
  $app->response->setBody(json_encode([
    'post' => $post
  ]));

});

$app->post('/api/posts', function () use ($app, $dbname){

  $req = $app->request->getBody()['post'];

  $req['user'] = new MongoId($req['user']);
  $dbname->posts->insert($req);

  $req['_id']  = (string) $req['_id'];
  $req['user'] = (string) $req['user'];

  $app->response->setBody(json_encode([
    'post' => $req
  ]));
});

$app->put('/api/posts/:id', function ($id) use ($app, $dbname){

  $req     = $app->request->getBody()['post'];
  $newData = ['$set' => [
      'title'       => $req['title'],
      'description' => $req['description'],
      'created_at'  => $req['created_at'],
      'updated_at'  => $req['updated_at'],
      'user'        => new MongoId($req['user'])
  ]];

  $dbname->posts->update([
      '_id' => new MongoId($id)
  ], $newData);

  $app->response->setBody(json_encode([
    'post' => $req
  ]));

});

$app->delete('/api/posts/:id', function ($id) use ($app, $dbname){

  $posts = $dbname->posts;
  $dbname->posts->remove([
      '_id' => new MongoId($id)
  ]);

  $app->response->setBody(json_encode([
    'post' => $req
  ]));

});

?>