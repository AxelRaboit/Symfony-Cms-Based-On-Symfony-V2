#!/bin/bash
SHELL = /bin/bash # Use bash syntax

# ARGUMENTS - ENV
ifndef ENV
ENV = dev
else
ENV = $(ENV)
endif

ifeq ($(ENV), dev)
BIN_PHP = php
ENVIRONNEMENT = develop
else ifeq ($(ENV), staging)
BIN_PHP = php
ENVIRONNEMENT = staging
else ifeq ($(ENV), production)
BIN_PHP = php
ENVIRONNEMENT = production
else
$(error ENV must be dev, staging or production)
endif

# VARIABLES
SYMFONY_CONSOLE = $(BIN_PHP) bin/console

##--------------------------------------------
## APPLICATION
##--------------------------------------------

#update:
#	symfony console make:migration --no-interaction
#	symfony console doctrine:migrations:migrate --no-interaction
#	yarn encore dev

update: ## Update application
	- bin/$(ENVIRONNEMENT)/update.sh
	- make front-production-build
start: ## Start application
	symfony serve
stop: ## Stop application
	symfony server:stop
prepare-to-push: ## Prepare to push running all useful commands
	make front-dev-build
	make cache-clear
	make update
	make quality
fast-main: ## Fast Push Main
	make cache-clear
	make update
	make front-production-build
	make quality
	git add .
	git commit -m "Fast Push"
	git push origin main
fast-develop: ## Fast Push Develop
	make cache-clear
	make update
	make front-production-build
	make quality
	git add .
	git commit -m "Fast Push"
	git push origin develop

##--------------------------------------------
## TOOLS
##--------------------------------------------

front-dev-build: ## Build front dev
	yarn install && yarn encore dev

fronte-dev-watch: ## Watch front dev
	yarn && yarn dev --watch

front-production-build: ## Build front production
	yarn install && yarn encore production

##--------------------------------------------
## QUALITY
##--------------------------------------------

quality:
	make phpstan

phpstan:
	vendor/bin/phpstan analyse

##--------------------------------------------
## CACHE
##--------------------------------------------

cache-clear: ## Clear cache
	$(SYMFONY_CONSOLE) cache:clear --env=$(ENV)

##--------------------------------------------
## COMMANDS
##--------------------------------------------

command-init: ## Save system data in database
	$(SYMFONY_CONSOLE) console app:init