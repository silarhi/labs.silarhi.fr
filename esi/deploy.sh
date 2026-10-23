#!/bin/bash
set -e

# Update to last file versions
git pull

# Download new image version
docker compose pull

docker compose up -d

# The pulled files are bind-mounted, but read only at start: OPcache does not check the PHP files for changes,
# and varnishd compiles the VCL once. up -d leaves both running when their image did not change.
docker compose restart esi-php varnish
