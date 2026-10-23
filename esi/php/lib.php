<?php // lib.php: helpers shared by the page and its fragments

declare(strict_types=1);

// Not PHPSESSID: labs.silarhi.fr also runs the Symfony CI demo, whose session cookie has the same name on /
const SESSION_NAME = 'ESISESSID';

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, \ENT_QUOTES);
}

// The public path of the demo: Traefik strips /esi before Varnish and tells it in X-Forwarded-Prefix (Varnish
// copies the request headers to the ESI sub-requests). The links built from it work on /esi as on /esi/, and
// locally, without a prefix.
function base_path(): string
{
    $prefix = $_SERVER['HTTP_X_FORWARDED_PREFIX'] ?? '';

    return preg_match('#^(/[\w-]+)+$#', $prefix) ? $prefix : '';
}

// Sends the TTL Varnish caches the response for (see vcl_backend_response), and returns it
function cache_for(int $ttl): int
{
    if ($ttl > 0) {
        header('X-Reverse-Proxy-TTL: '.$ttl);
    }

    return $ttl;
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

// Read only, and only when the visitor already has a session: an anonymous visitor gets no session (nor
// Set-Cookie) from the fragments, whose responses Varnish caches
function session_read(): array
{
    if (!isset($_COOKIE[SESSION_NAME])) {
        return [];
    }

    session_open();
    $session = $_SESSION;
    session_write_close();

    return $session;
}

/**
 * The frame around a fragment: its src, its cache scope and TTL, then when it was generated, with a bar that
 * empties until Varnish fetches it again (index.php animates it).
 *
 * @param 'public'|'private' $scope
 * @param bool               $include false for a block of the page itself, not an <esi:include>
 */
function fragment(string $src, string $scope, int $ttl, callable $body, bool $include = true): void
{
    $classes = 'fragment fragment-'.$scope.($ttl > 0 ? '' : ' fragment-uncached');
    ?>
    <section class="<?= $classes ?>">
        <header class="fragment-header">
            <code class="fragment-src"><?= $include ? '&lt;esi:include src="'.h($src).'" /&gt;' : h($src) ?></code>
            <span class="fragment-tags">
                <span class="fragment-tag"><span class="status-dot-square"></span><?= 'public' === $scope ? 'Public' : 'Privé' ?></span>
                <span class="fragment-tag"><?= $ttl > 0 ? 'TTL '.$ttl.'s' : 'Pas de cache' ?></span>
            </span>
        </header>
        <div class="fragment-body"><?php $body(); ?></div>
        <footer class="fragment-footer" data-generated-at="<?= time() ?>" data-ttl="<?= $ttl ?>">
            <span>Généré à <time datetime="<?= date('c') ?>"><?= date('H:i:s') ?></time></span>
            <span data-ttl-label></span>
            <?php if ($ttl > 0) { ?>
                <span class="fragment-ttl" aria-hidden="true"><span class="fragment-ttl-bar"></span></span>
            <?php } ?>
        </footer>
    </section>
    <?php
}
