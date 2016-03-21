#!/usr/bin/env bash

#composer stuff
composer dumpautoload -o
composer update

# removing compiling directories
rm -rf markdown
rm -rf source

mkdir source
cp -rf .git source/.git
cp -rf .gitignore source/.gitignore
cd source
git checkout master
git subtree pull --prefix / origin master
rm -rf .git
rm -rf .gitignore
cd ../vendor/bin
php phpdoc  -d ../../source -t ../../markdown/xml --template="xml"
php phpdocmd ../../markdown/xml/structure.xml ../../markdown
cd ../../Docs
php deploy.php

# cleanup
#rm -rf markdown
#rm -rf source