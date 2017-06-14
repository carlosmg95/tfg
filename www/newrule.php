<?php
    session_start();

    use Ewetasker\Manager\ChannelManager;
    use Ewetasker\Manager\RuleManager;
    include_once('./controllers/channelManager.php');
    include_once('./controllers/ruleManager.php');

    $channel_manager = new ChannelManager();
    $rule_manager = new RuleManager();
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
        <!-- Values -->
        <div class="row">
            <!-- Title -->
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label>Title:</label>
                    <input type="text" class="form-control" placeholder="Title" id="title" required data-validation-required-message="Please enter a title." name="title">
                </div>
            </div>  <!-- field -->

            <!-- Place -->
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label>Place:</label>
                    <?php foreach ($rule_manager->getPlaces() as $place) { ?>
                    <input type="radio" name="place" value="<?php echo $place ?>" id="<?php echo $place ?>"> <?php echo $place ?><br>
                    <?php } ?>
                    <div class="input-group">
                        <input type="radio" name="place">
                        <input type="text" placeholder=" New place" name="place" required data-validation-required-message="Please enter a place.">
                    </div>  <!-- input group -->
                </div>
            </div>  <!-- field -->

            <!-- Description -->
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label>Description:</label>
                    <input type="text" class="form-control" placeholder="Description" id="description" required data-validation-required-message="Please enter a description." name="description">
                </div>
            </div>  <!-- field -->
        </div>  <!-- Values -->

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
        </div>  <!-- Boxes -->

        <div class="row">
            <button type="button" onclick="submit()" class="btn btn-success" id="send" style="float: right;">Send</button>
        </div>

        <!-- Options dialog -->
        <div id="myModal" class="modal">
            <div id="action-options-dialog" class="modal-content"></div>
            <div id="event-options-dialog" class="modal-content"></div>
            <div id="parameter-action-dialog" class="modal-content"></div>
            <div id="parameter-event-dialog" class="modal-content"></div>
        </div>
    </div>

    <hr>

    <div class="container-fluid">
        <!-- Channels -->
        <div class="row">
        <?php

        $channel_manager->viewChannelsIconHTML();

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
            let parametersActions = new Array();
            let parametersEvents = new Array();

            <?php foreach ($channel_manager->getChannelsList() as $channel_title) { ?>
                actionsFunctions['<?php echo $channel_title?>'] = function() {
                    let fieldset = '' +
                    '<fieldset>' +
                        '<select name="action" id="action">' +
                    <?php foreach ($channel_manager->getActions($channel_title) as $action_title) { 
                        if ((bool) $channel_manager->actionHasParameter($channel_title, $action_title)) { ?>
                            '<option class="<?php echo implode(' ', $channel_manager->actionHasParameter($channel_title, $action_title)) ?>"><?php echo $action_title ?> [Need parameter]</option>' +
                        <?php } else { ?>
                            '<option><?php echo $action_title ?></option>' +
                        <?php }
                    } ?>
                        '</select>' +
                    '</fieldset>';
                    return fieldset;
                }
            <?php } ?>

            <?php foreach ($channel_manager->getChannelsList() as $channel_title) { ?>
                eventsFunctions['<?php echo $channel_title?>'] = function() {
                    let fieldset = '' +
                    '<fieldset>' +
                        '<select name="event" id="event">' +
                    <?php foreach ($channel_manager->getEvents($channel_title) as $event_title) {
                        if ((bool) $channel_manager->eventHasParameter($channel_title, $event_title)) { ?>
                            '<option class="<?php echo implode(' ', $channel_manager->eventHasParameter($channel_title, $event_title)) ?>"><?php echo $event_title ?> [Need parameter]</option>' +
                        <?php } else { ?>
                            '<option><?php echo $event_title ?></option>' +
                        <?php }
                    } ?>
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
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog('close');
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    let parameter = false;
                    const value = $('select#action').val().replace(/\[Need parameter\]$/, '').trim();
                    if ($('select#action').val().match(/\[Need parameter\]$/)) {
                        for (let i in $('select#action').children()) {
                            if ($('select#action').children()[i].innerHTML === $('select#action').val()) {
                                let parameterClass = $('select#action').children()[i].outerHTML;
                                parameterClass = parameterClass.replace(/<option class=\"/, '');
                                parameterClass = parameterClass.substring(0, parameterClass.indexOf('"'));
                                parameterClass = parameterClass.split(' ');
                                let fieldset = document.createElement('fieldset');
                                let legend = document.createElement('legend');
                                var title = document.createTextNode('Parameters:');
                                legend.appendChild(title);
                                fieldset.appendChild(legend);
                                for (let i in parameterClass) {
                                    let input = document.createElement('input');
                                    input.setAttribute('class', 'parameters-actions');
                                    input.setAttribute('placeholder', parameterClass[i]);
                                    fieldset.appendChild(input);
                                }
                                parameter = true;
                                $('#parameter-action-dialog').append(fieldset);
                                $('#parameter-action-dialog').dialog('open');
                                break;
                            }
                        }
                    }
                    actions.push(value);
                    if (!parameter) {
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
                }
            });

            $('#event-options-dialog').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog('close');
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    let parameter = false;
                    const value = $('select#event').val().replace(/\[Need parameter\]$/, '').trim();
                    if ($('select#event').val().match(/\[Need parameter\]$/)) {
                        for (let i in $('select#event').children()) {
                            if ($('select#event').children()[i].innerHTML === $('select#event').val()) {
                                let parameterClass = $('select#event').children()[i].outerHTML;
                                parameterClass = parameterClass.replace(/<option class=\"/, '');
                                parameterClass = parameterClass.substring(0, parameterClass.indexOf('"'));
                                parameterClass = parameterClass.split(' ');
                                let fieldset = document.createElement('fieldset');
                                let legend = document.createElement('legend');
                                var title = document.createTextNode('Parameters:');
                                legend.appendChild(title);
                                fieldset.appendChild(legend);
                                for (let i in parameterClass) {
                                    let input = document.createElement('input');
                                    input.setAttribute('class', 'parameters-events');
                                    input.setAttribute('placeholder', parameterClass[i]);
                                    fieldset.appendChild(input);
                                }
                                parameter = true;
                                $('#parameter-event-dialog').append(fieldset);
                                $('#parameter-event-dialog').dialog('open');
                                break;
                            }
                        }
                    }
                    events.push(value);
                    if (!parameter) {
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
                }
            });

            $('#parameter-action-dialog').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog('close');
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    parameter = [];
                    for(let i = 0; i < $('.parameters-actions').length; i++) {
                        parameter.push($('.parameters-actions')[i].value);
                    }
                    parametersActions.push(parameter);
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

            $('#parameter-event-dialog').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                show: {
                    effect: 'puff',
                    duration: 1000
                },
                buttons: {
                    'Save': function() {
                        $(this).dialog('close');
                    }
                },
                hide: {
                    effect: 'explode',
                    duration: 1000
                },
                close: function(event, ui) {
                    parameter = [];
                    for(let i = 0; i < $('.parameters-events').length; i++) {
                        parameter.push($('.parameters-events')[i].value);
                    }
                    parametersEvents.push(parameter);
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

            submit = function() {
                let actionChannels = new Array();
                let eventChannels = new Array();
                let place;

                for (let i in $('.event-box > img')) {
                    if(!$('.event-box > img')[i].id){
                        break;
                    }
                    eventChannels.push($('.event-box > img')[i].id);
                }
                for (let i in $('.action-box > img')) {
                    if(!$('.action-box > img')[i].id){
                        break;
                    }
                    actionChannels.push($('.action-box > img')[i].id);
                }

                let placeButton = $('input[name=place]');
                let newPlace;
                for (let i in placeButton) {                    
                    if (placeButton[i].checked) {
                        if (placeButton[i] === placeButton[placeButton.length - 2]) {
                            newPlace = prompt('Set sensorID of the place:','');;
                            place = placeButton[placeButton.length - 1].value;
                        } else {
                            newPlace = '';
                            place = placeButton[i].value;
                        }
                        break;
                    }
                }
                $.ajax({
                    type: 'POST',
                    url: './controllers/newRuleController.php',
                    data: {
                        'Rule-title' : $('input#title').val(),
                        'Rule-place' : place,
                        'New-place' : newPlace,
                        'Rule-description' : $('input#description').val(),
                        'Author' : '<?php echo $_SESSION['user'] ?>',
                        'Event-channels': eventChannels,
                        'Action-channels': actionChannels,
                        'Events' : events,
                        'Actions' : actions,
                        'Parameters-actions' : parametersActions,
                        'Parameters-events' : parametersEvents
                    },
                    success: function(output){
                        window.open('./rules.php', '_self');
                    }
                });
            }
        });
    </script>
</body>
</html>