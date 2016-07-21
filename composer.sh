#!/usr/bin/env bash
#composer stuff

cd _deployer

composer dumpautoload -o
composer update
clear