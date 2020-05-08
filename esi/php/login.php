<?php // login.php

session_start();
$_SESSION['logged'] = true;
$_SESSION['last_login'] = time();

header('Location: ./', true, 301);
