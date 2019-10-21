<?php

namespace Piwik\Plugins\Courier\tests\Integration;

use Piwik\Db;
use Piwik\Plugins\Courier\API;


/**
 * @group Courier
 * @group Plugins
 */
class APITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int|null
     */
    private $integrationName = 'testWebhookCourier';


    public function testGetIntegrations()
    {
       $api = new API();
       $integrations = $api->getIntegrations();
       $this->assertArrayHasKey('webhook',$integrations);
       $this->assertArrayHasKey('slack',$integrations);
       $this->assertArrayHasKey('zapier', $integrations);
       $this->assertArrayHasKey('email', $integrations);
       $this->assertArrayNotHasKey('foo',$integrations);
    }

    public function testSaveIntegration()
    {
        $api = new API();
        $save = $api->saveIntegration('webhook', $this->integrationName,'http://localhost', true);
    }

    public function testIdIntegrationByname()
    {
        $api = new API();
        $id = $api->getIdIntegrationByName($this->integrationName);
        $this->assertInternalType("string", $id);
    }

    /**
     * Check if integrations exists
     */
    public function testGetExistingIntegrations()
    {
        $api = new API();
        $integrations = $api->getExistingIntegrations();
        $this->assertInternalType('array', $integrations);
    }
    /**
     * Check if integration exists
     */
    public function testGetExistingIntegration()
    {
        $api = new API();
        $id = $api->getIdIntegrationByname($this->integrationName);
        $integration = $api->getExistingIntegration($id);
        $this->assertInternalType('array', $integration);
    }
    /**
     * Update integration url
     */
    public function testUpdateIntegration()
    {
        $api = new API();
        $id = $api->getIdIntegrationByname($this->integrationName);
        $api->updateIntegration($id, $this->integrationName, 'https://localhost');
        $getUpdatedIntegration = $api->getExistingIntegration($id);
        $this->assertInternalType('array', $getUpdatedIntegration);
        $this->assertContains( 'https://localhost', $getUpdatedIntegration);
        $api->updateIntegration($id, 'testWebhookCourier1', 'https://localhost');
        $getUpdatedIntegration = $api->getExistingIntegration($id);
        $this->assertInternalType('array', $getUpdatedIntegration);
        $this->assertContains( 'testWebhookCourier1', $getUpdatedIntegration);
        $api->updateIntegration($id, 'testWebhookCourier', 'http://localhost');
        $getUpdatedIntegration = $api->getExistingIntegration($id);
        $this->assertInternalType('array', $getUpdatedIntegration);
        $this->assertContains( 'testWebhookCourier', $getUpdatedIntegration);
    }

    /**
     * Update integration url
     */
    public function testDeleteTestWebhook()
    {
        $api = new API();
        $id = $api->getIdIntegrationByname($this->integrationName);
        $api->deleteIntegration($id);
    }
}
