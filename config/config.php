<?php
/**
 * This is the configuration file
 */
$config = [
  //database configuration
  'db' => [
    'user'     => 'root',
    'password' => '',
    'database' => 'zi_lance_development',
    'host'     => 'localhost'
],
  'app' => [
      'host' => $_SERVER['SERVER_NAME'],
      'port' => $_SERVER['SERVER_PORT']
  ]
];
