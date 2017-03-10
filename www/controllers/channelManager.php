<?php

require_once('DBHelper.php');
//require_once('./mongoconfig.php');

/**
* 
*/
class ChannelManager
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

    public function newChannel($title, $description, $nicename, $image, $events, $actions)
    {
        $channel = array(
            'title' => $title,
            'description' => $description,
            'nicename' => $nicename,
            'image' => $image,
            'events' => $events,
            'actions' => $actions
        );

        $table = $this->manager->insert('channels', $channel);
    }

    public function viewChannelsHTML()
    {
        $options = ['sort' => ['title' => 1]];
        $channels = $this->manager->find('channels', [], $options);

        foreach ($channels as $channel) {
            $image = $channel->image;
            $title = $channel->title;
            $description = $channel->description;

            echo '
            <!-- Channel item -->
            <div class="row channel-item">
                <!-- Channel img -->
                <div class="col-md-2 col-md-offset-1 channel-fragment">
                    <img class="img img-circle img-responsive img-channel" src="' . $image . '" />
                </div>

                <!-- Channel description -->
                <div class="col-md-6 channel-fragment">
                    <p><strong>' . $title . '</strong><br>' . $description . '.</p>
                </div>

                <!-- Channel bottons -->
                <div class="col-md-2 channel-fragment">
                    <button type="button" class="btn btn-info btn-rules-action">Edit</button>
                    <button type="button" class="btn btn-danger btn-rules-action">Delete</button>
                </div>
            </div>
            ';
        }
    }
}

?>