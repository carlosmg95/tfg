<?php

require_once('DBHelper.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class UserManager
{
    private $manager;

    function __construct($config)
    {
        $this->connect($config);
    }

    public function connect($config)
    {
        $this->manager = new DBHelper($config);
    }

    public function createNewUser($username, $password)
    {
        $user = array('username' => $username, 'password' => $password);

        if($this->userExists($username)) {
            return false;
        } else {
            $table = $this->manager->insert('users', $user);
            return true;
        }
    }

    public function login($username, $password)
    {
        $filter = ['username' => $username, 'password' => $password];
        $cursor = $this->manager->find('users', $filter);
        return !empty($cursor);
    }

    private function userExists($username)
    {
        $filter = ['username' => $username];
        $cursor = $this->manager->find('users', $filter);
        return !empty($cursor);
    }
}

?>