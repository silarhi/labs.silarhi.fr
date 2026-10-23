<?php // lib.php: helpers shared by the page and its fragments

declare(strict_types=1);

// Not PHPSESSID: labs.silarhi.fr also runs the Symfony CI demo, whose session cookie has the same name on /
const SESSION_NAME = 'ESISESSID';

// The public path of the demo: Traefik strips /esi before Varnish and tells it in X-Forwarded-Prefix (Varnish
// copies the request headers to the ESI sub-requests). The links built from it work on /esi as on /esi/, and
// locally, without a prefix.
function base_path(): string
{
    $prefix = $_SERVER['HTTP_X_FORWARDED_PREFIX'] ?? '';

    return preg_match('#^(/[\w-]+)+$#', $prefix) ? $prefix : '';
}

function session_open(): void
{
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'path' => base_path() ?: '/',
        'secure' => 'https' === ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
