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

    public function setUp()
    {
        
    }

    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testParserInstantiation()
    {
        
        $data = array( 'members' => array( array( 'id' => 'foo' ) ) );
    
        $membersParser = new MembersParser($data);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Parsers\MembersParser', $membersParser);
    }
    
   
}
