<?php
/**
 * Courier, a plugin for Matomo.
 *
 * @link https://digitalist.se
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Courier;

use Piwik\Common;
use Piwik\Exception\NoPrivilegesException;
use Piwik\Exception\NoWebsiteFoundException;
use Piwik\Plugins\Courier\API as CourierAPI;
use Piwik\Plugin\ControllerAdmin;
use Piwik\Notification;

class Controller extends ControllerAdmin
{
    public function index()
    {
        // Leaving this as a comment to easier debugging for now.
        // @todo: remove this later
        //$test_query = new CourierAPI();
        //$query = $test_query->sendWebhook(3,'Bar could go and hang himself *Foo you!*','internal');
        return $this->renderTemplate('@Courier/index', [
            'endpoints' => $this->availableIntegrations(),
            'integrations' => $this->existingIntegrations(),
        ]);
    }

    public function confirmdelete()
    {
        $id = '';
        $name = '';
        try {
            $id = Common::getRequestVar('integration', null, 'string');
            $this->confirmdeleteIntegration($id);
        } catch (\Exception $e) {
        }
        try {
            $name = Common::getRequestVar('name', null, 'string');
            $this->confirmdeleteIntegration($id);
        } catch (\Exception $e) {
        }

        $notification = new Notification("Integration $name was deleted successfully");
        $notification->context = Notification::CONTEXT_SUCCESS;
        $notification->type = Notification::TYPE_TOAST;
        Notification\Manager::notify(Common::getRandomString(), $notification);
        try {
            $this->redirectToIndex('Courier', 'index');
        } catch (NoPrivilegesException $e) {
        } catch (NoWebsiteFoundException $e) {
        }
    }

    public function deleteintegration()
    {

        try {
            $id = Common::getRequestVar('integration', null, 'string');
        } catch (\Exception $e) {
        }

        return $this->renderTemplate('@Courier/delete', [
            'integration' =>  $this->existingIntegration($id),
        ]);
    }

    public function addintegration()
    {
        try {
            $type = Common::getRequestVar('endpoint', 'webhook', 'string');
        } catch (\Exception $e) {
        }

        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' == $_SERVER['REQUEST_METHOD']) {
            if ($type == 'webhook') {
                $integration = [
                    'url' => $_POST['url']
                ];
            }
            $name = $_POST['name'];
            $serialized = serialize($integration);
            $save = new CourierAPI();
            $save->saveIntegration($type, $name, $serialized);
            $notification = new Notification("$name was added successfully");
            $notification->context = Notification::CONTEXT_SUCCESS;
            $notification->type = Notification::TYPE_TOAST;
            Notification\Manager::notify(Common::getRandomString(), $notification);
            try {
                $this->redirectToIndex('Courier', 'index');
            } catch (NoPrivilegesException $e) {
            } catch (NoWebsiteFoundException $e) {
            }
        }

        return $this->renderTemplate('@Courier/integration', [
            'integration' => $type,
        ]);
    }


    private function availableIntegrations()
    {
        $api = new CourierAPI();
        return $api->getIntegrations();
        $this->confirmdeleteIntegration($id);
    }

    private function existingIntegrations()
    {
        $api = new CourierAPI();
        return $api->getExistingIntegrations();
    }
    private function existingIntegration(int $id)
    {
        $api = new CourierAPI();
        return $api->getExistingIntegration($id);
    }
    private function confirmdeleteIntegration(int $id)
    {
        $api = new CourierAPI();
        $delete = $api->deleteIntegration($id);
    }
}
