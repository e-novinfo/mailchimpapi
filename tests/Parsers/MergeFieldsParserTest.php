<?php
/**
 * MailChimpApi - MergeFieldsParserTest
 *
 * @since       16.12.2016
 *
 * @version     1.0.0.0
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

use \enovinfo\MailChimpApi\Parsers\MergeFieldsParser as MergeFieldsParser;

class MergeFieldsParserTest extends \PHPUnit_Framework_TestCase
{
    /***************************/
    /********** SETUP **********/
    /***************************/
    
    protected $data;

    public function setUp()
    {
        $this->data = array( 'merge_fields' => array( array( 'merge_id' => 2, 'tag' => 'LNAME', 'name' => 'foo' ) ) );
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testParserInstantiation()
    {
        $mergeFieldsParser = new MergeFieldsParser($this->data);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Parsers\MergeFieldsParser', $mergeFieldsParser);
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
        $mergeFieldsParser = new MergeFieldsParser('foo');
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
        $mergeFieldsParser = new MergeFieldsParser('');
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
        $mergeFieldsParser = new MergeFieldsParser(array());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*************************************/
    /********** TEST PARSE DATA **********/
    /*************************************/
    
    public function testParseData()
    {
        $mergeFieldsParser = new MergeFieldsParser($this->data);
        $this->assertTrue($mergeFieldsParser->parseData());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /**************************************/
    /********** TEST PARSED DATA **********/
    /**************************************/
    
    public function testParsedData()
    {
        $mergeFieldsParser = new MergeFieldsParser($this->data);
        $mergeFieldsParser->parseData();
        $this->assertTrue(is_array($mergeFieldsParser->getParsedData()));
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
        $mergeFieldsParser = new MergeFieldsParser(array());
        $this->assertFalse($mergeFieldsParser->parseData());
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /********************************************/
    /********** PARSING PROCESS FIELDS **********/
    /********************************************/
    
    public function testParsingProcessFields()
    {
        
        for ($i = 0; $i <= 3; $i++) {
            
            $data = array();
            
            switch ($i) {
                    
                case 0:
                    $data = $this->data;
                    break;
                    
                case 1:
                    $data = array( 'merge_fields' => array( array( 'merge_id' => 0, 'tag' => 'LNAME', 'name' => 'foo' ) ) );
                    break;
                    
                case 2: 
                    $data = array( 'merge_fields' => array( array( 'merge_id' => 2, 'tag' => '', 'name' => 'foo' ) ) );
                    
                case 3:
                    $data = array( 'merge_fields' => array( array( 'merge_id' => 2, 'tag' => 'LNAME', 'name' => '' ) ) );
                    break;
                    
            }
            
            $mergeFieldsParser = new MergeFieldsParser($data);
            $mergeFieldsParser->parseData();
            $this->assertTrue(is_array($mergeFieldsParser->getParsedData()));
            
        }
        
    }
  
}
