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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;


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
            $stack['title'] = "create <strong>$table_name</strong>";
            $stack['link'] = "/admin/dev?action=create_".strtolower($table_name)."_table";
            $stack['badge_title'] = 'not_created';
            $stack['badge_color'] = 'bg-red';
        }else{
            $stack['title'] = "delete <strong>$table_name</strong>";
            $stack['link'] = "/admin/dev?action=delete_table&table=".strtolower($table_name);
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
        $app = $this->app;
        if (!$this->schema->tablesExist('users')) {
            $users = new Table('users');

            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('roles', 'string', array('length' => 255));

            $users->setPrimaryKey(array('id'));
            $users->addUniqueIndex(array('username'));

            $this->schema->createTable($users);

            $encoder = new MessageDigestPasswordEncoder();
            $app['db']->insert('users', array(
                'username' => 'user',
                'password' => $encoder->encodePassword('1234',''),
                'roles' => 'ROLE_USER'
            ));

            $app['db']->insert('users', array(
                'username' => 'admin',
                'password' => $encoder->encodePassword('1234',''),
                'roles' => 'ROLE_ADMIN'
            ));
        }
    }


}