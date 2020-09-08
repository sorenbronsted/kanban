#!/bin/sh

dpkg-buildpackage -b
name=`head -1 debian/changelog | cut -d ' ' -f1`
mv ../${name}_* /srv/pkg/
