.PHONY:	dist clean test migrate generate install checkout coverage depend js package update-depend

SHELL=/bin/bash

all: checkout depend migrate clean coverage package 
	echo "Up-to-date"

dist:
	dch
#	bin/dist.sh

clean:
	rm -fr dist
	bin/clean.sh

test:
	phpunit test/php

migrate:
	bin/dbmigrate.sh $(VERSION)

#
# usage: make generate arg=<ClassName>
#
generate:
	bin/generate.sh $(arg)

install:
	bin/install.sh

checkout:
	git pull

coverage:
	phpunit --coverage-html doc/coverage test/php

depend:
	bin/depend.sh install

update-depend:
	bin/depend.sh update

js:
	dart2js -opublic/client/main.dart.js public/client/main.dart

package:
	bin/package.sh
