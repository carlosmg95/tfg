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

    <!-- Page Header -->
    <header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>ewetasker</h1>
                        <hr class="small">
                        <span class="subheading">An Intelligent Automation Platform Based On ECA (Event-Condition-Action) Rules</span>
                        <hr class="small">
                        <button class="btn btn-success" onclick="location.href='./eyetest.php'">Test EYE</button>
                        <button class="btn btn-success" onclick="location.href='./simulator'">Simulator</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-md-2 col-md-offset-2">
                <p class="fragment-title">If</p>
            </div>
            <div class="col-md-2 col-md-offset-1">
                <p class="fragment-title">Then</p>
            </div>
        </div>

        <!-- Rule Item -->
        <div class="row rule-item">
            <div class="col-md-1 col-md-offset-1 rule-fragment">
                <button type="button" class="btn btn-primary btn-activate">Active</button>
            </div>
            
            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/presence.png" />
            </div>

            <div class="col-md-1 rule-fragment">
                <img class="img img-responsive img-arrow" src="img/arrow.png" />
            </div>

            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/door.png" />
            </div>

            <div class="col-md-3 rule-fragment rule-info">
                <p>If I'm near the door then open it.</p>
                <p>Sergio</p>
                <p>GSI Lab</p>
                <p>11:23 12/12/2016</p>
            </div>

            <div class="col-md-2 rule-fragment">
                <button type="button" class="btn btn-info btn-rules-action">Edit</button>
                <button type="button" class="btn btn-danger btn-rules-action">Delete</button>
            </div>
        </div>  <!-- row -->

        <!-- Rule Item -->
        <div class="row rule-item">
            <div class="col-md-1 col-md-offset-1 rule-fragment">
                <button type="button" class="btn btn-primary btn-activate">Active</button>
            </div>
            
            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/door.png" />
            </div>

            <div class="col-md-1 rule-fragment">
                <img class="img img-responsive img-arrow" src="img/arrow.png" />
            </div>

            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/tv.png" />
            </div>

            <div class="col-md-3 rule-fragment rule-info">
                <p>If the door is opened, then switch on TV.</p>
                <p>Sergio</p>
                <p>GSI Lab</p>
                <p>11:56 12/01/2016</p>
            </div>

            <div class="col-md-2 rule-fragment">
                <button type="button" class="btn btn-info btn-rules-action">Edit</button>
                <button type="button" class="btn btn-danger btn-rules-action">Delete</button>
            </div>
        </div>  <!-- row -->

        <!-- Rule Item -->
        <div class="row rule-item">
            <div class="col-md-1 col-md-offset-1 rule-fragment">
                <button type="button" class="btn btn-primary btn-activate">Active</button>
            </div>
            
            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/tv.png" />
            </div>

            <div class="col-md-1 rule-fragment">
                <img class="img img-responsive img-arrow" src="img/arrow.png" />
            </div>

            <div class="col-md-2 rule-fragment">
                <img class="img img-circle img-responsive img-channel" src="img/NotificationModule.png" />
            </div>

            <div class="col-md-3 rule-fragment rule-info">
                <p>If the TV is on, then show a Notification.</p>
                <p>Sergio</p>
                <p>GSI Lab</p>
                <p>11:23 12/12/2016</p>
            </div>

            <div class="col-md-2 rule-fragment">
                <button type="button" class="btn btn-info btn-rules-action">Edit</button>
                <button type="button" class="btn btn-danger btn-rules-action">Delete</button>
            </div>
        </div>  <!-- row -->
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
</body>
</html>