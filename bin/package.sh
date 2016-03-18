#!/bin/sh

dpkg-buildpackage -b
mv ../kanban_* /srv/pkg/
