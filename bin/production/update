#!/bin/bash

ENVIRONMENT=production
ENVIRONMENT_SYMFONY=prod

PHP_BIN=php

# Rights
chmod -R 777 var

# Cache clear
$PHP_BIN bin/console cache:clear --env=$ENVIRONMENT_SYMFONY

# Update database
$PHP_BIN bin/console doctrine:schema:update --force --env=$ENVIRONMENT_SYMFONY --complete

# Init data
$PHP_BIN bin/console app:init --env=$ENVIRONMENT_SYMFONY

# Rights
chmod -R 777 var
ar