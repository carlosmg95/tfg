<?php
    session_start();
    use Ewetasker\Manager\AdministrationManager;

    include_once('controllers/administrationManager.php');

    $admin_manager = new AdministrationManager();
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
    <?php if(!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') { ?>
        <script type="text/javascript">
            const alert = confirm('You have to be an admin.');
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

    <!-- Main Content -->
    <div class="container">
        <!-- Row 1 -->
        <div class="row">
            <!-- Most-run Actions -->
            <div class="col-md-4 col-xs-12 most-runed-actions administration">
                <h2 style="text-align: center;">Most-run Actions:</h2>
                <div class="scroll">
                    <?php $admin_manager->getOrderedActionsHTML(); ?>
                </div>
            </div>  <!-- Most-run Actions -->

            <!-- Most-actived Users -->
            <div class="col-md-4 col-xs-12 most-actived-users administration">
                <h2 style="text-align: center;">Most-active Users:</h2>
                <div class="scroll">
                    <?php $admin_manager->getOrderedUsersHTML(); ?>
                </div>
            </div>  <!-- Most-actived Users -->

            <!-- Most-imported Rules -->
            <div class="col-md-4 col-xs-12 most-imported-rules administration">
                <h2 style="text-align: center;">Most-imported Rules:</h2>
                <div class="scroll">
                    <?php $admin_manager->getOrderedRulesHTML(); ?>
                </div>
            </div>  <!-- Most-imported Rules -->
        </div>  <!-- Row 1 -->
    </div>  <!-- Main Content -->
    
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