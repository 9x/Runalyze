<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Silex\Application;

class DatabrowserController
{
    public function DefaultAction(Application $app)
    {
        $Frontend = new \Frontend();
        $DataBrowser = new \DataBrowser();
        $DataBrowser->display();
        return '';
    }
 
}
