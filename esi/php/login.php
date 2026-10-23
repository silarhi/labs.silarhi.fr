<?php // login.php
require __DIR__.'/lib.php';

session_open();
$_SESSION['logged'] = true;
$_SESSION['last_login'] = time();

header('Location: '.base_path().'/', true, 301);
