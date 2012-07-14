#!/bin/bash

# magic from http://stackoverflow.com/questions/59895/can-a-bash-script-tell-what-directory-its-stored-in 
# to get the directory of the script
BASEDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# pull in submodules
# currently atahualpa-matts-mods, uot-helper-functions
git submodule init
git submodule update  --init --recursive


# initialize list of plugins for later use
# this is a quasi-two-dimensional array that
# stores the name and version number in 
# the format that will be used to grap the zipball 
# from wordpress.org
# really we only use it to find a tarball

TRANS="$BASEDIR/var-translator.php"
if [ -e "$TRANS" ]
then
    php $BASEDIR/var-translator.php
    source /tmp/uot-file
else 
    PLUGINS="all-in-one-event-calendar,1.2.5
custom-content-type-manager,0.9.6"
fi 



# fetch and unpack wordpress
# we can upgrade to wordpress 3.4.1 or latest if we want
# figure out a way to do it

# allow check for stored files while testing
STORAGE='/var/www/storage/'
if [ -e $STORAGE"latest.tar.gz" ] 
then
    tar  -xzvf  $STORAGE"latest.tar.gz" --strip=1 --show-transformed
else
    wget -nd http://wordpress.org/wordpress-3.4.tar.gz
    tar  -xzvf  wordpress-3.4.tar.gz --strip=1 --show-transformed
fi

# fetch and unpack plugins
cd $BASEDIR/wp-content/plugins

for plugin in $PLUGINS; do
    #plugin=`echo $plugin | sed 's/,/./g'`".zip"
    plugin=${plugin/,/.}".zip"
    echo $plugin
    if [ -e $STORAGE/$plugin ]
    then
       unzip $STORAGE/$plugin
    else
    #url="http://downloads.wordpress.org/plugin/"$plugin
        curl -O  http://downloads.wordpress.org/plugin/$plugin
        unzip $plugin 
        # add this back in when I'm done testing
        # rm $plugin
    fi
done


# next step: create wp-config.php
cp $BASEDIR/wp-config-sample.php $BASEDIR/wp-config.php

# then, magic from stackexchange
# http://stackoverflow.com/a/6233537/1220983

SALT=$(curl -L https://api.wordpress.org/secret-key/1.1/salt/)
STRING='put your unique phrase here'
printf '%s\n' "g/$STRING/d" a "$SALT" . w | ed -s $BASEDIR/wp-config.php

echo -e "define('WP_SITEURL', 'http://testwp.hackinghistory.ca/');\n" >> $BASEDIR/wp-config.php
echo -e "define('WP_HOME', 'http://testwp.hackinghistory.ca/');\n" >> $BASEDIR/wp-config.php


