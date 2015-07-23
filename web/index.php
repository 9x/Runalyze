<?php
require '../inc/class.Frontend.php';
use Symfony\Component\HttpFoundation\Request;
use Runalyze\View\Activity\Context;
//$Frontend = new Frontend(true);
require_once '../vendor/autoload.php';
$app = new \Silex\Application();
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

function userStat() {
    DB::getInstance()->stopAddingAccountID();
    $stat['user'] = Cache::get('NumUser', 1);
    if ($NumUser == NULL) {
        $stat['user'] = DB::getInstance()->query('SELECT COUNT(*) FROM '.PREFIX.'account WHERE activation_hash = ""')->fetchColumn();
        Cache::set('NumUser', $NumUser, '500', 1);
    }

    $km = Cache::get('NumKm', 1);
    if ($NumKm == NULL) {
        $km= DB::getInstance()->query('SELECT SUM(distance) FROM '.PREFIX.'training')->fetchColumn();
        Cache::set('NumKm', $NumKm, '500', 1);
    }
    $stat['km'] = Runalyze\Activity\Distance::format($km);
    DB::getInstance()->startAddingAccountID();
    $stat['online'] = SessionAccountHandler::getNumberOfUserOnline();
    return $stat;
}
function cookeInfo() {
    setcookie('CookieInfo', 'true', time()+30*86400);
    return $_COOKIE['CookieInfo'];
}
function switchStart() {
    if($_POST['new_username']) {
        $path = 'register';
        $RegistrationErrors = AccountHandler::tryToRegisterNewUser();
    } elseif($_POST['username'])
        $path = 'login';
    elseif($_POST['send_username']) {
        $path = 'forgotpw';    
        $forgotpw =  AccountHandler::sendPasswordLinkTo($_POST['send_username']);
    } else
        $path = 'login';
}

$app->match('/', function() use($app)
{
    $Frontend = new Frontend();
?> 
<div id="container">
	<div id="main">
		<div id="data-browser" class="panel">
			<div id="data-browser-inner">
				<?php
				$DataBrowser = new DataBrowser();
				$DataBrowser->display();
				?>
			</div>
		</div>

		<div id="statistics" class="panel">
			<ul id="statistics-nav">
				<?php
				$Factory = new PluginFactory();
				$Stats = $Factory->activePlugins( PluginType::Stat );
				foreach ($Stats as $i => $key) {
					$Plugin = $Factory->newInstance($key);

					if ($Plugin !== false) {
						echo '<li'.($i == 0 ? ' class="active"' : '').'>'.$Plugin->getLink().'</li>';
					}
				}

				if (PluginStat::hasVariousStats()) {
					echo '<li class="with-submenu">';
					echo '<a href="#">'.__('Miscellaneous').'</a>';
					echo '<ul class="submenu">';

					$VariousStats = $Factory->variousPlugins();
					foreach ($VariousStats as $key) {
						$Plugin = $Factory->newInstance($key);

						if ($Plugin !== false) {
							echo '<li>'.$Plugin->getLink().'</li>';
						}
					}

					echo '</ul>';
					echo '</li>';
				}
				?>
			</ul>
			<div id="statistics-inner">
				<?php
				if (isset($_GET['id'])) {
					$Context = new Context(Request::sendId(), SessionAccountHandler::getId());
					$View = new TrainingView($Context);
					$View->display();
				} elseif (isset($_GET['pluginid'])) {
					$Factory->newInstanceFor((int)$_GET['pluginid'])->display();
				} else {
					if (empty($Stats)) {
						echo __('<em>There are no statistics available. Activate a plugin in your configuration.</em>');
					} else {
						$Factory->newInstance($Stats[0])->display();
					}
				}
				?>
			</div>
		</div>

	</div>

	<div id="panels">
		<?php $Frontend->displayPanels(); ?>
	</div>
</div>
<?php
return '';
});


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
$app->match('/settings/', function() use($app)
{
$Frontend = new Frontend();

$ConfigTabs = new ConfigTabs();
$ConfigTabs->addDefaultTab(new ConfigTabGeneral());
$ConfigTabs->addTab(new ConfigTabPlugins());
$ConfigTabs->addTab(new ConfigTabDataset());
$ConfigTabs->addTab(new ConfigTabSports());
$ConfigTabs->addTab(new ConfigTabTypes());
$ConfigTabs->addTab(new ConfigTabClothes());
$ConfigTabs->addTab(new ConfigTabAccount());
$ConfigTabs->display();

echo Ajax::wrapJSforDocumentReady('Runalyze.Overlay.removeClasses();');
return '';
});
$app->match('/settings/{key}', function(Request $request) use($app)
{
$Frontend = new Frontend();
 echo $request->get('key');
$ConfigTabs = new ConfigTabs();
$ConfigTabs->addDefaultTab(new ConfigTabGeneral());
$ConfigTabs->addTab(new ConfigTabPlugins());
$ConfigTabs->addTab(new ConfigTabDataset());
$ConfigTabs->addTab(new ConfigTabSports());
$ConfigTabs->addTab(new ConfigTabTypes());
$ConfigTabs->addTab(new ConfigTabClothes());
$ConfigTabs->addTab(new ConfigTabAccount());
$ConfigTabs->display();

echo Ajax::wrapJSforDocumentReady('Runalyze.Overlay.removeClasses();');
return '';
});
$app->match('/activity/{id}', function(Request $request) use($app){
$Frontend = new Frontend();

$Context = new Context($request->get('id'), SessionAccountHandler::getId());
$View = new TrainingView($Context);
$View->display();
return '';
});
$app->match('/databrowser', function(Request $request) use($app) {
    $Frontend = new Frontend();
$DataBrowser = new DataBrowser();
$DataBrowser->display();
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

$app->match('/site/{name}', function(Request $request) use($app) {
    
    return $app['twig']->render($request->get('name').'.twig');
});



$app->run();