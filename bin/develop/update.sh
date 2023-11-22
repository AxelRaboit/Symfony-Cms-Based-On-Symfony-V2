#!/bin/bash

ENVIROMENT=develop
ENVIROMENT_SYMFONY=dev

PHP_BIN=php

# Rights
chmod -R 777 var

# Cache clear
$PHP_BIN bin/console cache:clear --env=$ENVIROMENT_SYMFONY

# Update database
$PHP_BIN bin/console doctrine:schema:update --force --env=$ENVIROMENT_SYMFONY --complete

# Init data
$PHP_BIN bin/console app:init --env=$ENVIROMENT_SYMFONY

# Rights
chmod -R 777 var