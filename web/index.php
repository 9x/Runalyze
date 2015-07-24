<?php
require '../inc/class.Frontend.php';
require_once '../vendor/autoload.php';
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
 /*


$app->match('/login', function() use($app)
{
    $Frontend = new Frontend(true);
    $stat = userStat();
    $cookieinfo = cookeInfo();
    //Cooke information
     $path = 'login';
    
    return $app['twig']->render('login.twig', array(
	'RUNALYZE_VERSION' => RUNALYZE_VERSION,
	'numUserOnline' => $stat['online'],
	'numUser' => $stat['user'],
	'numKm' => $stat['km'],
        'errorType' => SessionAccountHandler::$ErrorType,
        'cookieInfo' => $cookieinfo,
        'switchpath' => $path,
        'forgotpw' => $forgotpw,
        'USER_CAN_REGISTER' => USER_CAN_REGISTER,
        'regError' => $RegistrationErrors,
    ));
});

$app->get('/login', function() use($app)
{
    
});

$app->match('/activity/{id}', function(Request $request) use($app){
$Frontend = new Frontend();

$Context = new Context($request->get('id'), SessionAccountHandler::getId());
$View = new TrainingView($Context);
$View->display();
return '';
});


$app->match('/plugin', function(Request $request) use($app) {
$Frontend = new Frontend();
$Factory = new PluginFactory();

try {
	$Plugin = $Factory->newInstanceFor( filter_input(INPUT_GET, 'id') );
} catch (Exception $E) {
	$Plugin = null;

	echo HTML::error( __('The plugin could not be found.') );
}

if ($Plugin !== null) {
	if ($Plugin instanceof PluginPanel) {
		$Plugin->setSurroundingDivVisible(false);
	}

	$Plugin->display();
}
});
*/

$app->run();