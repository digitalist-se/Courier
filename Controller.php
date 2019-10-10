<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Plugins\Courier\API as CourierAPI;
use Piwik\Plugin\ControllerAdmin;

class Controller extends ControllerAdmin
{
    public function index()
    {
        return $this->renderTemplate('index', [
            'endpoints' => $this->availableEndpoints(),
        ]);
    }

    private function availableEndpoints()
    {
        $api = new CourierAPI();
        return $api->getEndpoints();
    }
}
