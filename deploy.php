<?php

try {
    require_once "Pagen/Pagen/autoload.php";
    require_once "Config.php";
    $config = new \CustomPagenConfig();
    $pagen  = new \Pagen\Pagen($config, __DIR__);
    $pagen->deploy();
    echo "page generated...";
} catch (Exception $e) {
    echo $e->getMessage();
}