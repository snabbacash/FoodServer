TOP ?= $(shell pwd)
PHPUNIT ?= $(TOP)/bin/phpunit.phar
TESTS ?= src/protected/tests/unit
TEST_BOOTSTRAP ?= src/protected/tests/bootstrap.php
GIT_GLOBALS="GIT_DIR=~/foodserver.git GIT_WORKING_TREE=~/foodserver"

include config.mk

fetch-remote-db:
	@ssh $(SSH_REMOTE) "mysqldump $(DB_REMOTE) | gzip -cf" | gunzip -c > dump.sql

import-db:
	@mysql $(DB_LOCAL) < dump.sql

sync-db: fetch-remote-db import-db

test: phpunit

phpunit: $(TESTS)
	# phpunit complains about not having a pear package that isn't really
	# used.
	@$(PHPUNIT) --bootstrap $(TEST_BOOTSTRAP) $^ 2>/dev/null

update-submodules:
	@if [ "$$USER" == "cash" ]; then \
		cd ~/foodserver; \
		$(GIT_GLOBALS) git submodule init; \
		$(GIT_GLOBALS) git submodule update; \
		cd -; \
	fi

clean:
	@rm -f dump.sql

.PHONY: clean sync-db import-db fetch-remote-db
