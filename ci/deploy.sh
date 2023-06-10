#!/bin/bash
set -e

#Download new image version
docker compose pull

#Set maintenance mode to perform critical operations (database, upgrades, ...)
APP_MAINTENANCE=1 docker compose up -d
#i.e: docker-compose exec -T app bin/console doctrine:migration:migrate -n
sleep 10; #for demo purpose

#back to prod
docker compose up -d
