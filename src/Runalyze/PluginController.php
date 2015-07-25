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
                $Factory->uninstallPlugin($id);

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
    
    public function PanelMoveAction($id) {
        $Frontend = new \Frontend();
        if (is_numeric($id)) {
                $Factory = new \PluginFactory();
                $Panel = $Factory->newInstanceFor($id);

                if ($Panel->type() == \PluginType::Panel) {
                        $Panel->move($id);
                }
        }
    }
    
    public function PanelClapAction($id) {
        $Frontend = new \Frontend();

        if (is_numeric($id)) {
                $Factory = new \PluginFactory();
                $Panel = $Factory->newInstanceFor( $id );

                if ($Panel->type() == \PluginType::Panel) {
                        $Panel->clap();
                }
        }
    }
    public function PanelDisplayAction() {
        $Frontend = new \Frontend();
        $Frontend->displayPanels();
    }
    
    public function uninstallAction($key) {
        $Pluginkey = $key;

        $Frontend = new \Frontend();
        $Installer = new \PluginInstaller($Pluginkey);

        echo '<h1>'.__('Uninstall').' '.$Pluginkey.'</h1>';

        if ($Installer->uninstall()) {
                echo \HTML::okay( __('The plugin has been uninstalled.') );

                \PluginFactory::clearCache();
                \Ajax::setReloadFlag(Ajax::$RELOAD_ALL);
                echo \Ajax::getReloadCommand();
        } else {
                echo \HTML::error( __('There was a problem, the plugin could not be uninstalled.') );
        }

        echo '<ul class="blocklist">';
        echo '<li>';
        echo \Ajax::window('<a href="'.\ConfigTabPlugins::getExternalUrl().'">'.\Icon::$TABLE.' '.__('back to list').'</a>');
        echo '</li>';
        echo '</ul>';
        return '';
    }
    
    public function installAction($key) {
        $Pluginkey = $key;

        $Frontend = new \Frontend();
        $Installer = new \PluginInstaller($Pluginkey);

        echo '<h1>'.__('Install').' '.$Pluginkey.'</h1>';

        if ($Installer->install()) {
                $Factory = new \PluginFactory();
                $Plugin = $Factory->newInstance($Pluginkey);

                echo HTML::okay( __('The plugin has been successfully installed.') );

                echo '<ul class="blocklist">';
                echo '<li>';
                echo $Plugin->getConfigLink(\Icon::$CONF.' '.__('Configuration'));
                echo '</li>';
                echo '</ul>';

                \Ajax::setReloadFlag(Ajax::$RELOAD_ALL);
                echo \Ajax::getReloadCommand();
        } else {
                echo \HTML::error( __('There was a problem, the plugin could not be installed.') );
        }

        echo '<ul class="blocklist">';
        echo '<li>';
        echo Ajax::window('<a href="'.\ConfigTabPlugins::getExternalUrl().'">'.\Icon::$TABLE.' '.__('back to list').'</a>');
        echo '</li>';
        echo '</ul>';
        return '';
    }
}
