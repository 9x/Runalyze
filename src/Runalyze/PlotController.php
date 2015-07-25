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
    
    /*
     * Save plot image
     */
    public function saveAction() {
        header("Content-type: image/png");
        header("Content-Disposition: attachment; filename=".strtolower(str_replace(' ', '_', $_POST['filename'])));

        $encodeData = substr($_POST['image'], strpos($_POST['image'], ',') + 1);
        echo base64_decode($encodeData);
    }
}