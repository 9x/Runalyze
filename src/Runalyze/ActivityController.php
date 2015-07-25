<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Runalyze\Model\Activity;
use Runalyze\View\Activity\Linker;
use Runalyze\View\Activity\Dataview;

class ActivityController
{
    public function homeAction()
    {
        return new Response("AppController::homeAction");
    }
 
    public function viewAction($id)
    {
        $Frontend = new \Frontend();

        $Context = new Context($request->get($id), \SessionAccountHandler::getId());
        $View = new \TrainingView($Context);
        $View->display();
        return '';
    }
    
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
        $Window->display();
        return '';
    }

    public function editAction()
    {
        $Frontend = new Frontend();

        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
                $Deleter = new \Activity\Deleter(DB::getInstance(), Runalyze\Context::Factory()->activity($_GET['delete']));
                $Deleter->setAccountID(SessionAccountHandler::getId());
                $Deleter->delete();

                echo '<div class="panel-content"><p id="submit-info" class="error">'.__('The activity has been removed').'</p></div>';
                echo '<script>$("#multi-edit-'.((int)$_GET['delete']).'").remove();Runalyze.Statistics.resetUrl();Runalyze.reloadContent();</script>';
                exit();
        }

        $Training = new \TrainingObject(Request::sendId());
        $Activity = new \Activity\Object($Training->getArray());

        $Linker = new \Linker($Activity);
        $Dataview = new \Dataview($Activity);

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
    }
    
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
}