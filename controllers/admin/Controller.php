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
require_once 'app/Services/DB.php';
class Controller
{
    var $context = array();

    public function home(Request $request, Application $app)
    {
        $this->setPage('Admin','section');
        return $app['twig']->render('admin/index.twig',$this->context);
    }

    public function dev(Request $request, Application $app)
    {
        $this->setPage('Dev','Options');
        $this->context["db"] = new DB($app);

        if(isset($_GET['action'])){
            $this->context["db"]->$_GET['action']();
        }
        return $app['twig']->render('admin/dev.twig',$this->context);
    }

    //region Utils
    private function setPage($title,$description){
        $this->context['page'] = array(
            "title" => $title,
            "description" => $description
        );
    }
    //endregion

}