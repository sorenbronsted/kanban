.PHONY:	clean test migrate generate coverage depend js package update-depend

SHELL=/bin/bash

all: depend migrate coverage package
	@echo "Up-to-date"

clean:
	bin/clean.sh

test: clean migrate
	bin/phpunit.phar --configuration test-conf.xml

migrate:
	bin/dbmigrate.sh $(VERSION)

# usage: make generate arg=<ClassName>
generate:
	bin/generate.sh $(arg)

coverage:
	bin/phpunit.phar --coverage-html doc/coverage --configuration test-conf.xml

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update

js:
	bin/build.sh

package: js
	bin/package.sh
