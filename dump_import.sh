#!/bin/bash
echo "# Import var/backups/pimcore.sql dump into container database pimcore"
cat var/backups/pimcore.sql | docker exec -i pimskel-docker-db-1 mysql -u pimcore -ppimcore pimcore
echo "# Import done!"