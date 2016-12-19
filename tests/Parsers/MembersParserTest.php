<?php
/**
 * MailChimpApi - MailChimpHttpTest
 *
 * @since       14.12.2016
 *
 * @version     1.0.0.0
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

use \enovinfo\MailChimpApi\Parsers\MembersParser as MembersParser;

class MembersParserTest extends \PHPUnit_Framework_TestCase
{
    /***************************/
    /********** SETUP **********/
    /***************************/
    
    protected $data;

    public function setUp()
    {
        $this->data = array( 'members' => array( array( 'id' => 'foo', 'email_adress' => 'user@domaine.com', 'status' => 'subscribed', 'location' => array( 'country_code' => 'CH' ), 'merge_fields' => array( 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) ) ) );
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testParserInstantiation()
    {
        $membersParser = new MembersParser($this->data);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Parsers\MembersParser', $membersParser);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /***************************************/
    /********** TEST INVALID DATA **********/
    /***************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testParserInvalidData()
    {
        $membersParser = new MembersParser('foo');
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*************************************/
    /********** TEST EMPTY DATA **********/
    /*************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testParserEmptyData()
    {
        $membersParser = new MembersParser('');
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*******************************************/
    /********** TEST DATA WITHOUT KEY **********/
    /*******************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testParserDataWithoutKey()
    {
        $membersParser = new MembersParser(array());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*************************************/
    /********** TEST PARSE DATA **********/
    /*************************************/
    
    public function testParseData()
    {
        $membersParser = new MembersParser($this->data);
        $this->assertTrue($membersParser->parseData());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /**************************************/
    /********** TEST PARSED DATA **********/
    /**************************************/
    
    public function testParsedData()
    {
        $membersParser = new MembersParser($this->data);
        $membersParser->parseData();
        $this->assertTrue(is_array($membersParser->getParsedData()));
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*********************************************/
    /********** TEST NOT READY TO PARSE **********/
    /*********************************************/
    
    /**
     * @expectedException \Exception
     */
    
    public function testNotReadyToParse()
    {
        $membersParser = new MembersParser(array());
        $this->assertFalse($membersParser->parseData());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /********************************************/
    /********** PARSING PROCESS FIELDS **********/
    /********************************************/
    
    public function testParsingProcessFields()
    {
        for ($i = 0; $i <= 5; $i++) {
            $data = array();
            
            switch ($i) {
                    
                case 0:
                    $data = $this->data;
                    break;
                    
                case 1:
                    $data = array( 'members' => array( array( 'id' => '', 'email_adress' => 'user@domaine.com', 'status' => 'subscribed', 'location' => array( 'country_code' => 'CH' ), 'merge_fields' => array( 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) ) ) );
                    break;
                    
                case 2:
                    $data = array( 'members' => array( array( 'id' => 'foo', 'email_adress' => '', 'status' => 'subscribed', 'location' => array( 'country_code' => 'CH' ), 'merge_fields' => array( 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) ) ) );
                    break;
                    
                case 3:
                    $data = array( 'members' => array( array( 'id' => 'foo', 'email_adress' => 'user@domaine.com', 'status' => '', 'location' => array( 'country_code' => 'CH' ), 'merge_fields' => array( 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) ) ) );
                    break;
                    
                case 4:
                    $data = array( 'members' => array( array( 'id' => 'foo', 'email_adress' => 'user@domaine.com', 'status' => '', 'location' => array( 'country_code' => 'CH' ), 'merge_fields' => array( 'LNAME' => 'John', 'FNAME' => 'Do', 'MERGEDFIELD' => 'foo' ) ) ) );
                    break;
                    
                case 5:
                    $data = array( 'members' => array( array( 'id' => 'foo', 'email_adress' => 'user@domaine.com', 'status' => 'subscribed', 'location' => array( 'country_code' => 'CH' ) ) ) );
                    break;
                    
            }
            
            $membersParser = new MembersParser($data);
            $membersParser->parseData();
            $this->assertTrue(is_array($membersParser->getParsedData()));
        }
    }
}
