<?php
/**
 * MailChimpApi - MainControllerTest
 *
 * @since       16.12.2016
 *
 * @version     1.0.0.0
 * @author      e-novinfo
 * @copyright   e-novinfo 2016
 */

use \enovinfo\MailChimpApi\Controllers\MainController as MainController;

class MainControllerTest extends \PHPUnit_Framework_TestCase
{
    /***************************/
    /********** SETUP **********/
    /***************************/

    public function setUp()
    {
        $envFilePath = __DIR__.'/../../';

        if (file_exists($envFilePath.'.env')) {
            $dotenv = new Dotenv\Dotenv($envFilePath);
            $dotenv->load();
        }
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST ENVIRONNEMENT **********/
    /****************************************/

    public function testControllerEnvironment()
    {
        $MC_API_KEY = getenv('MC_API_KEY');
        $message = 'No environment variables in file .env. Create the file .env like .env.example.';
        $this->assertNotEmpty($MC_API_KEY, $message);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /****************************************/
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testControllerInstantiation()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }

        $mainController = new MainController(null, $MC_API_KEY);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Controllers\MainController', $mainController);
    }
    
    /*********************************************************************************/
    /*********************************************************************************/

    /*******************************************************/
    /********** TEST FAIL INSTANTIATION EXCEPTION **********/
    /*******************************************************/

    /**
     * @expectedException \Exception
     */
    
    public function testControllerInstantiationException()
    {
        $MC_API_KEY = getenv('MC_API_KEY');

        if (!$MC_API_KEY) {
            $this->markTestSkipped('No API key in .env file.');
        }
        
        $envFilePath = __DIR__.'/../foo/';

        $mainController = new MainController($envFilePath, null);
    }

}
