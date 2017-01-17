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


use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Provider\TwigServiceProvider;

class Application extends \Silex\Application
{

    function __construct(array $values)
    {
        parent::__construct($values);

        $this['debug'] = true;


        $this->registerProviders();
        $this->mountControllers();
        $this->addTwigExtensions();

    }



    private function registerProviders(){
        $this->register(new YamlConfigServiceProvider($this['root'].'/config/config.yml'));

        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['root'].'/views',
        ));



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
                    return $app['root'].'/assets/'.$path.'/'.$asset;
                }else{
                    return $app['root'].'/assets/'.$asset;
                }

            }));

            return $twig;
        }));
    }



}