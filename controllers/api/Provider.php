<?php

/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 08:08
 */
namespace AKCMS\AKApi;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
require 'Controller.php';

use AKCMS\AKApi\Controller;

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
        $controllers = $app['controllers_factory'];
        $app['section'] = '/api';

        $controllers->post('/login','AKCMS\AKApi\Controller::login')->bind('api.login');
        $controllers->get('/test','AKCMS\AKApi\Controller::test')->bind('api.test');

        return $controllers;
    }
}