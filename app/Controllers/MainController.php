<?php
/**
 * MailChimpApi - MainController
 *
 * @since       19.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Controllers;

use \enovinfo\MailChimpApi\Http\MailChimpHttp as MailChimpHttp;
use \enovinfo\MailChimpApi\Parsers\MembersParser as MembersParser;
use \enovinfo\MailChimpApi\Parsers\MergeFieldsParser as MergeFieldsParser;
use \enovinfo\MailChimpApi\Generators\CSVGenerator as CSVGenerator;

class MainController
{
    
    /********************************/
    /********** PROPERTIES **********/
    /********************************/
    
    private $mcApiKey;
    private $lists;
    private $listID;
    private $members;
    private $mergeFields;
    private $parsedMembers;
    private $parsedMergeFields;
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/

    public function __construct()
    {
        
        $timeStart = microtime(true); 
        
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        $fileName = 'export_' . $day . $month . $year . '.csv';
        
        try {
            
            $this->setMcApiKey();
            $this->setLists();
            $this->setListID();
            $this->setMembers();
            $this->setMergeFields();
            $this->setParsedMembers();
            $this->setParsedMergeFields();
            
            if ($this->process()) {
                echo "<p>Generated file: ".$fileName."</p>";
            } else {
                echo '<p>Process failed.</p>';
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            echo '<p>Process failed.</p>';
        } finally {
            echo "<p>Execution time: ".(microtime(true) - $timeStart)." s</p>";
        }
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /************************************/
    /********** SET MC API KEY **********/
    /************************************/
    
    private function setMcApiKey()
    {
        
        $envFilePath = __DIR__.'/../../';
        
        if (file_exists($envFilePath.'.env')) {
            $dotenv = new \Dotenv\Dotenv($envFilePath);
            $dotenv->load();
            $this->mcApiKey = getenv('MC_API_KEY');
        } else {
            throw new \Exception('No .env file.');
        }
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*******************************/
    /********** SET LISTS **********/
    /*******************************/
    
    private function setLists()
    {

        $mailChimpLists = new MailChimpHttp($this->mcApiKey);
        $mailChimpLists->verifySSL = false;
        $mailChimpLists->get('lists');
        $lists = $mailChimpLists->getFormattedResponse();
        $this->lists = $lists;

    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*********************************/
    /********** SET LIST ID **********/
    /*********************************/
    
    private function setListID()
    {
        $this->listID = $this->lists['lists'][8]['id'];
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*********************************/
    /********** SET MEMBERS **********/
    /*********************************/
    
    private function setMembers()
    {
        
        $mailChimpMembers = new MailChimpHttp($this->mcApiKey);
        $mailChimpMembers->verifySSL = false;
        $mailChimpMembers->get('lists/'.$this->listID.'/members');
        $members = $mailChimpMembers->getFormattedResponse();
        $this->members = $members;
            
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /**************************************/
    /********** SET MERGE FIELDS **********/
    /**************************************/
    
    private function setMergeFields()
    {
        
        $mailChimpListMergeFields = new MailChimpHttp($this->mcApiKey);
        $mailChimpListMergeFields->verifySSL = false;
        $mailChimpListMergeFields->get('lists/'.$this->listID.'/merge-fields');
        $mergeFields = $mailChimpListMergeFields->getFormattedResponse();
        $this->mergeFields = $mergeFields;

    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /****************************************/
    /********** SET PARSED MEMBERS **********/
    /****************************************/
    
    private function setParsedMembers()
    {
        
        $membersParser = new MembersParser($this->members);
        $membersParser->parseData();
        $parsedMembers = $membersParser->getParsedData();
        $this->parsedMembers = $parsedMembers;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*********************************************/
    /********** SET PARSED MERGE FIELDS **********/
    /*********************************************/
    
    private function setParsedMergeFields()
    {
        
        $mergeFieldsParser = new MergeFieldsParser($this->mergeFields);
        $mergeFieldsParser->parseData();
        $parsedMergeFields = $mergeFieldsParser->getParsedData();
        $this->parsedMergeFields = $parsedMergeFields;
        
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
    
    /*****************************/
    /********** PROCESS **********/
    /*****************************/
    
    private function process()
    {
        
        $csvGenerator = new CSVGenerator($this->parsedMembers, $this->parsedMergeFields);
        return $csvGenerator->process(true);
        
    }

}
