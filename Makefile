TOP ?= $(shell pwd)
PHPUNIT ?= $(TOP)/bin/phpunit.phar
TESTS ?= src/protected/tests/unit

include config.mk

fetch-remote-db:
	@ssh $(SSH_REMOTE) "mysqldump $(DB_REMOTE) | gzip -cf" | gunzip -c > dump.sql

import-db:
	@mysql $(DB_LOCAL) < dump.sql

sync-db: fetch-remote-db import-db

test: phpunit

phpunit: $(TESTS)
	@$(PHPUNIT) $^

clean:
	@rm -f dump.sql

.PHONY: clean sync-db import-db fetch-remote-db
