#!/bin/bash
set -e

IMAGE=$1

#Download new image version
docker pull ${IMAGE}

#Set maintenance mode to perform critical operations (database, upgrades, ...)
APP_MAINTENANCE=1 docker-compose up -d
#i.e: docker-compose exec app bin/console doctrine:migration:migrate -n
sleep 2; #for demo purpose

#back to prod
docker-compose up -d
