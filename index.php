<?php

//composer autoload
require_once 'vendor/autoload.php';

use app\App;

// run app
$app = new App();
$app->run($argv);

