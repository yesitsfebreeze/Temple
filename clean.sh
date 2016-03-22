#!/usr/bin/env bash

rm -rf index.html

cd docs

# removing compiling directories
rm -rf markdown
rm -rf source
rm -rf templates/cache
rm -rf vendor
rm -rf templates/generated
rm -rf composer.lock
rm -rf assets/prod