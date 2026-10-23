<?php // login.php
require __DIR__.'/lib.php';

session_open();
// A new id on login: no session fixation, and a new cache key for the private fragments (menu.php)
session_regenerate_id(true);
$_SESSION['logged'] = true;
$_SESSION['last_login'] = time();

// 303, not 301: a permanent redirect could be cached by the browser, and login.php never called again
header('Location: '.base_path().'/', true, 303);
