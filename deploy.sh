#!/usr/bin/env bash

cd _deployer

# removing compiling directories
rm -rf markdown
rm -rf source
rm -rf assets/prod
rm -rf cache
rm -rf tempaltes/_deployer/generated
rm -rf tempaltes/api/generated

# create needed folders
mkdir cache
mkdir assets/prod
mkdir source

#composer stuff
composer dumpautoload -o
composer update

cp -rf ../.git source/.git
cp -rf ../.gitignore source/.gitignore
cp -rf ./templates/api/phpclass.twig ./vendor/evert/phpdoc-md/templates/class.twig
cp -rf ./Deploy/Generator.php ./vendor/evert/phpdoc-md/src/Generator.php

# pull master to source
cd source
git checkout master
git subtree pull --prefix / origin master

# remove the git folders to prevent bugs
rm -rf .git
rm -rf .gitignore

# dump the docs
cd ../vendor/bin
php phpdoc  -d ../../source -t ../../markdown/xml --template="xml"
mkdir ../../markdown/parsed
php phpdocmd ../../markdown/xml/structure.xml ../../markdown/parsed


# deploy docs
php  ../../_core/deploy.php