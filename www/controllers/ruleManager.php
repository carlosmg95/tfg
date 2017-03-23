<?php

namespace Ewetasker\Manager;

use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\DBHelper;
use Ewetasker\Manager\UserManager;

include_once('channelManager.php');
include_once('DBHelper.php');
include_once('userManager.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class RuleManager
{
    private $channel_manager;
    private $manager;
    private $user_manager;

    function __construct($config)
    {
        $this->channel_manager = new ChannelManager($config);
        $this->user_manager = new UserManager($config);
        $this->connect($config);
    }

    private function connect($config)
    {
        $this->manager = new DBHelper($config);
    }

    public function createNewRule($rule_title, $rule_description, $rule_place, $author, $action_channels, $action_titles, $event_channels, $event_titles, $rule)
    {
        if ($this->ruleExists($rule_title)) {
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

        $this->user_manager->insertRule($rule_title, $author);
        $this->manager->insert('rules', $rule);

        return true;
    }

    public function deleteRule($rule_title)
    {
        $users = $this->user_manager->getUsersList();
        foreach ($users as $username) {
            if ($username === $this->getAuthor($rule_title)) {
                $this->user_manager->deleteRule($rule_title, $username);
            }
            $this->user_manager->removeRule($rule_title, $username);
        }
        return $this->manager->remove('rules', 'title', $rule_title);
    }

    public function getAuthor($rule_title)
    {
        $filter = ['title' => $rule_title];
        $options = ['projection' => ['author' =>1]];
        $author = $this->manager->find('rules', $filter, $options)[0]->author;
        return $author;
    }

    private function getRuleHTML($rule, $imported)
    {
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
            array_push($action_channels, $this->channel_manager->getChannel($action_channel));
        }
        foreach ($event_channels_name as $event_channel) {
            array_push($event_channels, $this->channel_manager->getChannel($event_channel));
        }

        $actions = $this->viewEventsHTML($action_channels, $rule->action_titles);
        $events = $this->viewEventsHTML($event_channels, $rule->event_titles);
        $title = $rule->title;
        $description = $rule->description;
        $author = $rule->author;
        $place = $rule->place;
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
        echo '
            <!-- Rule Item -->
            <div class="row rule-item">
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
        $options = ['sort' => ['title' => 1]];
        $rules = $this->manager->find('rules', [], $options);

        foreach ($rules as $rule) {
            $this->getRuleHTML($rule, $this->user_manager->ruleImported($rule->title, $_SESSION['user']));
        }
    }

    public function viewRulesHTMLByUser($username, $kind) {
        $filter = ['username' => $username];
        $options = ['projection' => [$kind => 1]];
        $rules_title = $this->manager->find('users', $filter, $options)[0]->$kind;

        foreach ($rules_title as $rule_title) {
            $filter = ['title' => $rule_title];
            $rule = $this->manager->find('rules', $filter)[0];

            $this->getRuleHTML($rule, $this->user_manager->ruleImported($rule->title, $username));
        }
    }
}

?>