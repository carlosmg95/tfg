<?php
    session_start();
    use Ewetasker\Manager\ChannelManager;
    use Ewetasker\Manager\RuleManager;
    include_once("./controllers/channelManager.php");
    include_once("./controllers/ruleManager.php");

    $channel_manager = new ChannelManager([]);
    $rule_manager = new RuleManager([]);
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

    <!-- jQuery CSS -->
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css">

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
            <div class="col-md-2 col-md-offset-5">
                <button class="btn btn-warning btn-initiation" onclick="deleteCookies()">
                    Initial Example
                </button>
            </div>
        </div>

        <!-- Test -->
        <div class="row">
            <div class="col-md-2 events-test">
                <h1>Events:</h1>
                <br>
                <!-- Accordion Item -->
                <div class="panel-group" id="accordion">
                    <?php
                    $channel_manager->getExampleHTML();
                    ?>
                </div>  <!-- Accordion -->
            </div>
            <div class="col-md-8">
                <h1>EYE in your browser</h1>
                <div class="eye">
                    <p>
                        Command to execute:
                        <code>
                            eye
                            <span class="data" id="input">eye/input.n3</span>
                            <span class="data">eye/rule.n3</span>
                            --query <span class="query">eye/query.n3</span> --nope
                        </code>
                    </p>
                </div>
            </div>
            <div class="col-md-2 col-xs-12 rules-test">
                <h1>Rules:</h1>
                <br>
                <!-- Rules Item -->
                <div class="panel-group">
                    <?php
                    $rule_manager->getRulesTest();
                    ?>
                </div>  <!-- Rules -->
            </div>
        </div>  <!-- Test -->
    </div>
    
    <hr>

    <!-- Footer -->
    <footer>
        <p class="copyright text-muted">&copy; 2017 gsi.dit.upm.es</p>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui-1.8.16.custom.min.js"></script>
  
    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- EYE -->
    <script src="js/eyeclient.js"></script>

    <script>
        $('.eye').eye(
            { path: 'http://eye.restdesc.org/' },
            <?php if (isset($_COOKIE['input'])) { ?> '<?php echo $_COOKIE['input']; ?>' <?php } else { ?> undefined <?php } ?>,
            <?php if (isset($_COOKIE['rule'])) { ?> '<?php echo $_COOKIE['rule']; ?>' <?php } else { ?> undefined <?php } ?>,
            <?php if (isset($_SESSION['user'])) { ?> '<?php echo $_SESSION['user']; ?>' <?php } else { ?> 'public' <?php } ?>
        );

        function input(prefix, example) {
            let cookie = getCookie('input');
            let linesCookie = cookie.trim() ? cookie.split('\\r\\n') : '';
            let linesPrefix = prefix ? prefix.split('\r\n') : '';
            let linesExample = example ? example.split('\r\n') : '';

            let prefixAux = '';
            let exampleAux = '';
            let input = '';

            for (let i in linesCookie) {
                if (linesCookie[i].match(/^@/))
                    input += linesCookie[i] + '\\r\\n';
            }
            for (let i in linesPrefix) {
                if (linesCookie.indexOf(linesPrefix[i]) < 0)
                    input += linesPrefix[i] + '\\r\\n';
            }
            input += '\\r\\n';

            for (let i in linesCookie) {
                if (!linesCookie[i].match(/^@/))
                    input += linesCookie[i] + '\\r\\n';
            }
            const channel = linesExample[0].split(' ')[0].split(':')[1];
            let re = new RegExp(channel);
            let newChannel;
            if (channel && input.match(re)) {
                let n = getCookie('nChannels') || 1;
                newChannel = channel + n;
                let d = new Date();
                d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
                let expires = 'expires=' + d.toUTCString();
                document.cookie = 'nChannels=' + ++n + ';' + expires + ';path=/';
            }
            for (let i in linesExample) {
                if (newChannel)
                    linesExample[i] = linesExample[i].replace(re, newChannel);
                input += linesExample[i] + '\\r\\n';
            }

            setCookie('input', input, 2);
            window.open('/eyetest.php', '_self');
        }

        function rule(ruleIn) {
            let cookie = getCookie('rule');
            let linesCookie = cookie.trim() ? cookie.split('\\r\\n') : '';
            let linesRule = ruleIn ? ruleIn.split('\r\n') : '';

            let prefix = [];
            let cookieRules = [];
            let newRules = [];

            let rule = '';

            for (let i in linesCookie) {
                if (linesCookie[i].match(/^@/))
                    prefix.push(linesCookie[i]);
                else
                    cookieRules.push(linesCookie[i]);
            }
            for (let i in linesRule) {
                if (prefix.indexOf(linesRule[i]) < 0 && linesRule[i].match(/^@/)){
                    prefix.push(linesRule[i]);}
                else if (!linesRule[i].match(/^@/) && linesRule[i].match(/^\{|^\=|\}/))
                    newRules.push(linesRule[i]);
                else if (!linesRule[i].match(/^@/) && !linesRule[i].match(/\{|\=|\}/))
                    newRules.push('\\t' + linesRule[i]);
            }

            for (let i in prefix)
                rule += prefix[i] + '\\r\\n'
            for (let i in cookieRules)
                rule += cookieRules[i] + '\\r\\n'
            for (let i in newRules)
                rule += newRules[i] + '\\r\\n'

            setCookie('rule', rule, 2);
            window.open('/eyetest.php', '_self');
        }

        function setCookie(cname, cvalue, exdays) {
            let lines = cvalue ? cvalue.split('\r\n') : '';
            let value = '';
            for (let i in lines) {
                if (lines[i] === '')
                    break;
                value += lines[i] + '\\r\\n';
            }
            while (value.match(/(\\r\\n){2,}/))
                value = value.replace(/(\\r\\n){2,}/, '\\r\\n');
            value = value.replace(/(\\r\\n)(?!@)/, '\\r\\n\\r\\n');
            while (value.match(/;/))
                value = value.replace(/;/, 'PUNTO_Y_COMA');
            let d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = 'expires=' + d.toUTCString();
            document.cookie = cname + '=' + value + ';' + expires + ';path=/';
        }

        function deleteCookies() {
            document.cookie = 'rule=;';
            document.cookie = 'input=;';
            document.cookie = 'nChannels=;';
            window.open('/eyetest.php', '_self');
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return '';
        }
    </script>
</body>
</html>