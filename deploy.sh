#!/usr/bin/env bash

composer dumpautoload -o
mkdir source
git add source
git subtree pull master origin
git subtree push --prefix source origin master