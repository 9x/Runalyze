<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class TestController
{
    public function homeAction()
    {
        return new Response("AppController::homeAction");
    }
 
    public function testAction()
    {
        return new Response("Hello");
    }
}