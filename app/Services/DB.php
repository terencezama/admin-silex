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
        $table = new Table('users');

        $table->addColumn('id','integer')->setUnsigned(true)->setAutoincrement(true);
        $table->addColumn('email','string')->setNotnull(true)->setDefault('');
        $table->addColumn('password','string')->setNotnull(true)->setDefault('');
        $table->addColumn('salt','string')->setNotnull(true)->setDefault('');
        $table->addColumn('roles','string')->setNotnull(true)->setDefault('');
        $table->addColumn('name','string')->setNotnull(true)->setDefault('');
        $table->addColumn('time_created','integer')->setNotnull(true)->setDefault(0);
        $table->addColumn('username','string')->setNotnull(true)->setDefault('');
        $table->addColumn('isEnabled','boolean')->setNotnull(true)->setDefault(true);
        $table->addColumn('confirmationToken','string');
        $table->addColumn('timePasswordRestRequested','integer')->setUnsigned(true);

        $table->setPrimaryKey(array('id'));
        $table->addUniqueIndex(array('email','username'));

        $this->schema->createTable($table);
    }


}