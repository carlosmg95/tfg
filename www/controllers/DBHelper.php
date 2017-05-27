<?php

namespace Ewetasker\Manager;

/**
* 
*/
class DBHelper
{
    
    private $manager;
    private $config;

    function __construct()
    {
        $dbhost = /*'127.0.0.1';//*/$_ENV['MONGO_HOST'];
        $dbname = /*'applicationdb';//*/$_ENV['MONGO_DB'];
        $port = /*'27017';//*/$_ENV['MONGO_PORT'];
        $username = /*'client';//*/$_ENV['MONGO_USER'];
        $password = /*'gsimongodb2015';//*/$_ENV['MONGO_PASS'];

        $this->config = array(
            'username' => $username,
            'password' => $password,
            'dbname'   => $dbname,
            'connection_string'=> sprintf('mongodb://%s:%s@%s:%d/%s', $username, $password, $dbhost, $port, $dbname)
        );
        $this->connect();
    }

    private function connect()
    {
        try{
            if (!class_exists('\MongoDB\Driver\Manager')){
                echo ('The \MongoDB PECL extension has not been installed or enabled');
                return false;
            }
            $manager = new \MongoDB\Driver\Manager($this->config['connection_string']);
            return $this->manager = $manager;
        } catch(Exception $e) {
            echo $e;
            return false;
        }
    }

    public function find($collection, $filter=[], $options=[])
    {
        $query = new \MongoDB\Driver\Query($filter, $options);
        $dbCollection = $this->config['dbname'] . '.' . $collection;
        return $this->manager->executeQuery($dbCollection, $query)->toArray();
    }

    public function getByTitle($collection, $title)
    {
        $options = ['projection' => [$title => 1], 'sort' => [$title => 1]]; 

        $cursor = $this->find($collection, [], $options);

        return $cursor;
    }

    public function insert($collection, $article)
    {
        $bulk = new \MongoDB\Driver\BulkWrite();

        $_article = array();
        foreach ($article as $title => $value) {
            $_article[$title] = $value;
        }
        $_article['createdAt'] = date_format(new \DateTime(), 'Y-m-d H:i:s');
        $bulk->insert($_article);

        $dbCollection = $this->config['dbname'] . '.' . $collection;
        $result = $this->manager->executeBulkWrite($dbCollection, $bulk);
    }

    public function remove($collection, $title, $value)
    {
        $bulk = new \MongoDB\Driver\BulkWrite;
        $bulk->delete([$title => $value], ['limit' => 0]);
        $dbCollection = $this->config['dbname'] . '.' . $collection;

        return $this->manager->executeBulkWrite($dbCollection, $bulk);
    }

    public function update($collection, $title, $title_value, $article)
    {
        $bulk = new \MongoDB\Driver\BulkWrite;
        $bulk->update(
            [$title => $title_value],
            ['$set' => $article]
        );
        $dbCollection = $this->config['dbname'] . '.' . $collection;

        return $this->manager->executeBulkWrite($dbCollection, $bulk);
    }
}