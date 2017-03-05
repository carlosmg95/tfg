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
                        <a href="#"><?php if(!isset($_SESSION['user'])) { ?>User<?php } else echo $_SESSION["user"] ?></a>
                    </li>
                    <li><a href="">Channels</a></li>
                    <li><a href="">Rules</a></li>
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">Sign In</div>
                        <div class="panel-body">
                            <form action="./controllers/signin.php" method="post">
                                <!-- Username -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Username:</label>
                                        <input type="text" class="form-control" placeholder="Username" id="username" required data-validation-required-message="Please enter your username." name="username">
                                    </div>
                                </div> <!-- field -->

                                <!-- Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" placeholder="Password" id="password1" required data-validation-required-message="Please enter your password." name="password">
                                    </div>
                                </div> <!-- field -->

                                <br>

                                <!-- Button -->
                                <div class="row">
                                    <div class="form-group col-xs-12">
                                        <button type="submit" class="btn btn-primary" onclick="encode()">Login</button>
                                    </div>
                                </div> <!-- Button -->
                            </form>
                        </div>
                    </div>
                </div> <!-- Login -->

                <!-- Sign Up -->
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">Sign Up</div>
                        <div class="panel-body">
                            <form action="./controllers/signupController.php" method="post">
                                <!-- Username -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Username:</label>
                                        <input type="text" class="form-control" placeholder="Username" id="username" required data-validation-required-message="Please enter your username." name="username">
                                    </div>
                                </div> <!-- field -->

                                <!-- Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" placeholder="Password" id="password2" required data-validation-required-message="Please enter your password." name="password">
                                    </div>
                                </div> <!-- field -->

                                <!-- Repeat Password -->
                                <div class="row control-group">
                                    <div class="form-group col-xs-12 floating-label-form-group controls">
                                        <label>Repeat password:</label>
                                        <input type="password" class="form-control" placeholder="Repeat Password" id="repeated_password2" required data-validation-required-message="Please enter again your password." name="repeated_password">
                                    </div>
                                </div> <!-- field -->

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
                </div> <!-- Sign Up -->
            </div>
        </div>
    <?php } else { ?>
        <form action="./controllers/logout.php">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
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
        function encode(){
            var input_pass = $("#password1");
            input_pass.val(sha1(input_pass.val()));
        }

        function confirmPassword() {
            var input_pass = $('#password2');
            var input_pass_repeated = $('#repeated_password2');

            input_pass.val(sha1(input_pass.val()));
            input_pass_repeated.val(sha1(input_pass_repeated.val()));
            
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