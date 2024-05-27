#!make

SHELL:=/usr/bin/env sh
ROOT_DIR:="$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))"

.EXPORT_ALL_VARIABLES:
.ONESHELL:
.DEFAULT: help
.PHONY: help
ifndef VERBOSE
.SILENT:
endif

ifneq (,$(wildcard './.env'))
-include .env;
endif

APP_CONTAINER:=app

-include dev/make/php.mk

login:
	env;
	$(info [+] Make: Log in to Docker container ${APP_CONTAINER}.)
	docker compose run --entrypoint=/bin/sh "${APP_CONTAINER}";

mac:
	brew bundle install

.test-env:
	bash -c "[[ -f ./.env ]] || ${MAKE} .create-env";

.crafting:
	@echo "\033[92mCrafting excellence...\033[0m"

.direnv:
	direnv allow;

setup: ENV_LOCAL = 'dev'
setup: .direnv mac init

init: .init example
test:  .test
quality: .test-env .quality
craft: .crafting .craft
