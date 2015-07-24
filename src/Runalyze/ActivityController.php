<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class ActivityController
{
    public function homeAction()
    {
        return new Response("AppController::homeAction");
    }
 
    public function viewAction($id)
    {
        echo "test";
        $Frontend = new \Frontend();

        $Context = new \Context($request->get($id), \SessionAccountHandler::getId());
        $View = new \TrainingView($Context);
        $View->display();
        return '';
    }
}