<?php

/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 08:08
 */
namespace AKCMS\AKAdmin;
use AKCMS\Application;
use Symfony\Component\HttpFoundation\Request;

class Controller
{
    public function home(Request $request, Application $app)
    {
        return $app['twig']->render('admin/index.twig');
    }

}