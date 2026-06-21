.PHONY: create-cert start test

ifneq (,$(wildcard .env))
include .env
export
endif

DOMAIN ?= $(PROJECT_NAME).test
CERT_DIR ?= docker/nginx/certs
HOSTS_FILE ?= /etc/hosts
HOSTS_BEGIN ?= \#\#\# BEGIN $(PROJECT_NAME) \#\#\#
HOSTS_END ?= \#\#\# END $(PROJECT_NAME) \#\#\#

create-cert::
	@mkdir -p $(CERT_DIR)
	@mkcert -install
	@mkcert -key-file $(CERT_DIR)/local.key -cert-file $(CERT_DIR)/local.crt $(DOMAIN) localhost 127.0.0.1 ::1
create-cert:: hosts

start:
	@docker compose down
	@docker compose up -d --build
	@echo "The domain is https://$(DOMAIN)"

test:
	@docker compose exec php vendor/bin/phpunit

hosts:
	@sudo sh -c 'tmp="$$(mktemp)"; \
		sed "/$$(printf "%s" "$(HOSTS_BEGIN)" | sed "s/[.[\*^$$()+?{|}/\\\\&]/\\\\&/g")/,/$$(printf "%s" "$(HOSTS_END)" | sed "s/[.[\*^$$()+?{|}/\\\\&]/\\\\&/g")/d" "$(HOSTS_FILE)" > "$$tmp"; \
		cat "$$tmp" > "$(HOSTS_FILE)"; \
		rm -f "$$tmp"; \
		{ \
			echo "$(HOSTS_BEGIN)"; \
			echo "127.0.0.1 $(DOMAIN) localhost"; \
			echo "$(HOSTS_END)"; \
		} >> "$(HOSTS_FILE)"'

webserver-shell:
	@docker compose exec webserver bash

php-shell:
	@docker compose exec php bash

database-shell:
	@docker compose exec database bash

tmp:
	@docker compose exec webserver bash -c "ls -lsa /var/www/html"






