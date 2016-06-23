<?php

namespace DrivewayOvershoot\Demo;

require_once( 'ClassAutoloader.php' );

$a = new ClassAutoloader();
$a->setupAutoloader();

$c = new Controllers\DefaultController();
$c->run();
