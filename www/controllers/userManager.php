<?php

require_once('./DBHelper.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class UserManager
{
    private $db;

    function __construct($config)
    {
        $this->connect($config);
    }

    function connect($config)
    {
        $this->db = new DBHelper($config);
    }

    function createNewUser($username, $password)
    {
        $user = array('username' => $username, 'password' => $password);

        if(/*$this->userExists()*/false) {
            return false;
        } else {
            $table = $this->db->insert('users', $user);
            return true;
        }
    }
}

?>