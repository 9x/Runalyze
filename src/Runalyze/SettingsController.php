<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class SettingsController
{
    public function DefaultAction($key='config_tab_general')
    {
        $Frontend = new \Frontend();
        $ConfigTabs = new \ConfigTabs();
        $ConfigTabs->setCurrentKey($key);
        $ConfigTabs->addDefaultTab(new \ConfigTabGeneral());
        $ConfigTabs->addTab(new \ConfigTabPlugins());
        $ConfigTabs->addTab(new \ConfigTabDataset());
        $ConfigTabs->addTab(new \ConfigTabSports());
        $ConfigTabs->addTab(new \ConfigTabTypes());
        $ConfigTabs->addTab(new \ConfigTabClothes());
        $ConfigTabs->addTab(new \ConfigTabAccount());
        $ConfigTabs->display();

        echo \Ajax::wrapJSforDocumentReady('Runalyze.Overlay.removeClasses();');
        return '';
    }
 
    public function testAction()
    {
        return new Response("Hello");
    }
}
