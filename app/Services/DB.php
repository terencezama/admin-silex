<?php
/**
 * Created by PhpStorm.
 * User: terence
 * Date: 18/01/2017
 * Time: 23:38
 */

namespace AKCMS\AKAdmin;


use AKCMS\Application;
use Exception;


class DB
{
    var $app;
    function __construct(Application $app)
    {
        $this->app = $app;
    }


    function table_exists($table_name){
        try{
            $this->app['db']->query("DESC $table_name");
        }catch (Exception $e){
            return false;
        }
        return true;
    }

    function dev_table($table_name){
        $stack = array();
        if(!$this->table_exists($table_name)){
            $stack['title'] = "Create $table_name";
            $stack['link'] = "/admin/dev?action=create_".strtolower($table_name);
            $stack['badge_title'] = 'not_created';
            $stack['badge_color'] = 'bg-red';
        }else{
            $stack['title'] = "Delete $table_name";
            $stack['link'] = "/admin/dev?action=delete_".strtolower($table_name);
            $stack['badge_title'] = 'created';
            $stack['badge_color'] = 'bg-green';
        }
        return $stack;
    }


}