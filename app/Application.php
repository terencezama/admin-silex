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

class Application extends \Silex\Application
{

    function __construct(array $values)
    {
        parent::__construct($values);



        $this->registerProviders();

    }



    private function registerProviders(){
        $this->register(new YamlConfigServiceProvider($this['root'].'/config/config.yml'));

        $this->mount('/api', new \AKCMS\AKAPI\Provider());
        $this->mount('/admin', new \AKCMS\AKAdmin\Provider());
        $this->mount('/', new \AKCMS\AKFront\Provider());

    }



}