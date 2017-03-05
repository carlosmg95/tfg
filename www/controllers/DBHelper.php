<?php

/**
* 
*/
class DBHelper
{
    
    private $db;

    function __construct($config)
    {
        $this->connect($config);
    }

    private function connect($config)
    {
        try{
            if (!class_exists('Mongo')){
                echo ("The MongoDB PECL extension has not been installed or enabled");
                //return false;
            }
            $connection = new MongoClient();/*
                $config['connection_string'],
                array('username' => $config['username'], 'password' => $config['password'])
            );*/
            $this->db = $connection->selectDB('applicationdb');//$config['dbname']);
        } catch(Exception $e) {
            echo $e;
            return false;
        }
    }

    function insert($collection, $article)
    {
        $table = $this->db->selectCollection($collection);
        $table->insert($article);
    }
}