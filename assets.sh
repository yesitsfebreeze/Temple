#!/usr/bin/env bash

sudo chmod -R 0777 *

composer install

# deploy docs
cp -rf _deployer/_core/api_generator.php _deployer/vendor/evert/phpdoc-md/src/Generator.php
cd _deployer/vendor/bin/
php phpdocmd ../../source/phpdocs/structure.xml

cd ../../
php  _core/deploy.php
