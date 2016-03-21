#!/usr/bin/env bash

composer dumpautoload -o
rm -rf source
mkdir source
cp -rf .git source/.git
cp -rf .gitignore source/.gitignore
cd source
git subtree add --prefix origin master
#git add source