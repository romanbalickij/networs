#!/bin/sh

DB_USERNAME=root
DB_PASSWORD="7k#&2-Dkf8wGSgp"
CONTAINER_NAME=thefans
DB_DATABASE=thefans

docker exec -i db-$CONTAINER_NAME mysql -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < mariadb/thefans.sql
