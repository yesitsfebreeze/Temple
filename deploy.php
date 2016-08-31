<?php

require_once "Pagen/Pagen/autoload.php";
require_once "Config.php";
$config = new \CustomPagenConfig();
$pagen = new \Pagen\Pagen($config,__DIR__);
var_dump(file_exists("/Users/hvlmnns/docker/temple/app/Temple/assets/css/all.css"));
$pagen->deploy();