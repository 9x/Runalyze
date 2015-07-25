<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class PluginController
{
    public function DefaultAction(Application $app, $id)
    {
        $Frontend = new \Frontend();
        $Factory = new \PluginFactory();

        try {
                $Plugin = $Factory->newInstanceFor($id);
        } catch (Exception $E) {
                $Plugin = null;

                echo \HTML::error( __('The plugin could not be found.') );
        }

        if ($Plugin !== null) {
                if ($Plugin instanceof PluginPanel) {
                        $Plugin->setSurroundingDivVisible(false);
                }

                $Plugin->display();
        }
        return '';
    }
    
    public function ConfigAction(Application $app, $id) 
    {
        $Frontend = new \Frontend(true);
        $Factory = new \PluginFactory();

        if (isset($_GET['key'])) {
                $Factory->uninstallPlugin( filter_input(INPUT_GET, 'id') );

                echo \Ajax::wrapJSforDocumentReady('Runalyze.Overlay.load("plugin/config");');
        } elseif (isset($id) && is_numeric($id)) {
                $Plugin = $Factory->newInstanceFor($id);
                $Plugin->displayConfigWindow();
        } else {
                echo '<em>'.__('Something went wrong ...').'</em>';
        }
        return '';
    }
    
    public function toolsAction() {
        $Frontend = new \Frontend();

        if (!isset($_GET['list'])) {
                \PluginTool::displayToolsHeader();
        }

        \PluginTool::displayToolsContent();
        return '';
    }
 
}
