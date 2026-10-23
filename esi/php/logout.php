<?php // logout.php
require __DIR__.'/lib.php';

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), "deleted", time() - 3600, "/");
}

session_start();
session_destroy();

header('Location: '.base_path().'/', true, 301);
