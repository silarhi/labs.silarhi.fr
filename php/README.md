## PHP Docker Image
Docker images for PHP 8.2 to 8.6 apps: Apache (Debian, with a Symfony variant), FrankenPHP (Debian or Alpine) and CI (Alpine). Legacy images for PHP 5.6 to 8.1 are still available but frozen (no longer rebuilt). The demos run `silarhi/php-apache:8.6` and `silarhi/php-apache:8.6-frankenphp-bookworm`. Both serve `app/index.php`, a summary page (PHP version, server, request, config, OPcache, extensions) instead of the full `phpinfo()` shipped with the images.

* Demo: https://labs.silarhi.fr/php
* Demo (404): https://labs.silarhi.fr/php/notfound
* Demo (FrankenPHP): https://labs.silarhi.fr/frankenphp
* Sources: https://github.com/silarhi/docker-php
* Docker image: https://hub.docker.com/r/silarhi/php-apache
* Blog post: https://blog.silarhi.fr/image-docker-php-apache-parfaite/
