<?php
  $uri    = "mongodb://localhost/blog";
  $client = new MongoClient($uri);
  $dbname = $client->selectDB('blog');
?>