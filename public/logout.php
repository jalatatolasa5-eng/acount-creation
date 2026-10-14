<?php
require_once __DIR__ . '/../src/inc.php';
log_action('logout');
session_destroy();
redirect(url('login.php'));
