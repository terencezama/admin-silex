<?php
/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 07:26
 */

namespace AKCMS;
require_once 'controllers/admin/Provider.php';
require_once 'controllers/api/Provider.php';
require_once 'controllers/home/Provider.php';
require_once 'app/Provider/UserProvider.php';

use AKCMS\AKAdmin\DB;
use AKCMS\AKAdmin\UserProvider;
use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class Application extends \Silex\Application
{

    function __construct(array $values)
    {
        parent::__construct($values);

        $this['debug'] = true;


        $this->registerProviders();
        $this->registerServices();
        $this->mountControllers();
        $this->addTwigExtensions();
        $this->setSecurityOptions();

    }



    private function registerProviders(){
        $this->register(new YamlConfigServiceProvider($this['root'].'/config/config.yml'));

        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['root'].'/views',
        ));

        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_sqlite',
                'path'     => $this['root'].'/app.db',
            ),
        ));

        $this->register(new SecurityServiceProvider());
        $this->register(new RememberMeServiceProvider());
        $this->register(new SessionServiceProvider());
        $this->register(new ServiceControllerServiceProvider());
        $this->register(new UrlGeneratorServiceProvider());
        $this->register(new SwiftmailerServiceProvider());



    }

    public function registerServices(){

    }

    private function mountControllers(){
        $this->mount('/api', new \AKCMS\AKAPI\Provider());
        $this->mount('/admin', new \AKCMS\AKAdmin\Provider());
        $this->mount('/', new \AKCMS\AKFront\Provider());
    }

    private function addTwigExtensions(){
        $app = $this;
        $app['twig'] = $app->share($app->extend('twig', function($twig,$app) {
            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset,$path = null) use($app) {
                //return $app['root'].'/'. (isset($path))?$path.'/':''.$asset;
                if(isset($path)){
                    return '/assets/'.$path.'/'.$asset;
                }else{
                    return '/assets/'.$asset;
                }

            }));

            return $twig;
        }));
    }

    private function setSecurityOptions(){
        $app = $this;

        $app['security.firewalls'] = array(
            'admin' => array(
                'pattern' => '^/admin/',
                'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
                'logout' => array('logout_path' => '/admin/logout', 'invalidate_session' => true),
                'users' => $app->share(function () use ($app) {
                    return new UserProvider($app['db']);
                }),
            ),
        );

        $app->get('/login', function(Request $request) use ($app) {
            return $app['twig']->render('admin/login.twig', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
        });
    }




}