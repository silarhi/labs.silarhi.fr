# Silarhi labs
Contains some Traefik configuration used in production and hosts code described in blog posts.

## CI
A POC of Symfony CI with Docker & CircleCI

* Demo: https://labs.sainthillier.fr
* Sources (App): https://github.com/silarhi/app-ci
* Sources (Deploy): ./ci/deploy.sh
* Docker image: https://hub.docker.com/r/silarhi/app-ci

## ESI
A POC of ESI fragments with Varnish & Plain PHP on Docker

* Demo: https://labs.sainthillier.fr/esi
* Sources: ./esi/
* Blog post: https://blog.sainthillier.fr/reduisez-les-temps-de-reponse-de-votre-site-par-100/

## PHP
A Docker image for PHP apps. Works with Apache and PHP 5.6,7.1,7.2 and with a Symfony variant.

* Demo: https://labs.sainthillier.fr/php
* Demo (404): https://labs.sainthillier.fr/php/notfound
* Sources: https://github.com/silarhi/docker-php
* Docker image: https://hub.docker.com/r/silarhi/php-apache
* Blog post: https://blog.sainthillier.fr/image-docker-php-apache-parfaite/
