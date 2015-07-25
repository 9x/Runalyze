<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Runalyze\Model\Activity;
use Runalyze\View\Activity\Linker;
use Runalyze\View\Activity\Dataview;
use Runalyze\View\Activity\Context;
use Runalyze\Model\Route;
use Runalyze\Calculation\Route\Calculator;
use Runalyze\View\Window\Laps\Window;


class ActivityController
{
    public function homeAction()
    {
        return new Response("AppController::homeAction");
    }
 
    /*
     * view activity
     */
    public function viewAction($id)
    {
        $Frontend = new \Frontend();

        $Context = new Context($id, \SessionAccountHandler::getId());
        $View = new \TrainingView($Context);
        return new Response($View->display());
    }
    
    public function exportAction($id) {
        $Frontend = new \Frontend();

        $View = new \ExporterWindow($id);
        return new Response($View->display());
    }
    
    /*
     * create activity
     */
    public function createAction()
    {
        $Frontend = new \Frontend(isset($_GET['json']));

        \System::setMaximalLimits();

        if (class_exists('Normalizer')) {
                if (isset($_GET['file'])) {
                        $_GET['file'] = \Normalizer::normalize($_GET['file']);
                }

                if (isset($_GET['files'])) {
                        $_GET['files'] = \Normalizer::normalize($_GET['files']);
                }

                if (isset($_POST['forceAsFileName'])) {
                        $_POST['forceAsFileName'] = \Normalizer::normalize($_POST['forceAsFileName']);
                }

                if (isset($_FILES['qqfile']) && isset($_FILES['qqfile']['name'])) {
                        $_FILES['qqfile']['name'] = \Normalizer::normalize($_FILES['qqfile']['name']);
                }
        }

        $Window = new \ImporterWindow();
        return new Response($Window->display());
        
    }

    /*
     * edit activity
     */
    public function editAction($id)
    {
        $Frontend = new \Frontend();

        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
                $Deleter = new \Activity\Deleter(DB::getInstance(), Runalyze\Context::Factory()->activity($_GET['delete']));
                $Deleter->setAccountID(\SessionAccountHandler::getId());
                $Deleter->delete();

                echo '<div class="panel-content"><p id="submit-info" class="error">'.__('The activity has been removed').'</p></div>';
                echo '<script>$("#multi-edit-'.((int)$_GET['delete']).'").remove();Runalyze.Statistics.resetUrl();Runalyze.reloadContent();</script>';
                exit();
        }

        $Training = new \TrainingObject($id);
        $Activity = new Activity\Object($Training->getArray());

        $Linker = new Linker($Activity);
        $Dataview = new Dataview($Activity);

        echo $Linker->editNavigation();

        echo '<div class="panel-heading">';
        echo '<h1>'.$Dataview->titleWithComment().', '.$Dataview->dateAndDaytime().'</h1>';
        echo '</div>';
        echo '<div class="panel-content">';

        $Formular = new \TrainingFormular($Training, \StandardFormular::$SUBMIT_MODE_EDIT);
        $Formular->setId('training');
        $Formular->setLayoutForFields( \FormularFieldset::$LAYOUT_FIELD_W50 );
        $Formular->display();

        echo '</div>';
        return '';
    }
    
    /*
     * search activities
     */
    public function searchAction() {
        $Frontend = new \Frontend();

        $showResults = !empty($_POST);

        if (isset($_GET['get']) && $_GET['get'] == 'true') {
                $_POST = array_merge($_POST, $_GET);
                $showResults = true;

                \SearchFormular::transformOldParamsToNewParams();
        }

        if (empty($_POST) || \Request::param('get') == 'true') {
                echo '<div class="panel-heading">';
                echo '<h1>'.__('Search for activities').'</h1>';
                echo '</div>';

                $Formular = new \SearchFormular();
                $Formular->display();
        }

        $Results = new \SearchResults($showResults);
        $Results->display();
        return '';
    }
    
    /*
     * show elevationInfo
     */
    public function elevationInfoAction($id) {
        $Frontend = new \Frontend();

        $ElevationInfo = new \ElevationInfo(new Context($id, \SessionAccountHandler::getId()));
        return new Response($ElevationInfo->display());
    }
    
    public function ElevationCorrectionAction($id) {
        $Frontend = new \Frontend();

        $Factory = \Context::Factory();
        $Activity = $Factory->activity($id);
        $ActivityOld = clone $Activity;
        $Route = $Factory->route($Activity->get(Activity\Object::ROUTEID));
        $RouteOld = clone $Route;

        $Calculator = new \Calculator($Route);

        if ($Calculator->tryToCorrectElevation()) {
                $Calculator->calculateElevation();
                $Activity->set(Activity\Object::ELEVATION, $Route->elevation());

                $UpdaterRoute = new \Route\Updater(\DB::getInstance(), $Route, $RouteOld);
                $UpdaterRoute->setAccountID(\SessionAccountHandler::getId());
                $UpdaterRoute->update();

                $UpdaterActivity = new \Activity\Updater(DB::getInstance(), $Activity, $ActivityOld);
                $UpdaterActivity->setAccountID(\SessionAccountHandler::getId());
                $UpdaterActivity->update();

                echo __('Elevation data has been corrected.');

                \Ajax::setReloadFlag( \Ajax::$RELOAD_DATABROWSER_AND_TRAINING );
                echo \Ajax::getReloadCommand();
                echo \Ajax::wrapJS('if($("#ajax").is(":visible") && $("#training").length)Runalyze.Overlay.load(\''.\Linker::EDITOR_URL.$id.'\')');
        } else {
                echo __('Elevation data could not be retrieved.');
        }
        return '';
    }
    
    public function roundsAction($id) {
        $Frontend = new \Frontend();

        $Window = new Window(new Context($id, \SessionAccountHandler::getId()));
        return new Response($Window->display());
        
    }
    
    public function vdotAction($id) {
        $Frontend = new \Frontend();

        $VDOTinfo = new \VDOTinfo(new Context($id, \SessionAccountHandler::getId()));
        return new Response($VDOTinfo->display());
    }
    
    public function MetaCourseAction($id) {
        $Frontend = new \FrontendShared(true);

        $Meta = new \HTMLMetaForFacebook($id);
        return new Response($Meta->displayCourse());
    }
}