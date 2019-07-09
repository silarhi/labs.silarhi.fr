# Silarhi labs
Contains some Traefik configuration used in production and hosts code described in blog posts.

## CI
[![CircleCI](https://circleci.com/gh/silarhi/app-ci.svg?style=svg)](https://circleci.com/gh/silarhi/app-ci)

A POC of CI with Symfony, Docker & CircleCI

* Demo: https://labs.silarhi.fr
* Sources (App): https://github.com/silarhi/app-ci
* Sources (Deploy): ./ci/deploy.sh
* Docker image: https://hub.docker.com/r/silarhi/app-ci

## ESI
A POC of ESI (Edge Side Includes) fragments with Varnish, PHP & Docker

* Demo: https://labs.silarhi.fr/esi
* Sources: ./esi/
* Blog post: https://blog.silarhi.fr/reduisez-les-temps-de-reponse-de-votre-site-par-100/

## PHP
A Docker image for PHP apps. Works with Apache and PHP 5.6, 7.1, 7.2 and provide a Symfony variant.

* Demo: https://labs.silarhi.fr/php
* Demo (404): https://labs.silarhi.fr/php/notfound
* Sources: https://github.com/silarhi/docker-php
* Docker image: https://hub.docker.com/r/silarhi/php-apache
* Blog post: https://blog.silarhi.fr/image-docker-php-apache-parfaite/
