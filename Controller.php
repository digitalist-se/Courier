<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Common;
use Piwik\Plugins\Courier\API as CourierAPI;
use Piwik\Plugin\ControllerAdmin;

class Controller extends ControllerAdmin
{
    public function index()
    {
        return $this->renderTemplate('@Courier/index', [
            'endpoints' => $this->availableEndpoints(),
        ]);
    }

    public function addendpoint()
    {
        $type = Common::getRequestVar('endpoint', 'webhook', 'string');

        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' == $_SERVER['REQUEST_METHOD']) {
            if ($type == 'webhook') {
                $integration = [
                    'url' => $_POST['url']
                ];
            }
            $serialized = serialize($integration);
            $save = new CourierAPI();
            $save->saveIntegration($type, $_POST['name'], $serialized);
            $this->redirectToIndex('Courier', 'index');
        }

        return $this->renderTemplate('@Courier/endpoint', [
            'integration' => $type,
        ]);
    }


    private function availableEndpoints()
    {
        $api = new CourierAPI();
        return $api->getEndpoints();
    }
}
