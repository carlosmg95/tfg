<?php

namespace Ewetasker\Manager;

use Ewetasker\Manager\DBHelper;
use Ewetasker\Manager\ChannelManager;

include_once('DBHelper.php');
include_once('channelManager.php');
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
        $actions = $this->orderActions($channels_aux);
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

    private function orderActions($channels)
    {
        for ($i = 1; $i < sizeof($channels); $i++) { 
            for ($j = 0; $j  < sizeof($channels) - 1; $j++) { 
                if ($channels[$j]['n'] < $channels[$j + 1]['n']) {
                    $aux = $channels[$j];
                    $channels[$j] = $channels[$j + 1];
                    $channels[$j + 1] = $aux;
                }
            }
        }
        return $channels;
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
}