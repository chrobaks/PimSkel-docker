#!/bin/bash

#docker cp ./var/backups/pimcore.sql pimskel-docker-db-1:application
#
#docker exec pimskel-docker-db-1 chmod 0775 /application/pimcore.sql

cat var/backups/pimcore.sql | docker exec -i pimskel-docker-db-1 mysql -u pimcore -ppimcore pimcore

#docker exec -it pimskel-docker-db-1 bash
#mysql -u pimcore -ppimcore pimcore < application/pimcore.sql