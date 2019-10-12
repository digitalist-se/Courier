<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Common;
use Piwik\Db;
use Piwik\NoAccessException;
use Piwik\Piwik;

/**
 * API for plugin Courier
 *
 * @method static API getInstance()
 */
class API extends \Piwik\Plugin\API
{

    public function getIntegrations()
    {

        try {
            Piwik::checkUserIsNotAnonymous();
        } catch (NoAccessException $e) {
        }
        $endpoints = [
            'webhook' => [
                'name' => 'Webhook',
                'description' => 'A custom webhook URL. Deliver will be in json.',
            ],
            'slack' => [
                'name' => 'Slack',
                'description' => 'Post to Slack.',
            ],
            'zapier' => [
                'name' => 'Zapier',
                'description' => 'Integrate with Zapier.',
            ],
            'email' => [
                'name' => 'Email',
                'description' => 'Use email.',
            ],
        ];
        return $endpoints;
    }


    public function saveIntegration($type, $name, $integration)
    {
        try {
            Piwik::checkUserIsNotAnonymous();
        } catch (NoAccessException $e) {
        }
        $created_date = date("Y-m-d H:i:s");
        $insert = "INSERT INTO " . Common::prefixTable('courier_integration') .
            " (type, name, integration, date) VALUES (?,?,?,?)";
        $values = [$type, $name, $integration, $created_date];
        try {
            Db::query($insert, $values);
        } catch (\Exception $e) {
        }
    }

    public function getExistingIntegrations() {
        $query = "SELECT * FROM " . Common::prefixTable('courier_integration');
        $results = $this->getDb()->fetchAll($query);
        return $results;

    }

    private function getDb()
    {
        return Db::get();
    }

    public function sendWebhook($id, $send, $source) {
        $webhook = $this->getWebhook($id);
        $integration = $webhook['integration'];
        $result = unserialize($integration);
        $url = $result['url'];
        $this->webhookCurl($url, $message = [
            'sender' => 'Matomo',
            'message' =>  $send,
            'source' => $source,
        ]);
    }

    private function webhookCurl($endpoint, $message) {

        $webhookurl = $endpoint;
        $make_json = json_encode($message);
        $ch = curl_init( $webhookurl );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $make_json);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec( $ch );
    }
    private function getWebhook($id) {
        $query = "SELECT * FROM " . Common::prefixTable('courier_integration') . " WHERE id=$id";
        $results = $this->getDb()->fetchRow($query);
        $foo = 'bar';
        return $results;
    }

}
