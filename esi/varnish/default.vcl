# /etc/varnish/default.vcl
vcl 4.0;

import std;
import directors;

backend default {
    .host = "app"; # Changez par 127.0.0.1 si vous n'utilisez pas Docker
    .port = "80";  # Le port d'écoute de votre serveur Apache / Nginx
}

# Appelé au début de chaque requête
sub vcl_recv {
    # Pas de mise en cache pour les méthodes type POST / DELETE
    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }

    # On supprime les cookies sur les pages publiques (cf vcl_hash)
    if(! req.url ~ "^/(login|logout|menu|user)\.php") {
        unset req.http.Cookie;
    }

    # On supprime tous les autres cookies que PHPSESSID pour les pages privées
    if (req.http.Cookie) {
        set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
        set req.http.Cookie = regsuball(req.http.Cookie, ";(PHPSESSID)=", "; \1=");
        set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
        set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");
        set req.http.Cookie = regsuball(req.http.Cookie, "^;\s*", "");

        if (req.http.Cookie ~ "^\s*$") {
            unset req.http.Cookie;
        }
    }

    return (hash);
}

# Appelé pour calculer un hash de la requête
sub vcl_hash {
    hash_data(req.url);

    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
    }

    if (req.http.Cookie) {
        hash_data(req.http.Cookie);
    }
}

# Appelé si le hash a été trouvé (= page en cache)
sub vcl_hit {
    if (obj.ttl >= 0s) {
        return (deliver);
    }
    
    return (miss);
}

# Appelé au retour de la réponse par le backend
sub vcl_backend_response {
    # L'entête est envoyée par index.php
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
    }

    # Notre fameuse entête custom
    if (beresp.http.X-Reverse-Proxy-TTL) {
        set beresp.ttl = std.duration(beresp.http.X-Reverse-Proxy-TTL + "s", 0s);
        unset beresp.http.X-Reverse-Proxy-TTL;
    }
    
    return (deliver);
}

# Appelé à la fin de chaque réponse (en cache ou non)
sub vcl_deliver {
    if (obj.hits > 0) {
        set resp.http.X-Cache = "HIT";
    } else {
        set resp.http.X-Cache = "MISS";
    }

    set resp.http.X-Cache-Hits = obj.hits;
    
    return (deliver);
}