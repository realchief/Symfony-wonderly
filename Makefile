####
# Common commands
####
SUDO :=
RM := $(shell command -v rm 2> /dev/null)
NPM := $(shell command -v npm 2> /dev/null)

UID := $(shell id -u)

ifneq '$(UID)' '0'
SUDO := $(shell command -v sudo 2> /dev/null)
endif

####
# Insure that docker and docker compose command is exists.
####

DOCKER := $(shell command -v docker 2> /dev/null)
DOCKER_COMPOSE := $(shell command -v docker-compose 2> /dev/null)

ifndef DOCKER
$(error You should install 'docker' first)
endif

ifndef DOCKER_COMPOSE
$(error You should install 'docker-compose' first)
endif
DOCKER := $(SUDO) $(DOCKER)
DOCKER_COMPOSE := $(SUDO) $(DOCKER_COMPOSE)

####
# Configuration constants
####

APP_NAME := wonderly

CONTAINER_NGINX := $(APP_NAME)_nginx_1
CONTAINER_PHP := $(APP_NAME)_php_1
CONTAINER_MYSQL := $(APP_NAME)_mysql_1

####
# Task definitions.
####
.PHONY: all install start clean

all: start

install:
	$(DOCKER) exec $(CONTAINER_PHP) /bin/sh -c 'cd /code; composer install'

start:
	$(DOCKER_COMPOSE) --project-name $(APP_NAME) --file ./docker/docker-compose.development.yml up --build

clean:
	$(SUDO) $(RM) -rf ./docker/mysql

console:
	@$(DOCKER) exec -it $(CONTAINER_PHP) /bin/sh
