<?php
namespace Runalyze;

require '../inc/class.FrontendShared.php';
require '../inc/class.FrontendSharedList.php';

use Symfony\Component\HttpFoundation\Response;
use Silex\Application;


/**
 * SharedController
 * 
 * @author Hannes Christiansen <hannes@runalyze.de> & Michael Pohl <michael@runalyze.de>
 * @copyright http://www.runalyze.de/
 */
class SharedController {
    public function ActivityAction($id) {
        $Frontend = new \FrontendShared();

        if (\FrontendShared::$IS_IFRAME)
                echo '<div id="statistics-inner" class="panel" style="width:97%;margin:0 auto;">';
        elseif (!Request::isAjax())
                echo '<div id="statistics-inner" class="panel" style="width:960px;margin:5px auto;">';
        else
                echo '<div>';

        $Frontend->displaySharedView();

        echo '</div>';
        return '';
    }
    
    public function ListAction($username = '') {
        if (isset($_GET['view'])) {
                if ($_GET['view'] == 'monthkm') {
                        $_GET['type'] = 'month';
                        include 'window.plotSumData.shared.php';
                        exit;
                } elseif ($_GET['view'] == 'weekkm') {
                        $_GET['type'] = 'week';
                        include 'window.plotSumData.shared.php';
                        exit;
                }
        }

        $Frontend = new \FrontendSharedList;
        $Frontend->username = $username;
        if (!\Request::isAjax()) {
                if ($Frontend->userAllowsStatistics()) {
                        echo '<div class="panel" style="width:960px;margin:5px auto;">';
                        $Frontend->displayGeneralStatistics();
                        echo '</div>';
                }

                echo '<div id="data-browser" class="panel" style="width:960px;margin:5px auto;">';
                echo '<div id="'.DATA_BROWSER_SHARED_ID.'">';
        }

        $Frontend->displaySharedView();

        if (!Request::isAjax()) {
                echo '</div>';
                echo '</div>';

                echo '<div id="statistics-inner" class="panel" style="width:960px;margin:5px auto;">
                <div class="panel-content">
                        <p class="info">
                                '.__('Click on an activity to see more details.').'<br>
                                '.__('Public activities are marked: ').' '.\Icon::$ADD_SMALL_GREEN.'.
                        </p>
                </div>
        </div>';
        }
     return '';   
    }
}
