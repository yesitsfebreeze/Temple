#!/bin/bash

DIR=${PWD}

cd "$DIR/slate"
sh "./deploy.sh"

rm -R "$DIR/fonts"
rm -R "$DIR/images"
rm -R "$DIR/javascripts"
rm -R "$DIR/stylesheets"
rm -R "$DIR/index.html"

shopt -s dotglob
mv $DIR/slate/build/* $DIR