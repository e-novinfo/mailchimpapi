<?php

use enovinfo\MailChimpApi\Controllers\SimpleController as SimpleController;
use enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;

require_once 'app/index.php';

$controller = new SimpleController();
echo $controller->sayHello();