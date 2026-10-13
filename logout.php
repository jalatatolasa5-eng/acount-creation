<?php
require 'inc.php';
if (!empty($_SESSION['uid'])) log_action('logout');
$_SESSION = [];
session_destroy();
header('Location: login.php');
