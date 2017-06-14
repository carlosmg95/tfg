<?php
    session_start();
    use Ewetasker\Manager\RuleManager;
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

    <!-- Main Content -->
    <?php if(!isset($_SESSION['user'])) { ?>

        <!-- No session -->
        <div class="container">
            <div class="row">
                <!-- Login -->
                <div class="col-md-6">
                    <div class="panel <?php if (isset($_REQUEST['error']) && $_REQUEST['error'] === 'userIncorrect') { ?>panel-danger<?php } else { ?>panel-primary<?php } ?>">
                        <div class="panel-heading">
                            Sign In<?php if($_REQUEST && $_REQUEST['error'] === 'userIncorrect') { ?>
                                - Wrong username or password
                            <?php } ?>
                        </div>
                        <div class="panel-body">
                            <form action="./controllers/signinController.php" method="post">
                                <!-- Username -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Username:</label>
                                        <input type="text" class="form-control" placeholder="Username" id="username" required data-validation-required-message="Please enter your username." name="username">
                                    </div>
                                </div>  <!-- field -->

                                <!-- Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" placeholder="Password" id="password1" required data-validation-required-message="Please enter your password." name="password">
                                    </div>
                                </div>  <!-- field -->

                                <br>

                                <!-- Button -->
                                <div class="row">
                                    <div class="form-group col-xs-12">
                                        <button type="submit" class="btn btn-primary" onclick="encode()">Login</button>
                                    </div>
                                </div>  <!-- Button -->
                            </form>
                        </div>
                    </div>
                </div>  <!-- Login -->

                <!-- Sign Up -->
                <div class="col-md-6">
                    <div class="panel <?php if (isset($_REQUEST['error']) && $_REQUEST['error'] === 'userExists') { ?>panel-danger<?php } else { ?>panel-success<?php } ?>">
                        <div class="panel-heading">
                            Sign Up<?php if(isset($_REQUEST['error']) && $_REQUEST['error'] === 'userExists') { ?> - Username in use<?php } ?>
                        </div>
                        <div class="panel-body">
                            <form action="./controllers/signupController.php" method="post">
                                <!-- Username -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Username:</label>
                                        <input type="text" class="form-control" placeholder="Username" id="username" required data-validation-required-message="Please enter your username." name="username">
                                    </div>
                                </div>  <!-- field -->

                                <!-- Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" placeholder="Password" id="password2" required data-validation-required-message="Please enter your password." name="password">
                                    </div>
                                </div>  <!-- field -->

                                <!-- Repeat Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Repeat password:</label>
                                        <input type="password" class="form-control" placeholder="Repeat Password" id="repeated_password2" required data-validation-required-message="Please enter again your password." name="repeated_password">
                                    </div>
                                </div>  <!-- field -->

                                <br>

                                <!-- Button -->
                                <div class="row">
                                    <div class="form-group col-xs-12">
                                        <button type="submit" class="btn btn-primary" onclick="return confirmPassword()">Signup</button>
                                    </div>
                                </div> <!-- Button -->
                            </form>
                        </div>
                    </div>
                </div>  <!-- Sign Up -->
            </div>
        </div>  <!-- No session -->
    <?php } else { ?>
        <!-- logged user -->
        <div class="container">
            <div class="row">
                <h1>My rules:</h1>
            </div>

            <div class="row">
                <h3>Imported rules:</h3>
            </div>

            <!-- Header -->
            <div class="row">
                <div class="col-md-2 col-md-offset-2">
                    <p class="fragment-title">If</p>
                </div>

                <div class="col-md-2 col-md-offset-1">
                    <p class="fragment-title">Then</p>
                </div>
            </div>

            <?php

            include_once('./controllers/ruleManager.php');

            $rule_manager = new RuleManager();

            $rule_manager->viewRulesHTMLByUser($_SESSION['user'], 'imported_rules');

            ?>

            <hr>

            <div class="row">
                <h3>Created rules:</h3>
            </div>

            <!-- Header -->
            <div class="row">
                <div class="col-md-2 col-md-offset-2">
                    <p class="fragment-title">If</p>
                </div>

                <div class="col-md-2 col-md-offset-1">
                    <p class="fragment-title">Then</p>
                </div>
            </div>
            
            <?php $rule_manager->viewRulesHTMLByUser($_SESSION['user'], 'created_rules'); ?>

            <div class="row">
                <!-- Telegram button -->
                <div class="col-md-2 col-xs-2">
                    <button type="button" class="btn btn-info btn-telegram" onclick="location.href='./settelegramid.php'">
                        Set Telegram Id
                    </button>
                </div>  <!-- Button -->

                <!-- Twitter button -->
                <div class="col-md-2 col-xs-2">
                    <button type="button" class="btn btn-info btn-twitter" onclick="location.href='./twitterconnect.php'">
                        Connect with Twitter
                    </button>
                </div>  <!-- Button -->

                <!-- Logout button -->
                <div class="col-md-1 col-xs-1 col-xs-offset-5 col-md-offset-6">
                    <button type="button" class="btn btn-danger btn-logout" onclick="location.href='./controllers/logout.php'">
                        Logout
                    </button>
                </div>  <!-- Button -->
            </div>
        </div>  <!-- Session -->
    <?php } ?>
    
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

    <!-- SHA1 -->
    <script src="js/sha1.js"></script>
    <script>
        /*function encode(){
            let input_pass = $("#password1");
            if(input_pass.val().length !== 0) {
                input_pass.val(sha1(input_pass.val()));
            }
        }*/

        function confirmPassword() {
            let input_pass = $('#password2');
            let input_pass_repeated = $('#repeated_password2');

            if(input_pass.val().length !== 0 && input_pass_repeated.val().length !== 0) {
                //input_pass.val(sha1(input_pass.val()));
                //input_pass_repeated.val(sha1(input_pass_repeated.val()));
            }
            
            if (input_pass.val() === input_pass_repeated.val()) {
                return true;
            } else {
                input_pass.val("");
                input_pass_repeated.val("");
                alert("Passwords must be the same.");
                return false;
            }
        }
    </script>
</body>
</html>