<?php // logout.php

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), "deleted", time() - 3600, "/");
}

session_start();
session_destroy();

header('Location: ./', true, 301);
