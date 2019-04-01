#!/usr/bin/env bash

# Create `logger-internal` network (useful `docker system prune` is executed on jenkins)
if ! docker network ls | grep -q " logger-internal "; then docker network create logger-internal; fi;

# Build and bring up containers
cd ./docker
docker-compose up --build -d
cd ../

# Install packages
docker exec -i sourcetoad_logger_php /usr/local/bin/composer install

# test process
docker exec -i sourcetoad_logger_php ./vendor/bin/phpunit