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

    public function windowAction() {
        $Frontend = new \Frontend();
        ?>
        <div class="panel-heading">
                <h1><?php _e('Explanation: How are these experimental values calculated?'); ?></h1>
        </div>

        <div class="panel-content">
        <?php
        $Factory = new \PluginFactory();
        $Plugin = $Factory->newInstance('RunalyzePluginPanel_Rechenspiele');

        $Formular = new \Formular();
        $Formular->setId('rechenspiele-calculator');
        $Formular->addCSSclass('ajax');
        $Formular->addCSSclass('no-automatic-reload');
        $Formular->addFieldset( $Plugin->getFieldsetTRIMP(), false );
        $Formular->addFieldset( $Plugin->getFieldsetVDOT(), false );
        $Formular->addFieldset( $Plugin->getFieldsetBasicEndurance() );
        $Formular->addFieldset( $Plugin->getFieldsetPaces(), false );
        $Formular->allowOnlyOneOpenedFieldset();
        $Formular->display();
        ?>
        </div>
        <?php
        return '';
    }
}
