<?php
namespace Runalyze;
 
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use \Cache;

function userStat() {
    \DB::getInstance()->stopAddingAccountID();
    $stat['user'] = Cache::get('NumUser', 1);
    if ($NumUser == NULL) {
        $stat['user'] = \DB::getInstance()->query('SELECT COUNT(*) FROM '.PREFIX.'account WHERE activation_hash = ""')->fetchColumn();
        Cache::set('NumUser', $NumUser, '500', 1);
    }

    $km = Cache::get('NumKm', 1);
    if ($NumKm == NULL) {
        $km= \DB::getInstance()->query('SELECT SUM(distance) FROM '.PREFIX.'training')->fetchColumn();
        Cache::set('NumKm', $NumKm, '500', 1);
    }
    $stat['km'] = Activity\Distance::format($km);
    \DB::getInstance()->startAddingAccountID();
    $stat['online'] = \SessionAccountHandler::getNumberOfUserOnline();
    return $stat;
}
function cookeInfo() {
    setcookie('CookieInfo', 'true', time()+30*86400);
    return $_COOKIE['CookieInfo'];
}

class HomeController
{
    public function loginAction(Application $app)
    {
            $Frontend = new \Frontend(true);
            $stat = userStat();
            $cookieinfo = cookeInfo();
            $path = 'login';
            $response =  $app['twig']->render('login.twig', array(
                'RUNALYZE_VERSION' => RUNALYZE_VERSION,
                'numUserOnline' => $stat['online'],
                'numUser' => $stat['user'],
                'numKm' => $stat['km'],
                'errorType' => \SessionAccountHandler::$ErrorType,
                'cookieInfo' => $cookieinfo,
                'switchpath' => $path,
                'forgotpw' => $forgotpw,
                'USER_CAN_REGISTER' => USER_CAN_REGISTER,
                'regError' => $RegistrationErrors,
            ));
            return new Response($response);
    }
    
    public function logoutAction() {
       $Frontend = new \Frontend(true);
       \SessionAccountHandler::logout(); 
       header('Location: '.\System::getFullDomain().'login');
       return '';
    }
    
 
    public function registerAction(Application $app)
    {
        $Frontend = new \Frontend(true);
        $path = 'register'; 
        $stat = userStat();
        $cookieinfo = cookeInfo();
        if($_POST['new_username'])
            $RegistrationErrors = \AccountHandler::tryToRegisterNewUser();
                
        $response =  $app['twig']->render('login.twig', array(
                    'RUNALYZE_VERSION' => RUNALYZE_VERSION,
                    'numUserOnline' => $stat['online'],
                    'numUser' => $stat['user'],
                    'numKm' => $stat['km'],
                    'errorType' => \SessionAccountHandler::$ErrorType,
                    'cookieInfo' => $cookieinfo,
                    'switchpath' => $path,
                    'forgotpw' => $forgotpw,
                    'USER_CAN_REGISTER' => USER_CAN_REGISTER,
                    'regError' => $RegistrationErrors,
                ));
                return new Response($response);
    }
    
    public function forgotPwAction(Application $app)
    {
        $Frontend = new \Frontend(true);
        $path = 'forgotpw';    
        $forgotpw =   \AccountHandler::sendPasswordLinkTo($_POST['send_username']);
        $stat = userStat();
        $cookieinfo = cookeInfo();
        if($_POST['send_username'])
             $forgotpw =   \AccountHandler::sendPasswordLinkTo($_POST['send_username']);
            
                
        $response =  $app['twig']->render('login.twig', array(
                    'RUNALYZE_VERSION' => RUNALYZE_VERSION,
                    'numUserOnline' => $stat['online'],
                    'numUser' => $stat['user'],
                    'numKm' => $stat['km'],
                    'errorType' => \SessionAccountHandler::$ErrorType,
                    'cookieInfo' => $cookieinfo,
                    'switchpath' => $path,
                    'forgotpw' => $forgotpw,
                    'USER_CAN_REGISTER' => USER_CAN_REGISTER,
                    'regError' => $RegistrationErrors,
                ));
                return new Response($response);
    }
    
    public function activateAction(Application $app, $hash)
    {
        $Frontend = new \Frontend(true);
        $ActivateAccount = \AccountHandler::tryToActivateAccount($hash);
        
                
        $response =  $app['twig']->render('activateAccount.html.twig', array(
                    'activateAccount' => $ActivateAccount,
                ));
                return new Response($response);
    }
    
    public function deleteAction(Application $app, $hash)
    {
        $Frontend = new \Frontend(true);
        if($_GET['true']) 
            $deleteAccount = \AccountHandler::tryToDeleteAccount($hash);
        if (isset($_GET['delete'])) 
            SessionAccountHandler::logout();
                
        $response =  $app['twig']->render('deleteAccount.html.twig', array(
                    'deleteAccount' => $deleteAccount,
                    'hash' => $hash,
                ));
                return new Response($response);
    }
    
    public function changePasswordAction(Application $app, $hash)
    {
        $Frontend = new \Frontend(true);
        $errors = \AccountHandler::tryToSetNewPassword();
        $user   = \AccountHandler::getUsernameForChangePasswordHash();
                
        $response =  $app['twig']->render('setNewPassword.html.twig', array(
                    'errors' => $errors,
                    'hash' => $hash,
                    'user' => $user,
                ));
                return new Response($response);
    }
    
    public function appAction()
    {
        $Frontend = new \Frontend();
        ?> 
        <div id="container">
                <div id="main">
                        <div id="data-browser" class="panel">
                                <div id="data-browser-inner">
                                        <?php
                                        $DataBrowser = new \DataBrowser();
                                        $DataBrowser->display();
                                        ?>
                                </div>
                        </div>

                        <div id="statistics" class="panel">
                                <ul id="statistics-nav">
                                        <?php
                                        $Factory = new \PluginFactory();
                                        $Stats = $Factory->activePlugins( \PluginType::Stat );
                                        foreach ($Stats as $i => $key) {
                                                $Plugin = $Factory->newInstance($key);

                                                if ($Plugin !== false) {
                                                        echo '<li'.($i == 0 ? ' class="active"' : '').'>'.$Plugin->getLink().'</li>';
                                                }
                                        }

                                        if (\PluginStat::hasVariousStats()) {
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
                                                $Context = new \Context(Request::sendId(), \SessionAccountHandler::getId());
                                                $View = new \TrainingView($Context);
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
            }
            
    public function helpAction(Application $app)
    {
        $Frontend = new \Frontend();
        return new Response($app['twig']->render('help.html.twig'));
    }    
    
    public function SiteAction(Application $app, $sitename)
    {
        return new Response($app['twig']->render('sites/'.$sitename.'.twig'));
    }
    
}