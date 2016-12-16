<?php

use \enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;
use \enovinfo\MailChimpApi\Parsers\MembersParser as MembersParser;
use \enovinfo\MailChimpApi\Parsers\MergeFieldsParser as MergeFieldsParser;
use \enovinfo\MailChimpApi\Generators\CSVGenerator as CSVGenerator;

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

$mailChimpListMergeFields = new MailChimpHttp($MC_API_KEY);
$mailChimpListMergeFields->verifySSL = false;
$mailChimpListMergeFields->get('lists/'.$listID.'/merge-fields');
$mergeFields = $mailChimpListMergeFields->getFormattedResponse();

$membersParser = new MembersParser($members);
$membersParser->parseData();
$parsedMembers = $membersParser->getParsedData();

$mergeFieldsParser = new MergeFieldsParser($mergeFields);
$mergeFieldsParser->parseData();
$parsedMergeFields = $mergeFieldsParser->getParsedData();

echo '<pre>';
print_r($parsedMembers);
echo '</pre>';

echo '<pre>';
print_r($parsedMergeFields);
echo '</pre>';

$csvGenerator = new CSVGenerator($parsedMembers, $parsedMergeFields);
$csvGenerator->process(true);