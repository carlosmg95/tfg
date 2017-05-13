<?php

session_start();

use EweTasker\Manager\RuleManager;
include_once('controllers/ruleManager.php');

$rule_manager = new RuleManager();

$rule_title = htmlspecialchars($_GET['ruleTitle']);
$rule_author = $rule_manager->getAuthor($rule_title);

if (isset($_SESSION['user']) && ($_SESSION['user'] === 'admin' || $_SESSION['user'] === $rule_author) && $rule_manager->deleteRule($rule_title)) {
    header('Location: ../rules.php');
} else {
    header('Location: ../index.php');
}