<?php
    session_start();
    use Ewetasker\Manager\RuleManager;
    include_once("./controllers/ruleManager.php");

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
    <?php if(!isset($_SESSION['user'])) { ?>
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
        <div class="row">
            <div class="col-md-2 col-md-offset-5">
                <button type="button" class="btn btn-warning btn-create-rule" onclick="location.href='./newrule.php'">
                    Create new rule
                </button>
            </div>
            <div class="col-md-2 col-md-offset-3">
                <select name="place" id="place">
                    <option value="all">All</option>
                <?php foreach ($rule_manager->getPlaces() as $place) { ?>
                    <option value="<?php echo $place ?>"><?php echo $place ?></option>
                <?php } ?>
                </select>
            </div>
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
        
        $rule_manager->viewRulesHTML();

        ?>

    <hr>

    <!-- Footer -->
    <footer>
        <p class="copyright text-muted">&copy; 2017 gsi.dit.upm.es</p>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('select#place').selectmenu({
                change: function(event, data) {
                    let place = data.item.value;
                    const rules = $('div.rule-item');
                    rules.css('display', 'block');
                    if (place !== 'all') {
                        place = place.replace(/\s+/, '');
                        const rulesPlace = $('div.' + place);
                        rules.css('display', 'none');
                        rulesPlace.css('display', 'block');
                    }
                }
            });
        })
    </script>

</body>
</html>