#!/usr/bin/env bash

rm -rf index.html
rm -rf api.html

cd _deployer

# removing compiling directories
rm -rf markdown
rm -rf source
rm -rf cache
rm -rf vendor
rm -rf templates/api/generated
rm -rf templates/docs/generated
rm -rf composer.lock
rm -rf assets/prod