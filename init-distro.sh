#!/bin/bash

# magic from http://stackoverflow.com/questions/59895/can-a-bash-script-tell-what-directory-its-stored-in 
# to get the directory of the script
BASEDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# pull in submodules
# currently twentytwelve, uot-helper-functions
git submodule init
git submodule update  --init --recursive

# initialize list of plugins for later use
# this is a quasi-two-dimensional array that
# stores the name and version number in 
# the format that will be used to grap the zipball 
# from wordpress.org
# really we only use it to find a tarball
# but in install.php that list is fed to wordpress itself.
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
# currently fetching latest
# May want to revert to this
# earlier version if things start breaking later on

# allow check for stored files during development
# later we'll get rid of these I guess
STORAGE='/var/www/storage/'
if [ !  -e $STORAGE ]
then
    echo "can't find storage"
    sleep 1
    STORAGE=$PWD/
fi

echo "looking for "$STORAGE"latest.tar.gz"
# if [ -e $STORAGE"latest.tar.gz" ] 
# then
#     tar  -xzvf  $STORAGE"latest.tar.gz" --strip=1 --show-transformed
# else
    wget -nd -P $STORAGE  http://wordpress.org/latest.tar.gz
    tar  -xzvf  $STORAGE/latest.tar.gz --strip=1 --show-transformed
# fi

# fetch and unpack plugins
cd $BASEDIR/wp-content/plugins
echo "gonna do these plugins: $PLUGINS"
for plugin in $PLUGINS; do
    #plugin=`echo $plugin | sed 's/,/./g'`".zip"
    plugin=${plugin/,/.}".zip"
    plugin=${plugin/../.}
    echo $plugin
    if [ -e $STORAGE/$plugin ]
    then
       unzip -o $STORAGE/$plugin
    else
    #url="http://downloads.wordpress.org/plugin/"$plugin
        wget -nd -P $STORAGE  http://downloads.wordpress.org/plugin/$plugin
        unzip $STORAGE/$plugin 
        # add this back in when I'm done testing
        # rm $plugin
    fi
done

echo "done with plugins"
sleep 1.5

# now get the themes
echo "fetching and unpacking themes: $THEMES"
cd $BASEDIR/wp-content/themes
for theme in $THEMES; do
    #theme=`echo $theme | sed 's/,/./g'`".zip"
    theme=${theme/,/.}".zip"
    theme=${theme/../.}
    echo "trying to install "$theme
    sleep 0
    if [ -e $STORAGE/$theme ]
    then
       unzip $STORAGE/$theme
    else
    #url="http://downloads.wordpress.org/theme/"$theme
        wget -nd -P $STORAGE  http://wordpress.org/extend/themes/download/$theme
        unzip $STORAGE/$theme 
        # add this back in when I'm done testing
        # rm $theme
    fi
done
echo "done with themes"

# now create wp-config.php
cp $BASEDIR/wp-config-sample.php $BASEDIR/wp-config.php

# then, magic from stackexchange
# http://stackoverflow.com/a/6233537/1220983
# use ed to edit directly
SALT=$(curl -L https://api.wordpress.org/secret-key/1.1/salt/)
STRING='put your unique phrase here'
printf '%s\n' "g/$STRING/d" a "$SALT" . w | ed -s $BASEDIR/wp-config.php

