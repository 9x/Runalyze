<?php
require '../inc/class.Frontend.php';
require_once '../vendor/autoload.php';
require_once '../plugin/RunalyzePluginPanel_Prognose/PanelPrognoseController.php';
require_once '../plugin/RunalyzePluginPanel_Rechenspiele/PanelRechenspieleController.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Silex\Application;


$app = new Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => '../view',
));
Twig_Autoloader::register();
$app['twig']->addExtension(new Twig_Extensions_Extension_I18n());
$app['twig']->registerUndefinedFunctionCallback(function ($name) {
	if (function_exists($name)) {
		return new Twig_SimpleFunction($name, function() use($name) {
			return call_user_func_array($name, func_get_args());
		});
	}

	return false;
});
$app['routes'] = $app->extend('routes', function (RouteCollection $routes, Application $app) {
    $loader     = new YamlFileLoader(new FileLocator(__DIR__ . '/../config'));
    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);
 
    return $routes;
});

$app->run();