<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

use Dbml\Dbml;

$file = __DIR__ . DIRECTORY_SEPARATOR . 'airbnb.dbml';
$dbml = new Dbml($file);

dd($dbml->tables('users'));