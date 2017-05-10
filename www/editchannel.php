<?php
    session_start()
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EWETasker</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="./img/logo.ico" />

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <?php if (!isset($_SESSION['user'])) { ?>
        <script type="text/javascript">
            const alert = confirm('You have to be logged to access this page.');
            if(alert) {
                window.open("./user.php", '_self');
            } else {
                window.open("./index.php", '_self');
            }
        </script>
    <?php } elseif ($_SESSION['user'] !== 'admin') { ?>
        <script type="text/javascript">window.open("./index.php", '_self');</script>
    <?php } ?>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top my-nav">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button><a class="navbar-brand" href="index.php">Home</a>
            </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="./admin.php">
                            <?php if(isset($_SESSION['user']) && $_SESSION['user'] === 'admin') { ?>
                            Administration
                            <?php } ?>
                        </a>
                    </li>
                    <li>
                        <a href="./user.php"><?php if(!isset($_SESSION['user'])) { ?>User<?php } else echo $_SESSION["user"] ?></a>
                    </li>
                    <li><a href="./channels.php">Channels</a></li>
                    <li><a href="./rules.php">Rules</a></li>
                    <li><a href="">FAQ</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <hr><hr><hr>

    <!-- Main content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel <?php if (isset($_REQUEST['error'])) { ?>panel-danger<?php } else { ?>panel-info<?php } ?>">
                    <div class="panel-heading">
                        Edit channel<?php if(isset($_REQUEST['error']) && $_REQUEST['error'] === 'neitherActionNorEvent') { ?>
                            -  You must add an event or action
                        <?php } ?>
                    </div>

                    <div class="panel-body">
                        <form action="./controllers/editChannelController.php" method="post" enctype="multipart/form-data">
                            <input id="channelTitle" name="title" hidden>

                            <!-- Title -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Title:</label>
                                    <p id="title" style="font-size: 20px;"></p>
                                </div>
                            </div>  <!-- field -->

                            <!-- Description -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Description:</label>
                                    <input type="text" class="form-control" placeholder="Description" id="description" required data-validation-required-message="Please enter a description." name="description">
                                </div>
                            </div>  <!-- field -->

                            <!-- Nice name -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Nicename:</label>
                                    <input type="text" class="form-control" placeholder="Nicename" id="nicename" required data-validation-required-message="Please enter a nicename." name="nicename">
                                </div>
                            </div>  <!-- field -->

                            <br>

                            <!-- Add buttons -->
                            <div class="row">
                                <div class="col-md-3 col-md-offset-8">
                                    <button type="button" onclick="addEvent()" class="btn btn-success btn-add">Add event</button>
                                    <button type="button" onclick="addAction()" class="btn btn-success btn-add">Add action</button>
                                </div>
                            </div>

                            <!-- Events -->
                            <div class="row" id="events-list">
                                
                            </div>  <!-- Events -->

                            <!-- Actions -->
                            <div class="row" id="actions-list">
                                
                            </div>  <!-- Actions -->

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-md-1 col-md-offset-10">
                                    <button type="submit" class="btn btn-warning">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <p class="copyright text-muted">&copy; 2017 gsi.dit.upm.es</p>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

    <script type="text/javascript">
        nEvents = 0;
        nActions = 0;

        function addAction(title, rule, prefix) {
            let actionsList = $("#actions-list");
            nAction = ++nActions;
            actionsList.append(
                "<!-- Action item -->" +
                "<div class='col-md-6 action-item' id='action" + nAction + "'>" +
                    "<button type='button' class='btn btn-danger btn-remove' onclick='removeAction(" + nAction + ")'>" +
                        "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span>" +
                    "</button>" +

                    "<h2>Action</h2>" +

                    "<!-- Action title -->" +
                    "<div class='row action-fragment'>" +
                        "<div class='col-md-3'><strong>Title</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<input type='text' name='action-title" + nAction + "' id='action-title" + nAction + "' placeholder='Turn on' required data-validation-required-message='Please enter a title for the action.' class='form-control' value='" + title + "'>" +
                        "</div>" +
                    "</div>  <!-- title -->" +

                    "<!-- Action rule -->" +
                    "<div class='row action-fragment'>" +
                        "<div class='col-md-3'><strong>Rule</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<textarea placeholder='?b :knows ?a' rows='4' name='action-rule" + nAction + "' required data-validation-required-message='Please enter a rule for the action.' class='form-control'>" + rule + "</textarea>" +
                        "</div>" +
                    "</div>  <!-- Rule -->" +

                    "<!-- Action prefix -->" +
                    "<div class='row action-fragment'>" +
                        "<div class='col-md-3'><strong>Prefix</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<textarea placeholder='@prefix : <ppl#>.' class='form-control' rows='4' name='action-prefix" + nAction + "' required data-validation-required-message='Please enter prefixes for the action.'>" + prefix + "</textarea>" +
                        "</div>" +
                    "</div>  <!-- Prefix -->" +
                "</div>  <!-- Item -->"
            );
        }

        function addEvent(title, rule, prefix, example) {
            let eventsList = $("#events-list");
            nEvent = ++nEvents;
            eventsList.append(
                "<!-- Event item -->" +
                "<div class='col-md-6 event-item' id='event" + nEvent + "'>" +
                    "<button type='button' class='btn btn-danger btn-remove' onclick='removeEvent(" + nEvent + ")'>" +
                        "<span class='glyphicon glyphicon-remove' aria-hidden='true'></span>" +
                    "</button>" +

                    "<h2>Event</h2>" +

                    "<!-- Event title -->" +
                    "<div class='row event-fragment'>" +
                        "<div class='col-md-3'><strong>Title</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<input type='text' name='event-title" + nEvent + "' id='event-title" + nEvent + "' placeholder='New tweet' required data-validation-required-message='Please enter a title for the event.' class='form-control' value='" + title + "'>" +
                        "</div>" +
                    "</div>  <!-- title -->" +

                    "<!-- Event rule -->" +
                    "<div class='row event-fragment'>" +
                        "<div class='col-md-3'><strong>Rule</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<textarea placeholder='?a :knows ?b.\n?a!:age math:lessThan #PARAM_1#' rows='4' name='event-rule" + nEvent + "' required data-validation-required-message='Please enter a rule for the event.' class='form-control'>" + rule + "</textarea>" +
                        "</div>" +
                    "</div>  <!-- Rule -->" +

                    "<!-- Event prefix -->" +
                    "<div class='row event-fragment'>" +
                        "<div class='col-md-3'><strong>Prefix</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<textarea placeholder='@prefix : <ppl#>. @prefix math: <http://www.w3.org/2000/10/swap/math#>.' class='form-control' rows='4' name='event-prefix" + nEvent + "' required data-validation-required-message='Please enter prefixes for the event.'>" + prefix + "</textarea>" +
                        "</div>" +
                    "</div>  <!-- Prefix -->" +

                    "<!-- Event example -->" +
                    "<div class='row event-fragment'>" +
                        "<div class='col-md-3'><strong>Example</strong></div>" +
                        "<div class='col-md-9'>" +
                            "<textarea placeholder='ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance.\newe-presence:PresenceSensor ewe:sensorID #sensorID#.\newe-presence:PresenceSensor ewe:distance #distance#.' class='form-control' rows='4' name='event-example" + nEvent + "'>" + example + "</textarea>" +
                        "</div>" +
                    "</div>  <!-- Example -->" +
                "</div>  <!-- Item -->"
            );
        }

        function removeAction(nAction) {
            const id = "#action" + nAction;
            $(id).remove();
        }

        function removeEvent(nEvent) {
            const id = "#event" + nEvent;
            $(id).remove();
        }

        <?php

        use Ewetasker\Manager\ChannelManager;
        include_once("./controllers/channelManager.php");

        $channelManager = new ChannelManager([]);
        $channel = $channelManager->getChannel(htmlspecialchars($_REQUEST['channelTitle']));

        if (isset($channel['events'])) {
            foreach ($channel['events'] as $value) { 
                $rule = preg_split('/[\r\n]+/', $value['rule']);
                $prefix = preg_split('/[\r\n]+/', $value['prefix']);
                $example = preg_split('/[\r\n]+/', $value['example']);

                $str_rule = '';
                foreach ($rule as $value_rule) {
                    $str_rule = $str_rule . $value_rule . '\r\n';
                }

                $str_prefix = '';
                foreach ($prefix as $value_prefix) {
                    $str_prefix = $str_prefix . $value_prefix . '\r\n';
                } 

                $str_example = '';
                foreach ($example as $value_example) {
                    $str_example = $str_example . $value_example . '\r\n';
                } ?>

                addEvent(
                    '<?php echo $value['title'] ?>',
                    '<?php echo $str_rule ?>',
                    '<?php echo $str_prefix ?>',
                    '<?php echo $str_example ?>'
                );
            <?php }
        }

        if (isset($channel['actions'])) {
            foreach ($channel['actions'] as $value) {
                $rule = preg_split('/[\r\n]+/', $value['rule']);
                $prefix = preg_split('/[\r\n]+/', $value['prefix']);

                $str_rule = '';
                foreach ($rule as $value_rule) {
                    $str_rule = $str_rule . $value_rule . '\r\n';
                }

                $str_prefix = '';
                foreach ($prefix as $value_prefix) {
                    $str_prefix = $str_prefix . $value_prefix . '\r\n';
                } ?>

                addAction(
                    '<?php echo $value['title'] ?>',
                    '<?php echo $str_rule ?>',
                    '<?php echo $str_prefix ?>'
                );
            <?php }
        } ?>

        $("input#channelTitle").val("<?php echo htmlspecialchars_decode($channel['title']) ?>");
        $("p#title").html("<?php echo htmlspecialchars_decode($channel['title']) ?>");
        $("input#description").val("<?php echo htmlspecialchars_decode($channel['description']) ?>");
        $("input#nicename").val("<?php echo htmlspecialchars_decode($channel['nicename']) ?>");
    </script>
</body>
</html>