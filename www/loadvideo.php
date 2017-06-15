<?php
    session_start();
    use Ewetasker\Manager\RuleManager;
    include_once('./controllers/ruleManager.php');
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
        <div class="row">
            <!-- Info video -->
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">Info video</div>
                    <div class="panel-body">
                        <form action="./controllers/loadVideo.php" method="post">
                            <!-- Key -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Key:</label>
                                    <input type="text" class="form-control" placeholder="Key" id="key" required data-validation-required-message="Please enter the key." name="key">
                                </div>
                            </div>  <!-- field -->

                            <!-- URL -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>URL:</label>
                                    <input type="text" class="form-control" placeholder="URL" id="url" required data-validation-required-message="Please enter the URL." name="url">
                                </div>
                            </div>  <!-- field -->

                            <!-- Format -->
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Format:</label>
                                    <input type="text" class="form-control" placeholder="Format" id="format" required data-validation-required-message="Please enter the format." name="format">
                                </div>
                            </div>  <!-- field -->

                            <!-- place -->
                            <div class="row control-group" style="margin-left: 2px;">
                                <label>Place:</label>
                                <br>
                                <?php foreach ($rule_manager->getPlaces() as $place) { ?>
                                <input type="radio" name="place" value="<?php echo $place ?>" id="<?php echo $place ?>"> <?php echo $place ?><br>
                                <?php } ?>
                            </div>  <!-- field -->

                            <br>

                            <!-- Button -->
                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <button type="submit" class="btn btn-primary">Load</button>
                                </div>
                            </div>  <!-- Button -->
                        </form>
                    </div>
                </div>
            </div>  <!-- Login -->
        </div>  <!-- End row -->

        <!-- Row -->
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <ul>
                    <li><strong>Key:</strong> It is the parameter that you have to use in the Chromecast channel.</li>
                    <li><strong>URL:</strong> It is the video's URL.<br>http://&lt;server-path&gt;/&lt;video-name&gt;.&lt;format&gt;</li>
                    <li><strong>Format:</strong> It is the video's format. For example, <i>mp4.</i></li>
                    <li><strong>Place:</strong> The place where the video can be played. If you don't set this field, the video will be able to play on all the current smart places.</li>
                </ul>
            </div>
        </div>  <!-- End row -->
    </div>
    <hr>

    <!-- Footer -->
    <footer>
        <p class="copyright text-muted">&copy; 2017 gsi.dit.upm.es</p>
    </footer>
</body>
</html>