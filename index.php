<?php

use enovinfo\MailChimpApi\Controllers\SimpleController as SimpleController;

require_once 'app/index.php';

$controller = new SimpleController();
echo $controller->sayHello();
