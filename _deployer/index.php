<?php
$env = "development";
foreach ($_SERVER["argv"] as $arg) {
    if (strpos($arg, "APP_ENV") !== false) {
        $arg = str_replace("APP_ENV=", "", $arg);
        $env = $arg;
    }
}

echo $env . " environment.";
include "Deployer.php";
$deployer = new Deployer($env, "/Temple");
$deployer->deploy();