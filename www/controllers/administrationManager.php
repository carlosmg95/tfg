<?php

namespace Ewetasker\Manager;

use Ewetasker\Manager\DBHelper;
use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\UserManager;
use Ewetasker\Manager\RuleManager;

include_once('DBHelper.php');
include_once('channelManager.php');
include_once('userManager.php');
include_once('ruleManager.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class AdministrationManager
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

    public function deleteRule($rule_title)
    {
        return $this->manager->remove('importedRules', 'title', $rule_title);
    }

    public function getOrderedActionsHTML()
    {
        $channel_manager = new ChannelManager([]);
        $channels = $this->manager->find('admin');
        $channels_aux = array();
        foreach ($channels as $channel) {
            foreach ($channel->actions as $action) {
                array_push($channels_aux, array(
                    'channel' => $channel->channel,
                    'action' => $action->title,
                    'n' => $action->n)
                );
            }
        }
        $actions = $this->order($channels_aux);
        $i = 1;
        foreach ($actions as $action) {
            $title = $channel_manager->getTitleByText($action['channel']);
            $channel = $channel_manager->getChannel($title);
            $img = $channel['image'];
            $channel_title = $channel['nicename'];
            $action_title = $action['action'];
            $n = $action['n'];
            echo '
            <!-- Action item -->
            <div class="row action-runed-item">
                <div class="col-md-2">
                    <h3>#' . $i . '</h3>
                </div>
                <div class="col-md-4">
                    <img class="img-channel img-circle" src="' . $img . '" />
                </div>
                <div class="col-md-5 col-md-offset-1 channel-info">
                    <p>' . $channel_title . '</p>
                    <p>' . $action_title . '</p>
                    <p>' . $n . ' times</p>
                </div>
            </div>  <!-- Action -->
            ';
            $i++;
        }
        unset($channel_manager);
    }

    public function getOrderedRulesHTML()
    {
        $channel_manager = new ChannelManager([]);
        $rule_manager = new RuleManager([]);
        $rules = $this->manager->find('importedRules');
        $rules_aux = array();
        foreach ($rules as $rule) {
            array_push($rules_aux, array('title' => $rule->title, 'n' => $rule->n));
        }
        $rules = $this->order($rules_aux);
        $i = 1;
        foreach ($rules as $rule) {
            $n = $rule['n'];
            $rule = $rule_manager->getRule($rule['title']);
            if ($n > 0 && $rule['description'] !== 'ADMIN RULE') {
                $title = $rule['title'];
                $events = '';
                $j = 0;
                foreach ($rule['event_channels'] as $event_channel) {
                    $event_img = $channel_manager->getChannel($event_channel)['image'];
                    $event_title = $rule['event_titles'][$j];
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
                    $j++;
                }
                $actions = '';
                $j = 0;
                foreach ($rule['action_channels'] as $action_channel) {
                    $action_img = $channel_manager->getChannel($action_channel)['image'];
                    $action_titles = $rule['action_titles'][$j];
                    $actions = $actions . '
                    <!-- Event info -->
                    <div class="row">
                        <div class="col-md-12 rule-fragment">
                            <!-- Event-Channel image -->
                            <div class="row">
                                <img class="img img-circle img-responsive img-channel" src="' . $action_img . '" />
                            </div>

                            <!-- Event title -->
                            <div class="row">
                                <h4 style="text-align:center;">' . $action_titles . '</h4>
                            </div>
                        </div>
                    </div>  <!-- Info -->
                    ';
                    $j++;
                }
                echo '
                <!-- Rule item -->
                <div class="row rule-imported-item">
                    <div class="col-md-1">
                        <h3>#' . $i . '</h3>
                    </div>

                    <div class="col-md-11">
                        <!-- Title -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4 style="text-align: center;">' . $title . '</h4>
                            </div>
                        </div>  <!-- Title -->

                        <br>

                        <!-- Rule -->
                        <div class="row">
                            <!-- Evens -->
                            <div class="col-md-5">
                                ' . $events . '
                            </div>  <!-- Events -->

                            <!-- Arrow -->
                            <div class="col-md-2">
                                <img class="img img-responsive img-arrow" src="img/arrow.png" />
                            </div>  <!-- Arrow -->

                            <!-- Actions -->
                            <div class="col-md-5">
                                ' . $actions . '
                            </div>  <!-- Actions -->
                        </div>  <!-- Rule -->

                        <div class="row">
                            <div class="col-md-12">
                                <p style="text-align: center;">' . $n . ' times imported</p>
                            </div>
                        </div>
                    </div>
                </div>  <!-- Rule -->
                ';
                $i++;
            }
        }
        unset($rule_manager);
    }

    public function getOrderedUsersHTML()
    {
        $user_manager = new UserManager([]);
        $usernames = $user_manager->getUsersList();
        $users = array();
        foreach ($usernames as $username) {
            $user = $user_manager->getUser($username);
            array_push($users, $user);
        }
        $users = $this->order($users);
        $i = 1;
        foreach ($users as $user) {
            if ($user['n'] > 0) {
                $username = $user['username'];
                $n = $user['n'];
                echo '
                <!-- User item -->
                <div class="row user-active-item">
                    <div class="col-md-2">
                        <h3>#' . $i . '</h3>
                    </div>
                    <div class="col-md-5 col-md-offset-1 user-info">
                        <p><h4>' . $username . ':</h4> ' . $n . ' times</p>
                    </div>
                </div>  <!-- User -->
                ';
                $i++;
            }
        }
    }

    public function importRule($rule_title)
    {
        $rule_manager = new RuleManager([]);        
        $admin_rules = $rule_manager->getAdminRulesList();
        if (in_array($rule_title, $admin_rules)) {
            return false;
        }
        $filter = ['title' => $rule_title];
        $cursor = $this->manager->find('importedRules', $filter);
        if (empty($cursor)) {
            $rule = array('title' => $rule_title, 'n' => 1);
            $this->manager->insert('importedRules', $rule);
        } else {
            $rule = array('title' => $rule_title, 'n' => ++$cursor[0]->n);
            $this->manager->update('importedRules', 'title', $rule_title, $rule);
        }
        unset($rule_manager);
        return true;
    }

    private function order($items)
    {
        for ($i = 1; $i < sizeof($items); $i++) { 
            for ($j = 0; $j  < sizeof($items) - 1; $j++) { 
                if ($items[$j]['n'] < $items[$j + 1]['n']) {
                    $aux = $items[$j];
                    $items[$j] = $items[$j + 1];
                    $items[$j + 1] = $aux;
                }
            }
        }
        return $items;
    }

    public function removeRule($rule_title)
    {
        $rule_manager = new RuleManager([]);        
        $admin_rules = $rule_manager->getAdminRulesList();
        if (in_array($rule_title, $admin_rules)) {
            return false;
        }
        $filter = ['title' => $rule_title];
        $cursor = $this->manager->find('importedRules', $filter);
        if (empty($cursor)) {
            return false;
        } else {
            $rule = array('title' => $rule_title, 'n' => --$cursor[0]->n);
            $this->manager->update('importedRules', 'title', $rule_title, $rule);
        }
        unset($rule_manager);
        return true;
    }

    public function runAction($channel_title, $action_title)
    {
        $filter = ['channel' => $channel_title];
        $cursor = $this->manager->find('admin', $filter);
        if (empty($cursor)) {
            $actions = array();
            $action = array('title' => $action_title, 'n' => 1);
            array_push($actions, $action);
            $value = array('channel' => $channel_title, 'actions' => $actions);
            $this->manager->insert('admin', $value);
            return true;
        } else {
            $actions = $cursor[0]->actions;
            $actions_array = array();
            foreach ($actions as $action) {
                if ($action->title === $action_title) {
                    $n = $action->n;
                    $action = array('title' => $action->title, 'n' => ++$n);
                    array_push($actions_array, $action);
                    $new = false;
                } else {
                    $action = array('title' => $action->title, 'n' => $action->n);
                    array_push($actions_array, $action);
                }
            }
            if (!isset($new)) {
                $action = array('title' => $action_title, 'n' => 1);
                array_push($actions_array, $action);
            }
            $value = array('channel' => $channel_title, 'actions' => $actions_array);
            $this->manager->update('admin', 'channel', $channel_title, $value);
            return true;
        }
    }

    public function userRuns($username)
    {
        $user_manager = new UserManager([]);
        $user = $user_manager->getUser($username);
        $user['n']++;
        unset($user_manager);
        return $this->manager->update('users', 'username', $username, $user);
    }
}