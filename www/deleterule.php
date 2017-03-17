<?php

session_start();
require_once('controllers/ruleManager.php');
$config = [];
$rule_manager = new RuleManager($config);

$rule_title = htmlspecialchars($_GET['ruleTitle']);
$rule_author = $rule_manager->getAuthor($rule_title);

if (isset($_SESSION['user']) && ($_SESSION['user'] === 'admin' || $_SESSION['user'] === $rule_author) && $rule_manager->deleteRule($rule_title)) {
    header('Location: ../rules.php');
} else {
    header('Location: ../index.php');
}

?>