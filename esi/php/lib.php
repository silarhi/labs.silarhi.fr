<?php // lib.php: helpers shared by the page and its fragments

declare(strict_types=1);

// The public path of the demo: Traefik strips /esi before Varnish and tells it in X-Forwarded-Prefix (Varnish
// copies the request headers to the ESI sub-requests). The links built from it work on /esi as on /esi/, and
// locally, without a prefix.
function base_path(): string
{
    $prefix = $_SERVER['HTTP_X_FORWARDED_PREFIX'] ?? '';

    return preg_match('#^(/[\w-]+)+$#', $prefix) ? $prefix : '';
}
