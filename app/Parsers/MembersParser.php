<?php
/**
 * MailChimpApi - MembersParser
 *
 * @since       14.12.2016
 *
 * @version     1.0.0.0
 *
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

namespace enovinfo\MailChimpApi\Parsers;

use enovinfo\MailChimpApi\Interfaces\Parser as Parser;

class MembersParser implements Parser
{
    
    /********************************/
    /********** PROPERTIES **********/
    /********************************/
    
    public $data;
    
    private $dataToParse;
    private $parsedData;
    private $readyToParse = false;
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*******************************/
    /********** CONSTRUCT **********/
    /*******************************/
    
    /*
     * @param Array $data Data to parse
     */

    public function __construct($data)
    {
        if ($this->checkReceivedData($data)) {
            $this->setDataToParse($data['members']);
            $this->setReadyToParse(true);
        }
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*************************************/
    /********** GET PARSED DATA **********/
    /*************************************/
    
    /*
     * @return Array
     */
    
    public function getParsedData()
    {
        return $this->parsedData;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /***************************************/
    /********** SET DATA TO PARSE **********/
    /***************************************/
    
    /*
     * @param Array $dataToParse Data to parse
     * @return Void
     */
    
    private function setDataToParse($dataToParse)
    {
        $this->dataToParse = $dataToParse;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*************************************/
    /********** SET PARSED DATA **********/
    /*************************************/
    
    /*
     * @param Array $parsedData Data parsed
     * @return Void
     */
    
    private function setParsedData($parsedData)
    {
        $this->parsedData = $parsedData;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** SET READY TO PARSE **********/
    /****************************************/
    
    /*
     * @param Bool $state State
     * @return Void
     */
    
    private function setReadyToParse($state = false)
    {
        $this->readyToParse = $state;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /*****************************************/
    /********** CHECK RECIEVED DATA **********/
    /*****************************************/
    
    /*
     * @param Array $data Data to parse
     * @return Bool
     */
    
    public function checkReceivedData($data)
    {
        if (!empty($data) && is_array($data) && !empty($data['members'])) {
            return true;
        } else {
            throw new \Exception('Invalid data.');
        }
        
        return false;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** PARSE MERGE FIELDS **********/
    /****************************************/
    
    /*
     * @param Array $fields Fields to parse
     */
    
    private function parseMergeField($fields)
    {
        $mergedFields = array();
        
        foreach ($fields as $f => $field) {
            $mergedFields[$f] = $field;
        }
        
        return $mergedFields;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /***********************************/
    /********** PARSE PROCESS **********/
    /***********************************/
    
    /*
     * @return Array
     */
    
    private function parseProcess()
    {
        $parsedData = array();
            
        $i = 0;

        foreach ($this->dataToParse as $member) {
            $parsedData[$i] = array();

            if (!empty($member['id'])) {
                $parsedData[$i]['id'] = (string) $member['id'];
            }

            if (!empty($member['email_address'])) {
                $parsedData[$i]['email'] = (string) $member['email_address'];
            }

            if (!empty($member['status'])) {
                $parsedData[$i]['status'] = (string) $member['status'];
            }

            if (!empty($member['language'])) {
                $parsedData[$i]['language'] = (string) $member['language'];
            }
            
            if (!empty($member['location']['country_code'])) {
                $parsedData[$i]['country'] = (string) $member['location']['country_code'];
            }
            
            if (!empty($member['merge_fields'])) {
                $parsedFields = $this->parseMergeField($member['merge_fields']);
                
                foreach ($parsedFields as $f => $field) {
                    $parsedData[$i][$f] = $field;
                }
            }

            $i++;
        }
        
        return $parsedData;
    }
    
    /*********************************************************************************/
    /*********************************************************************************/
        
    /****************************************/
    /********** RETURN PARSED DATA **********/
    /****************************************/
    
    /*
     * @return Bool
     */
    
    public function parseData()
    {
        if ($this->readyToParse) {
            $parsedData = $this->parseProcess();
            $this->setParsedData($parsedData);
            return true;
        } else {
            throw new \Exception('Data can\'t be parsed.');
        }
        
        return false;
    }
}
