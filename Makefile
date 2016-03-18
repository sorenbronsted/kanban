.PHONY:	dist clean test migrate generate coverage depend js package update-depend

SHELL=/bin/bash

all: depend coverage package
	echo "Up-to-date"

clean:
	rm -fr dist
	bin/clean.sh

test: migrate clean
	bin/phpunit.phar test/php

migrate:
	bin/dbmigrate.sh $(VERSION)

#
# usage: make generate arg=<ClassName>
#
generate:
	bin/generate.sh $(arg)

coverage: migrate clean
	bin/phpunit.phar --coverage-html doc/coverage test/php

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update

js:
	dart2js -opublic/client/main.dart.js public/client/main.dart

package: js
	bin/package.sh
