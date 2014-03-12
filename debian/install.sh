#!/bin/sh -e
#set -x

if [ $# -ne 1 ]
then
  echo "Wrong number of arguments"
  echo "Usage $0 <destdir>"
  exit 1
fi

appdir="var/www/kanban"
dest=$1

#
# Copy files
dirs="application database public readme.txt ruckusing.conf.php vendor"
for dir in $dirs
do
  rsync -a --exclude=".git*" $dir $dest/$appdir
done

#
# Application config
cp conf/kanban.ini $dest/etc/ufds/

#
# Apache config
cp conf/kanban.conf $dest/etc/apache2/sites-available/
