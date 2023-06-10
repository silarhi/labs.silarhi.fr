# SILARHI labs
This repo contains all docker-compose + Traefik configuration used in production and hosts code described in blog posts.

## Symfony Docker CI
[![CircleCI](https://circleci.com/gh/silarhi/symfony-docker-ci.svg?style=svg)](https://circleci.com/gh/silarhi/symfony-docker-ci)

A POC of CI with Symfony, Docker & CircleCI

* Demo: https://labs.silarhi.fr
* Sources (App): https://github.com/silarhi/symfony-docker-ci
* Sources (Deploy): https://github.com/silarhi/labs.silarhi.fr/blob/master/ci/deploy.sh
* Docker image: https://hub.docker.com/r/silarhi/symfony-docker-ci
* Blog post: https://blog.silarhi.fr/deploiement-continu-symfony-docker-circleci/

## HTTP Cache with ESI & Varnish
A POC of ESI (Edge Side Includes) fragments with Varnish, PHP & Docker

* Demo: https://labs.silarhi.fr/esi/
* Sources: https://github.com/silarhi/labs.silarhi.fr/tree/master/esi
* Blog post: https://blog.silarhi.fr/varnish-fragment-esi-docker/

## PHP Docker Image
A Docker image for PHP apps based on Debian. Works with Apache and PHP from 5.6 to 8.2 and provide a Symfony variant.

* Demo: https://labs.silarhi.fr/php
* Demo (404): https://labs.silarhi.fr/php/notfound
* Sources: https://github.com/silarhi/docker-php
* Docker image: https://hub.docker.com/r/silarhi/php-apache
* Blog post: https://blog.silarhi.fr/image-docker-php-apache-parfaite/
