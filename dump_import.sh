#!/bin/bash

docker cp ./var/backups/pimcore.sql pimskel-docker-db-1:application

docker exec pimskel-docker-db-1 mysql -upimcore -ppimcore pimcore < /application/pimcore.sql