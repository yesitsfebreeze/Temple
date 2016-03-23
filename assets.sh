#!/usr/bin/env bash

# deploy docs
cd _deployer/vendor/bin/
php phpdocmd ../../source/phpdocs/structure.xml

cd ../../
php  _core/deploy.php
