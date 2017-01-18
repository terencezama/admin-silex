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


use AKCMS\AKAdmin\DB;
use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use SimpleUser\UserServiceProvider;

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
        $this->addSimpleUser();
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

    private function addSimpleUser(){

        // Register the SimpleUser service provider.
        $simpleUserProvider = new UserServiceProvider();
        $this->register($simpleUserProvider);
        // Mount the user controller routes:
        $this->mount('/user', $simpleUserProvider);
        // Security config. See http://silex.sensiolabs.org/doc/providers/security.html for details.
        $this['security.firewalls'] = array(
            /* // Ensure that the login page is accessible to all, if you set anonymous => false below.
            'login' => array(
                'pattern' => '^/user/login$',
            ), */
            'secured_area' => array(
                'pattern' => '^.*$',
                'anonymous' => true,
                'remember_me' => array(),
                'form' => array(
                    'login_path' => '/user/login',
                    'check_path' => '/user/login_check',
                ),
                'logout' => array(
                    'logout_path' => '/user/logout',
                ),
                'users' => $this->share(function($app) { return $app['user.manager']; }),
            ),
        );

    }



}