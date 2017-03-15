<?php

require_once('DBHelper.php');
require_once('userManager.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class RuleManager
{
    private $manager;
    private $userManager;

    function __construct($config)
    {
        $this->userManager = new UserManager($config);
        $this->connect($config);
    }

    private function connect($config)
    {
        $this->manager = new DBHelper($config);
    }

    public function createNewRule($rule_title, $rule_description, $rule_place, $author, $action_channel, $action_title, $event_channel, $event_title)
    {
        $rule = array(
            'Title' => $rule_title,
            'Description' => $rule_description,
            'Place' => $rule_place,
            'Author' => $author,
            'Event_channel' => $event_channel,
            'Event_title' => $event_title,
            'Action_channel' => $action_channel,
            'Action_title' => $action_title
        );

        $this->userManager->insertRules($rule_title, $author);
        $this->manager->insert('rules', $rule);
    }
}

?>