<?php
require_once __DIR__.'/vendor/autoload.php';
date_default_timezone_set('UTC');
require 'app/Application.php';
use akadmin\Application;

$app = new Application(array(
    'root' => __DIR__
));

// ... definitions

$app->run();