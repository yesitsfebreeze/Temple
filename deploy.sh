#!/usr/bin/env bash

# removing compiling directories
rm -rf markdown
rm -rf source
rm -rf templates/cache
mkdir templates/cache

#composer stuff
composer dumpautoload -o
composer update

cp -rf templates/phpclass.twig vendor/evert/phpdoc-md/templates/class.twig

# make git directory
mkdir source
cp -rf .git source/.git
cp -rf .gitignore source/.gitignore

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
cd ../../Docs
php deploy.php
cd ../
# cleanup
rm -rf markdown
rm -rf source
#rm -rf vendor