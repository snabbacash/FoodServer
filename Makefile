TOP=$(shell pwd)

include config.mk

fetch-remote-db:
	@ssh $(SSH_REMOTE) "mysqldump $(DB_REMOTE) | gzip -cf" | gunzip -c > dump.sql

import-db:
	@mysql $(DB_LOCAL) < dump.sql

sync-db: fetch-remote-db import-db

clean:
	@rm -f dump.sql

.PHONY: clean sync-db import-db fetch-remote-db
