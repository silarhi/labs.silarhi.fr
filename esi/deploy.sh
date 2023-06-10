#!/bin/bash
set -e

# Update to last file versions
git pull

# Download new image version
docker compose pull

docker compose up -d
