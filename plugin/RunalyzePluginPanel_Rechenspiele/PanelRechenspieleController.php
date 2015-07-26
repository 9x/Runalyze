<?php
namespace Runalyze;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Runalyze\Activity\Distance;


/**
 * PanelRechenspieleController
 *
 * @author Hannes Christiansen <hannes@runalyze.de> & Michael Pohl <michael@runalyze.de>
 */
class PanelRechenspieleController {
    
    public function infoAction() {
        $Frontend = new \Frontend();
        return new Response($app['twig']->render('info.twig'));
    }


}
