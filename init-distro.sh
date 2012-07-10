#!/bin/bash
git submodule init
git submodule update
wget -nd http://wordpress.org/wordpress-3.4.tar.gz
tar  -xzvf  wordpress-3.4.tar.gz --strip=1 --show-transformed
