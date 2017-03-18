<?php

require_once('channelManager.php');
require_once('DBHelper.php');
require_once('userManager.php');
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

    public function createNewRule($rule_title, $rule_description, $rule_place, $author, $action_channel, $action_title, $event_channel, $event_title)
    {
        if ($this->ruleExists($rule_title)) {
            return false;
        }

        $rule = array(
            'title' => $rule_title,
            'description' => $rule_description,
            'place' => $rule_place,
            'author' => $author,
            'event_channel' => $event_channel,
            'event_title' => $event_title,
            'action_channel' => $action_channel,
            'action_title' => $action_title
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

    private function ruleExists($title)
    {
        $filter = ['title' => $title];
        $cursor = $this->manager->find('rules', $filter);
        return !empty($cursor);
    }

    public function viewRulesHTMLByUser($username, $kind) {
        $filter = ['username' => $username];
        $options = ['projection' => [$kind => 1]];
        $rules_title = $this->manager->find('users', $filter, $options)[0]->$kind;

        foreach ($rules_title as $rule_title) {
            $filter = ['title' => $rule_title];
            $rule = $this->manager->find('rules', $filter)[0];

            $event_channel = $this->channel_manager->getChannel($rule->event_channel);
            $action_channel = $this->channel_manager->getChannel($rule->action_channel);

            $event_img = $event_channel['image'];
            $event_title = $rule->event_title;
            $action_img = $action_channel['image'];
            $action_title = $rule->action_title;
            $title = $rule->title;
            $description = $rule->description;
            $author = $rule->author;
            $place = $rule->place;
            $date = date_format(new DateTime($rule->createdAt), 'H:m d/m/Y');
            $buttons = '';
            $removeButton = '';

            if (isset($_SESSION['user']) && ($_SESSION['user'] === 'admin' || $_SESSION['user'] === $author)) {
                $buttons = '
                <!-- Rule buttons -->
                <div class="col-md-2 rule-fragment">
                    <button type="button" class="btn btn-info btn-rules-action" onclick="window.location=\'./editrule.php?ruleTitle=' . $title . '\'">Edit</button>
                    <button type="button" class="btn btn-danger btn-rules-action" onclick="window.location=\'./deleterule.php?ruleTitle=' . $title . '\'">Delete</button>
                </div>';
            }

            if ($kind === "imported_rules") {
                $removeButton = '
                    <button type="button" class="btn btn-primary btn-activate" onclick="window.location=\'./removerule.php?ruleTitle=' . $title . '\'">
                        Remove
                    </button>';
            }

            echo '
                <!-- Rule Item -->
                <div class="row rule-item">
                    <!-- Rule title -->
                    <div class="col-md-12">
                        <h2 style="text-align:center;">' . $title . '</h2>
                    </div>  <!-- Title -->

                    <!-- Remove button -->
                    <div class="col-md-1 col-md-offset-1 rule-fragment">
                        ' . $removeButton . '
                    </div>  <!-- Remove -->

                    <?php } ?>
                    
                    <!-- Event info -->
                    <div class="col-md-2 rule-fragment">
                        <!-- Event-Channel image -->
                        <div class="row">
                            <img class="img img-circle img-responsive img-channel" src="' . $event_img . '" />
                        </div>

                        <!-- Event title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $event_title . '</h4>
                        </div>
                    </div>  <!-- Info -->

                    <div class="col-md-1 rule-fragment">
                        <img class="img img-responsive img-arrow" src="img/arrow.png" />
                    </div>

                    <!-- Action info -->
                    <div class="col-md-2 rule-fragment">
                        <!-- Action-channel image -->
                        <img class="img img-circle img-responsive img-channel" src="' . $action_img . '" />

                        <!-- Action title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $action_title . '</h4>
                        </div>
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
    }

    public function viewRulesHTML()
    {
        $options = ['sort' => ['title' => 1]];
        $rules = $this->manager->find('rules', [], $options);

        foreach ($rules as $rule) {
            $event_channel = $this->channel_manager->getChannel($rule->event_channel);
            $action_channel = $this->channel_manager->getChannel($rule->action_channel);

            $event_img = $event_channel['image'];
            $event_title = $rule->event_title;
            $action_img = $action_channel['image'];
            $action_title = $rule->action_title;
            $title = $rule->title;
            $description = $rule->description;
            $author = $rule->author;
            $place = $rule->place;
            $date = date_format(new DateTime($rule->createdAt), 'H:m d/m/Y');
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
                        <button type="button" class="btn btn-primary btn-activate" onclick="window.location=\'./importrule.php?ruleTitle=' . $title . '\'">
                            Import
                        </button>
                    </div>  <!-- Import -->
                    
                    <!-- Event info -->
                    <div class="col-md-2 rule-fragment">
                        <!-- Event-Channel image -->
                        <div class="row">
                            <img class="img img-circle img-responsive img-channel" src="' . $event_img . '" />
                        </div>

                        <!-- Event title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $event_title . '</h4>
                        </div>
                    </div>  <!-- Info -->

                    <div class="col-md-1 rule-fragment">
                        <img class="img img-responsive img-arrow" src="img/arrow.png" />
                    </div>

                    <!-- Action info -->
                    <div class="col-md-2 rule-fragment">
                        <!-- Action-channel image -->
                        <img class="img img-circle img-responsive img-channel" src="' . $action_img . '" />

                        <!-- Action title -->
                        <div class="row">
                            <h4 style="text-align:center;">' . $action_title . '</h4>
                        </div>
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
    }
}

?>