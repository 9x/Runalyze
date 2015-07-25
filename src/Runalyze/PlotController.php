<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class PlotController
{
    public function sumAction($type='month') {
        $Frontend = new \Frontend();

        if (!isset($_GET['y']))
                $_GET['y'] = \PlotSumData::LAST_12_MONTHS;


        if ($type == 'week') {
                $Plot = new \PlotWeekSumData();
                $Plot->display();
        } elseif ($type == 'month') {
                $Plot = new \PlotMonthSumData();
                $Plot->display();
        } else {
                echo \HTML::error( __('There was a problem.') );
        }
        return '';
    }
}