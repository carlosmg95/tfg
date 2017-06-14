<?php

namespace Ewetasker\Manager;

use Ewetasker\Manager\AdministrationManager;
use Ewetasker\Manager\DBHelper;
use Ewetasker\Manager\RuleManager;

include_once('administrationManager.php');
include_once('DBHelper.php');
include_once('ruleManager.php');

/**
* 
*/
class UserManager
{
    private $manager;

    function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->manager = new DBHelper();
    }

    public function createNewUser($username, $password)
    {
        $rule_manager = new RuleManager();
        $user = array(
            'username' => $username,
            'password' => $password,
            'imported_rules' => $rule_manager->getAdminRulesList(),
            'created_rules' => [],
            'chat_id' => ''
        );

        unset($rule_manager);

        if($this->userExists($username)) {
            return false;
        }
        $table = $this->manager->insert('users', $user);
        return true;
    }

    public function deleteRule($rule_title, $username)
    {
        $filter = ['username' => $username];
        $user = $this->manager->find('users', $filter)[0];
        $created_rules = $user->created_rules;
        $new_created_rules = array();
        foreach ($created_rules as $rule) {
            if ($rule_title !== $rule) {
                array_push($new_created_rules, $rule);
            }
        }

        $edited_user = array('created_rules' => $new_created_rules);

        $this->manager->update('users', 'username', $username, $edited_user);
    }

    public function getEvents($user)
    {
        $filter = ['username' => $user];
        $array_user = $this->manager->find('users', $filter)[0];
        $events = array();
        if (isset($array_user->events)) {
            foreach ($array_user->events as $event) {
                if ((getdate()[0] - $event->date) < 10) {
                    preg_match('/ewe-\w+:[a-zA-Z]+/', $event->event, $variable);
                    preg_match('/ewe-\w+:\w+/', $event->event, $variable1);
                    $event_aux = preg_replace('/' . $variable1[0] . '/', $variable[0] . $event->date, $event->event);
                    $event = array('date' => $event->date, 'event' => $event_aux);
                    array_push($events, $event);
                }
            }
        }
        return $events;
    }

    public function getImportedRules($title, $title_value)
    {
        $filter = [$title => $title_value];
        $options = ['projection' => ['imported_rules' => 1]];
        $rules_list = $this->manager->find('users', $filter, $options)[0]->imported_rules;

        return $rules_list;
    }

    public function getUser($username)
    {
        $filter = ['username' => $username];
        $array_user = $this->manager->find('users', $filter)[0];

        $username = $array_user->username;
        $password = $array_user->password;
        $imported_rules = $array_user->imported_rules;
        $created_rules = $array_user->created_rules;
        $chat_id = $array_user->chat_id;
        $n = isset($array_user->n) ? $array_user->n : 0;
        $twitter_access_token = isset($array_user->twitteraccesstoken) ? $array_user->twitteraccesstoken : 0;
        $twitter_secret_token = isset($array_user->twittersecrettoken) ? $array_user->twittersecrettoken : 0;

        $user = array(
            'username' => $username,
            'password' => $password,
            'imported_rules' => $imported_rules,
            'created_rules' => $created_rules,
            'chat_id' => $chat_id,
            'n' => $n,
            'twitteraccesstoken' => $twitter_access_token,
            'twittersecrettoken' => $twitter_secret_token
        );

        return $user;        
    }

    public function getUsernameByChatId($chat_id)
    {
        $filter = ['chat_id' => $chat_id];
        $options = ['projection' => ['username' => 1]];
        $username = $this->manager->find('users', $filter, $options)[0]->username;

        return $username;
    }

    public function getUsersList(){
        $users = $this->manager->getByTitle('users', 'username');
        $users_list = array();
        foreach ($users as $user) {
            array_push($users_list, $user->username);
        }
        return $users_list;
    }    

    public function importRule($rule_title, $username)
    {
        if ($this->ruleImported($rule_title, $username)) {
            return false;
        }
        $admin_manager = new AdministrationManager();
        $admin_manager->importRule($rule_title);
        $filter = ['username' => $username];
        $user = $this->manager->find('users', $filter)[0];
        $imported_rules = $user->imported_rules;
        array_push($imported_rules, $rule_title);

        $edited_user = array('imported_rules' => $imported_rules);

        $this->manager->update('users', 'username', $username, $edited_user);
        unset($admin_manager);

        return true;
    }

    public function importRuleToAll($rule_title)
    {
        foreach ($this->getUsersList() as $username) {
            $this->importRule($rule_title, $username);
        }
    }

    public function insertRule($rule_title, $username)
    {
        $admin_manager = new AdministrationManager();
        $admin_manager->importRule($rule_title);
        $filter = ['username' => $username];
        $user = $this->manager->find('users', $filter)[0];
        $imported_rules = $user->imported_rules;
        array_push($imported_rules, $rule_title);
        $created_rules = $user->created_rules;
        array_push($created_rules, $rule_title);

        $edited_user = array('imported_rules' => $imported_rules, 'created_rules' => $created_rules);
        unset($admin_manager);

        $this->manager->update('users', 'username', $username, $edited_user);
    }

    public function login($username, $password)
    {
        $filter = ['username' => $username, 'password' => $password];
        $cursor = $this->manager->find('users', $filter);
        return !empty($cursor);
    }

    public function removeRule($rule_title, $username)
    {
        $rule_manager = new RuleManager();
        $rule = $rule_manager->getRule($rule_title);
        if (!$this->ruleImported($rule_title, $username) || $rule['description'] === 'ADMIN RULE') {
            return false;
        }
        $admin_manager = new AdministrationManager();
        $admin_manager->removeRule($rule_title);
        $filter = ['username' => $username];
        $user = $this->manager->find('users', $filter)[0];
        $imported_rules = $user->imported_rules;
        $new_imported_rules = array();
        foreach ($imported_rules as $rule) {
            if ($rule_title !== $rule) {
                array_push($new_imported_rules, $rule);
            }
        }

        $edited_user = array('imported_rules' => $new_imported_rules);

        $this->manager->update('users', 'username', $username, $edited_user);
        unset($admin_manager);

        return true;
    }

    public function ruleImported($rule_title, $username)
    {
        $filter = ['username' => $username];
        $options = ['projection' => ['imported_rules' => 1]];
        $imported_rules = $this->manager->find('users', $filter, $options)[0]->imported_rules;

        return in_array($rule_title, $imported_rules);
    }

    public function setEvent($input, $user)
    {
        $events = $this->getEvents($user);
        $event = array('date' => getdate()[0], 'event' => $input);
        array_push($events, $event);

        $this->manager->update('users', 'username', $user, array('events' => $events));
    }

    public function setTelegramId($username, $chat_id)
    {
        return $this->manager->update('users', 'username', $username, ['chat_id' => $chat_id]);
    }

    public function updateTwitter($username, $accesstoken, $secrettoken)
    {
        $edited_user = array('twitteraccesstoken' => $accesstoken, 'twittersecrettoken' => $secrettoken);
        $this->manager->update('users', 'username', $username, $edited_user);
    }

    private function userExists($username)
    {
        $filter = ['username' => $username];
        $cursor = $this->manager->find('users', $filter);
        return !empty($cursor);
    }
}