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

    private function connect($config)
    {
        $this->manager = new DBHelper($config);
    }

    public function createNewUser($username, $password)
    {
        $user = array('username' => $username, 'password' => $password, 'imported_rules' => [], 'created_rules' => []);

        if($this->userExists($username)) {
            return false;
        } else {
            $table = $this->manager->insert('users', $user);
            return true;
        }
    }

    public function insertRules($rule_title, $username)
    {
        $file = fopen("archivo.txt", "w");
        $filter = ['username' => $username];
        $user = $this->manager->find('users', $filter)[0];
        fwrite($file, implode('-',$user) ."" . PHP_EOL);
        $imported_rules = $user->imported_rules;
        array_push($imported_rules, $rule_title);
        $created_rules = $user->created_rules;
        array_push($created_rules, $rule_title);

        $edited_user = array('imported_rules' => $imported_rules, 'created_rules' => $created_rules);

        $this->manager->update('users', 'username', $username, $edited_user);
        fclose($file);
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