<?php

use enovinfo\MailChimpApi\Controllers\SimpleController as SimpleController;
use enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;

require_once 'app/index.php';

//$controller = new SimpleController();
//echo $controller->sayHello();

$envFilePath = __DIR__.'/';

if (file_exists($envFilePath.'.env')) {
    $dotenv = new Dotenv\Dotenv($envFilePath);
    $dotenv->load();
}

$MC_API_KEY = getenv('MC_API_KEY');
$mailChimpHttp = new MailChimpHttp($MC_API_KEY);
$mailChimpHttp->verifySSL = false;
$mailChimpHttp->get('lists');
print_r($mailChimpHttp->getResponse());