#!/bin/bash
docker compose stop
# shellcheck disable=SC2046
docker rm $(docker ps -aq)
# shellcheck disable=SC2046
docker rmi $(docker images -q)