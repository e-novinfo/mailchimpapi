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
    /********** TEST INSTANTIATION **********/
    /****************************************/

    public function testControllerInstantiation()
    {
        $envFilePath = __DIR__.'/../../';
        $mainController = new MainController($envFilePath);
        $this->assertInstanceOf('\enovinfo\MailChimpApi\Controllers\MainController', $mainController);
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
}
