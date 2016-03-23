#!/usr/bin/env bash

cd _deployer

# removing compiling directories
rm -rf source
rm -rf assets/prod
rm -rf tempaltes/api/generated
rm -rf tempaltes/docs/generated
clear

# create needed folders
mkdir assets/prod
mkdir source
clear

#composer stuff
composer dumpautoload -o
composer update
clear

cp -rf ../.git source/.git
cp -rf ../.gitignore source/.gitignore
cp -rf ./_core/api_generator.php ./vendor/evert/phpdoc-md/src/Generator.php

# pull master to source
cd source
git checkout master
git subtree pull --prefix / origin master
clear

# remove the git folders to prevent bugs
rm -rf .git
rm -rf .gitignore

# dump the docs
cd ../vendor/bin
php phpdoc  -d ../../source -t ../../source/phpdocs --template="xml"
php phpdocmd ../../source/phpdocs/structure.xml


# deploy docs
php  ../../_core/deploy.php


#cp -rf _deployer/_core/api_generator.php _deployer/vendor/evert/phpdoc-md/src/Generator.php
#cd _deployer/vendor/bin
#php phpdocmd ../../markdown/xml/structure.xml ../../markdown/parsed