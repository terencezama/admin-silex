<?php
/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 07:26
 */

namespace akadmin;
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

        $this->mount('/', new \Provider());

    }



}