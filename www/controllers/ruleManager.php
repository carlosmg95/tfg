<?php

namespace Ewetasker\Manager;

use Ewetasker\Manager\AdministrationManager;
use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\DBHelper;
use Ewetasker\Manager\UserManager;

include_once('administrationManager.php');
include_once('channelManager.php');
include_once('DBHelper.php');
include_once('userManager.php');

/**
* 
*/
class RuleManager
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

    public function createNewRule($rule_title, $rule_description, $rule_place, $author, $action_channels, $action_titles, $event_channels, $event_titles, $rule)
    {
        $user_manager = new UserManager();

        if ($this->ruleExists($rule_title)) {
            return false;
        }
        if ($rule_description === 'ADMIN RULE' && $author !== 'admin') {
            return false;
        }

        $rule = array(
            'title' => $rule_title,
            'description' => $rule_description,
            'place' => $rule_place,
            'author' => $author,
            'event_channels' => $event_channels,
            'event_titles' => $event_titles,
            'action_channels' => $action_channels,
            'action_titles' => $action_titles,
            'rule' => $rule
        );

        if (in_array('', $rule)) {
            return false;
        }

        $user_manager->insertRule($rule_title, $author);
        $this->manager->insert('rules', $rule);

        unset($user_manager);

        return true;
    }

    public function deleteRule($rule_title)
    {
        if (in_array($rule_title, $this->getAdminRulesList())) {
            return false;
        }
        $admin_manager = new AdministrationManager();
        $admin_manager->deleteRule($rule_title);
        $user_manager = new UserManager();
        $users = $user_manager->getUsersList();
        foreach ($users as $username) {
            if ($username === $this->getAuthor($rule_title)) {
                $user_manager->deleteRule($rule_title, $username);
            }
            $user_manager->removeRule($rule_title, $username);
        }
        unset($admin_manager);
        unset($user_manager);
        return $this->manager->remove('rules', 'title', $rule_title);
    }

    public function getAdminRulesList()
    {
        $admin_rules = array();
        foreach ($this->getRulesList() as $rule_title) {
            $rule = $this->getRule($rule_title);
            if ($rule['description'] === 'ADMIN RULE') {
                array_push($admin_rules, $rule_title);
            }
        }
        return $admin_rules;
    }

    public function getAuthor($rule_title)
    {
        $filter = ['title' => $rule_title];
        $options = ['projection' => ['author' =>1]];
        $author = $this->manager->find('rules', $filter, $options)[0]->author;
        return $author;
    }

    public function getPlaces()
    {
        $rules = $this->manager->find('rules');
        $places = array();
        foreach ($rules as $rule) {
            if (!in_array($rule->place, $places)) {
                array_push($places, $rule->place);
            }
        }
        return $places;
    }

    public function getRule($title)
    {
        $filter = ['title' => $title];
        $array_rule = $this->manager->find('rules', $filter)[0];

        $title = $array_rule->title;
        $description = $array_rule->description;
        $place = $array_rule->place;
        $author = $array_rule->author;
        $event_channels = $array_rule->event_channels;
        $event_titles = $array_rule->event_titles;
        $action_channels = $array_rule->action_channels;
        $action_titles = $array_rule->action_titles;
        $rule = $array_rule->rule;

        $rule = array(
            'title' => $title,
            'description' => $description,
            'place' => $place,
            'author' => $author,
            'event_channels' => $event_channels,
            'event_titles' => $event_titles,
            'action_channels' => $action_channels,
            'action_titles' => $action_titles,
            'rule' => $rule
        );

        return $rule;
    }

    private function getRuleHTML($rule, $imported)
    {
        $channel_manager = new ChannelManager();

        if ($imported) {
            $importButton = 'remove';
        } else {
            $importButton = 'import';
        }
        $action_channels_name = $rule->action_channels;
        $event_channels_name = $rule->event_channels;
        $action_channels = array();
        $event_channels = array();
        foreach ($action_channels_name as $action_channel) {
            array_push($action_channels, $channel_manager->getChannel($action_channel));
        }
        foreach ($event_channels_name as $event_channel) {
            array_push($event_channels, $channel_manager->getChannel($event_channel));
        }

        $actions = $this->viewActionsHTML($action_channels, $rule->action_titles);
        $events = $this->viewEventsHTML($event_channels, $rule->event_titles);
        $title = $rule->title;
        $description = $rule->description;
        $author = $rule->author;
        $place = $rule->place;
        $place_class = preg_replace('/\s+/', '', $place);
        $date = date_format(new \DateTime($rule->createdAt), 'H:m d/m/Y');
        $buttons = '';

        if (isset($_SESSION['user']) && ($_SESSION['user'] === 'admin' || $_SESSION['user'] === $author)) {
            $buttons = '
            <!-- Rule buttons -->
            <div class="col-md-2 rule-fragment">
                <button type="button" class="btn btn-info btn-rules-action" onclick="window.location=\'./editrule.php?ruleTitle=' . $title . '\'">Edit</button>
                <button type="button" class="btn btn-danger btn-rules-action" onclick="window.location=\'./deleterule.php?ruleTitle=' . $title . '\'">Delete</button>
            </div>';
        }

        unset($channel_manager);

        echo '
            <!-- Rule Item -->
            <div class="row rule-item ' . $place_class . '">
                <!-- Rule title -->
                <div class="col-md-12">
                    <h2 style="text-align:center;">' . $title . '</h2>
                </div>  <!-- Title -->

                <!-- Import button -->
                <div class="col-md-1 col-md-offset-1 rule-fragment">
                    <button type="button" class="btn btn-primary btn-activate" onclick="window.location=\'./' . $importButton . 'rule.php?ruleTitle=' . $title . '\'">
                         ' . $importButton . '
                    </button>
                </div>  <!-- Import -->
                
                <!-- Events info -->
                <div class="col-md-2 rule-fragment">
                    ' . $events . '
                </div>  <!-- Info -->

                <div class="col-md-1 rule-fragment">
                    <img class="img img-responsive img-arrow" src="img/arrow.png" />
                </div>

                <!-- Actions info -->
                <div class="col-md-2 rule-fragment">
                    ' . $actions . '
                </div>  <!-- Info -->

                <!-- Rule info -->
                <div class="col-md-3 rule-fragment rule-info">
                    <p>' . $description . '.</p>
                    <p>' . $author . '</p>
                    <p>' . $place . '</p>
                    <p>' . $date . '</p>
                </div>  <!-- Info -->

                ' . $buttons . '
            </div>  <!-- row -->
        ';
    }

    public function getRulesList()
    {
        $rules = $this->manager->getByTitle('rules', 'title');
        $rules_list = array();
        foreach ($rules as $rule) {
            array_push($rules_list, $rule->title);
        }
        return $rules_list;
    }

    public function getRulesTest()
    {
        $html_str = '';
        $rules_list = $this->getRulesList();
        $admin_rules_list = $this->getAdminRulesList();
        foreach ($rules_list as $rule_title) {
            if (in_array($rule_title, $admin_rules_list))
                continue;
            $rule = $this->getRule($rule_title);
            $title = $rule['title'];
            $rule = preg_replace('/"/', '&quot;', $rule['rule']);
            $rule = preg_replace('/</', '&lt;', $rule);
            $rule = preg_replace('/>/', '&gt;', $rule);
            $rule = preg_split('/[\r\n]+/', $rule);
            $rule_str = '';
            foreach ($rule as $value_rule) {
                $rule_str = $rule_str . $value_rule . '\r\n';
            }
            $html_str .= '
            <div class="row">
                <button class="btn btn-success btn-rules" onclick="rule(\'' . $rule_str . '\')">' . $title . '</button>
            </div>
            ' . PHP_EOL;
        }
        echo $html_str;
    }

    public function getURLPlace($place)
    {
        $filter = ['place' => $place];
        $array_place = $this->manager->find('places', $filter)[0];

        return isset($array_place->url) ? $array_place->url : '';
    }

    public function newPlace($place, $url)
    {
        $filter = ['place' => $place];
        $cursor = $this->manager->find('places', $filter);
        if (empty($cursor))
            $this->manager->insert('places', array('place' => $place, 'url' => $url));
    }

    private function ruleExists($title)
    {
        $filter = ['title' => $title];
        $cursor = $this->manager->find('rules', $filter);
        return !empty($cursor);
    }

    private function viewActionsHTML($action_channels, $action_titles)
    {
        $i = 0;
        $actions = '';
        foreach ($action_channels as $action_channel) {
            $action_img = $action_channel['image'];
            $action_title = $action_titles[$i];
            $i++;

            $actions = $actions . '
                <!-- Action info -->
                <div class="row">
                    <div class="col-md-12 rule-fragment">
                        <!-- Action-Channel image -->
                        <div class="row">
                            <img class="img img-circle img-responsive img-channel" src="' . $action_img . '" />
                        </div>

                        <!-- Action title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $action_title . '</h4>
                        </div>
                    </div>
                </div>  <!-- Info -->
            ';
        }
        return $actions;
    }

    private function viewEventsHTML($event_channels, $event_titles)
    {
        $i = 0;
        $events = '';
        foreach ($event_channels as $event_channel) {
            $event_img = $event_channel['image'];
            $event_title = $event_titles[$i];
            $i++;

            $events = $events . '
                <!-- Event info -->
                <div class="row">
                    <div class="col-md-12 rule-fragment">
                        <!-- Event-Channel image -->
                        <div class="row">
                            <img class="img img-circle img-responsive img-channel" src="' . $event_img . '" />
                        </div>

                        <!-- Event title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $event_title . '</h4>
                        </div>
                    </div>
                </div>  <!-- Info -->
            ';
        }
        return $events;
    }

    public function viewRulesHTML()
    {
        $user_manager = new UserManager();
        $options = ['sort' => ['title' => 1]];
        $rules = $this->manager->find('rules', [], $options);

        foreach ($rules as $rule) {
            if ($rule->description !== 'ADMIN RULE') {
                $this->getRuleHTML($rule, $user_manager->ruleImported($rule->title, $_SESSION['user']));
            }            
        }
        unset($user_manager);
    }

    public function viewRulesHTMLByUser($username, $kind) {
        $user_manager = new UserManager();
        $filter = ['username' => $username];
        $options = ['projection' => [$kind => 1]];
        $rules_title = $this->manager->find('users', $filter, $options)[0]->$kind;

        foreach ($rules_title as $rule_title) {
            $filter = ['title' => $rule_title];
            $rule = $this->manager->find('rules', $filter)[0];

            if ($rule->description !== 'ADMIN RULE') {
                $this->getRuleHTML($rule, $user_manager->ruleImported($rule->title, $username));
            }
        }
        unset($user_manager);
    }
}

?>