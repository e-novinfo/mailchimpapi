<?php

use \enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;
use \enovinfo\MailChimpApi\Parsers\MembersParser as MembersParser;

require_once 'app/index.php';

$envFilePath = __DIR__.'/';

if (file_exists($envFilePath.'.env')) {
    $dotenv = new Dotenv\Dotenv($envFilePath);
    $dotenv->load();
}

$MC_API_KEY = getenv('MC_API_KEY');

$mailChimpLists = new MailChimpHttp($MC_API_KEY);
$mailChimpLists->verifySSL = false;
$mailChimpLists->get('lists');
$lists = $mailChimpLists->getFormattedResponse();

$listID = $lists['lists'][8]['id'];
$mailChimpMembers = new MailChimpHttp($MC_API_KEY);
$mailChimpMembers->verifySSL = false;
$mailChimpMembers->get('lists/'.$listID.'/members');
$members = $mailChimpMembers->getFormattedResponse();

$membersParser = new MembersParser($members);

echo '<pre>';
print_r($members);
echo '</pre>';