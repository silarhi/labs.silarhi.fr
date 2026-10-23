<?php // logout.php
require __DIR__.'/lib.php';

if (isset($_COOKIE[SESSION_NAME])) {
    session_open();
    $_SESSION = [];
    session_destroy();

    // Same path and flags as the session cookie, or the browser keeps it
    $params = session_get_cookie_params();
    setcookie(SESSION_NAME, '', ['expires' => time() - 3600] + array_intersect_key($params, array_flip(['path', 'domain', 'secure', 'httponly', 'samesite'])));
}

header('Location: '.base_path().'/', true, 303);
