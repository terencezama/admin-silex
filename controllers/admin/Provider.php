<?php

/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 08:08
 */
namespace AKCMS\AKAdmin;

require 'Controller.php';
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use AKCMS\AKAdmin\Controller;

class Provider implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/','AKCMS\AKAdmin\Controller::home')->bind('home');

        return $controllers;
    }
}