<?php
    session_start()
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EWETasker</title>

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
        <!-- Boxes -->
        <div class="row new-rule">
            <!-- Event Box -->
            <div class="col-md-2 col-md-offset-3 col-xs-12 new-rule-box events">
                <h3 style="text-align: center;">If</h3>
                <div class="event-box droppable-event"></div>
            </div>  <!-- Event -->

            <!-- Arrow -->
            <div class="col-md-1 col-md-offset-1 col-xs-12 rule-fragment">
                <img class="img img-responsive img-arrow" src="img/arrow.png" />
            </div>  <!-- Arrow -->

            <!-- Action Box -->
            <div class="col-md-2 col-md-offset-1 col-xs-12 new-rule-box actions">
                <h3 style="text-align: center;">Then</h3>
                <div class="action-box droppable-action"></div>
            </div>  <!-- Action -->
        </div>

        <div class="row">
            <button type="button" onclick="submit()" class="btn btn-success" id="send" style="float: right;">Send</button>
        </div>

        <!-- Options dialog -->
        <div id="myModal" class="modal">
            <div id="action-options-dialog" class="modal-content"></div>
            <div id="event-options-dialog" class="modal-content"></div>
            <div id="rule-options-dialog" class="modal-content"></div>
        </div>
    </div>

    <hr>

    <div class="container-fluid">
        <!-- Channels -->
        <div class="row">
        <?php

        require_once("./controllers/channelManager.php");

        $channelManager = new channelManager([]);
        $channelManager->viewChannelsIconHTML();

        ?>
        </div>        
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <p class="copyright text-muted">&copy; 2017 gsi.dit.upm.es</p>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

    <script> 
        $(function() {
            let eventsFunctions = new Array();
            let actionsFunctions = new Array();

            let actions = new Array();
            let events = new Array();

            <?php foreach ($channelManager->getChannelsList() as $channel_title) { ?>
                actionsFunctions['<?php echo $channel_title?>'] = function() {
                    let fieldset = '' +
                    '<fieldset>' +
                        '<select name="action" id="action">' +
                    <?php foreach ($channelManager->getActions($channel_title) as $action_title) { ?>
                            '<option><?php echo $action_title ?></option>' +
                    <?php } ?>
                        '</select>' +
                    '</fieldset>';
                    return fieldset;
                }
            <?php } ?>

            <?php foreach ($channelManager->getChannelsList() as $channel_title) { ?>
                eventsFunctions['<?php echo $channel_title?>'] = function() {
                    let fieldset = '' +
                    '<fieldset>' +
                        '<select name="event" id="event">' +
                    <?php foreach ($channelManager->getEvents($channel_title) as $event_title) { ?>
                            '<option><?php echo $event_title ?></option>' +
                    <?php } ?>
                        '</select>' +
                    '</fieldset>';
                    return fieldset;
                }
            <?php } ?>
            
            $('.draggable').draggable({
                revert: 'invalid',
                helper: 'clone'
            });

            $('.droppable-action').droppable({
                accept: '.hasAction',
                drop: function(event, ui) {
                    $('#event-options-dialog').dialog('close');
                    $(this).append(ui.draggable.prop('outerHTML'));
                    $(this).droppable({ disabled: true });
                    let select = actionsFunctions[ui.draggable.prop('id')];
                    $('#action-options-dialog').html(select);
                    $('#action-options-dialog').attr('title', 'Actions');
                    $('#action-options-dialog').dialog('open');
                }
            });

            $('.droppable-event').droppable({
                accept: '.hasEvent',
                drop: function(event, ui) {
                    $('#action-options-dialog').dialog('close');
                    $(this).append(ui.draggable.prop('outerHTML'));
                    $(this).droppable({ disabled: true });
                    let select = eventsFunctions[ui.draggable.prop('id')];
                    $('#event-options-dialog').html(select);
                    $('#event-options-dialog').attr('title', 'Events');
                    $('#event-options-dialog').dialog('open');
                }
            });

            $('#action-options-dialog').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                height: 130,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog( "close" );
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    actions.push($('select#action').val());
                    $('.actions').append('<div class="action-box droppable-action new-droppable-action"></div>');
                    $('.new-droppable-action').droppable({
                        accept: '.hasAction',
                        drop: function(event, ui) {
                            $('#event-options-dialog').dialog('close');
                            $(this).append(ui.draggable.prop('outerHTML'));
                            $(this).droppable({ disabled: true });
                            let select = actionsFunctions[ui.draggable.prop('id')];
                            $('#action-options-dialog').html(select);
                            $('#action-options-dialog').attr('title', 'Actions');
                            $('#action-options-dialog').dialog('open');
                        }
                    });
                }
            });

            $('#event-options-dialog').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                height: 130,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog( "close" );
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    events.push($('select#event').val());
                    $('.events').append('<div class="event-box droppable-event new-droppable-event"></div>');
                    $('.new-droppable-event').droppable({
                        accept: '.hasEvent',
                        drop: function(event, ui) {
                            $('#action-options-dialog').dialog('close');
                            $(this).append(ui.draggable.prop('outerHTML'));
                            $(this).droppable({ disabled: true });
                            let select = eventsFunctions[ui.draggable.prop('id')];
                            $('#event-options-dialog').html(select);
                            $('#event-options-dialog').attr('title', 'Events');
                            $('#event-options-dialog').dialog('open');
                        }
                    });
                }
            });

            $('#rule-options-dialog').dialog({
                autoOpen: false,
                modal: true,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog( "close" );
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    let actionChannels = new Array();
                    let eventChannels = new Array();

                    for (var i in $('.event-box > img')) {
                        if(!$('.event-box > img')[i].id){
                            break;
                        }
                        eventChannels.push($('.event-box > img')[i].id);
                    }
                    for (var i in $('.action-box > img')) {
                        if(!$('.action-box > img')[i].id){
                            break;
                        }
                        actionChannels.push($('.action-box > img')[i].id);
                    }
                    $.post({
                        type: 'POST',
                        url: './controllers/newRuleController.php',
                        data: {
                            'Rule-title' : $('input#title').val(),
                            'Rule-place' : $('input#place').val(),
                            'Rule-description' : $('input#description').val(),
                            'Author' : '<?php echo $_SESSION['user'] ?>',
                            'Event-channels': eventChannels,
                            'Action-channels': actionChannels,
                            'Events' : events,
                            'Actions' : actions
                        },
                        success: function(output){
                            window.open('./rules.php', '_self');
                        }
                    });
                }
            });
        });


        function submit() {
            $('button#send').attr('onclick', '');
            $('#action-options-dialog').dialog('close');
            $('#event-options-dialog').dialog('close');
            $('#rule-options-dialog').append('' +
                '<!-- Title -->' +
                '<div class="row control-group">' +
                    '<div class="form-group col-xs-12 floating-label-form-group controls">' +
                        '<label>Title:</label>' +
                        '<input type="text" class="form-control" placeholder="Title" id="title" required data-validation-required-message="Please enter a title." name="title">' +
                    '</div>' +
                '</div>  <!-- field -->' +

                '<!-- Place -->' +
                '<div class="row control-group">' +
                    '<div class="form-group col-xs-12 floating-label-form-group controls">' +
                        '<label>Place:</label>' +
                        '<input type="text" class="form-control" placeholder="Place" id="place" required data-validation-required-message="Please enter a place." name="place">' +
                    '</div>' +
                '</div>  <!-- field -->' +

                '<!-- Description -->' +
                '<div class="row control-group">' +
                    '<div class="form-group col-xs-12 floating-label-form-group controls">' +
                        '<label>Description:</label>' +
                        '<input type="text" class="form-control" placeholder="Description" id="description" required data-validation-required-message="Please enter a description." name="description">' +
                    '</div>' +
                '</div>  <!-- field -->'
            );
            $('#rule-options-dialog').dialog('open');
        }
    </script>
</body>
</html>