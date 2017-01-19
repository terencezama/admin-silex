<?php
/**
 * Created by PhpStorm.
 * User: terence
 * Date: 18/01/2017
 * Time: 23:38
 */

namespace AKCMS\AKAdmin;


use AKCMS\Application;
use Doctrine\DBAL\Schema\Table;
use Exception;


class DB
{
    var $app;
    var $schema;
    function __construct(Application $app)
    {
        $this->app = $app;
        $this->schema = $this->app['db']->getSchemaManager();
    }


    function dev_table($table_name){

        $stack = array();
        if(!$this->schema->tablesExist($table_name)){
            $stack['title'] = "Create $table_name";
            $stack['link'] = "/admin/dev?action=create_".strtolower($table_name)."_table";
            $stack['badge_title'] = 'not_created';
            $stack['badge_color'] = 'bg-red';
        }else{
            $stack['title'] = "Delete $table_name";
            $stack['link'] = "/admin/dev?action=delete_".strtolower($table_name)."_table";
            $stack['badge_title'] = 'created';
            $stack['badge_color'] = 'bg-green';
        }
        return $stack;
    }

    function create_table($table_name){
        $table = $this->schema->createTable($table_name);
    }

    function delete_table($table_name){
        $this->schema->dropTable($table_name);
    }

    function create_users_table(){
        $table =
        $table = $this->schema->createTable('users');
        return json_encode($table);
    }


}