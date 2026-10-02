# SILARHI labs
This repo contains the Docker Compose files used to run the labs.silarhi.fr demos in production (services are routed by Traefik through container labels; Traefik itself is configured outside this repo) and hosts code described in blog posts.

## Symfony Docker CI
[![CircleCI](https://circleci.com/gh/silarhi/symfony-docker-ci.svg?style=svg)](https://circleci.com/gh/silarhi/symfony-docker-ci)

A POC of CI with Symfony, Docker & CircleCI.

* Demo: https://labs.silarhi.fr
* Sources (App): https://github.com/silarhi/symfony-docker-ci
* Sources (Deploy): https://github.com/silarhi/labs.silarhi.fr/blob/main/ci/deploy.sh
* Docker image: https://hub.docker.com/r/silarhi/symfony-docker-ci
* Blog post: https://blog.silarhi.fr/deploiement-continu-symfony-docker-circleci/

## HTTP Cache with ESI & Varnish
A POC of ESI (Edge Side Includes) fragments with Varnish, PHP & Docker.

* Demo: https://labs.silarhi.fr/esi/
* Sources: https://github.com/silarhi/labs.silarhi.fr/tree/main/esi
* Blog post: https://blog.silarhi.fr/varnish-fragment-esi-docker/

## PHP Docker Image
Docker images for PHP 8.1 to 8.5 apps: Apache (Debian, with a Symfony variant), FrankenPHP (Debian or Alpine) and CI (Alpine). Legacy images for PHP 5.6 to 8.0 are still available but frozen (no longer rebuilt). The demos run `silarhi/php-apache:8.5` and `silarhi/php-apache:8.5-frankenphp-bookworm`.

* Demo: https://labs.silarhi.fr/php
* Demo (404): https://labs.silarhi.fr/php/notfound
* Demo (FrankenPHP): https://labs.silarhi.fr/frankenphp
* Sources: https://github.com/silarhi/docker-php
* Docker image: https://hub.docker.com/r/silarhi/php-apache
* Blog post: https://blog.silarhi.fr/image-docker-php-apache-parfaite/
