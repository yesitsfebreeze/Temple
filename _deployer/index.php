<?php


$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$env = "development";

if (isset($_SERVER["argv"])) {
    foreach ($_SERVER["argv"] as $arg) {
        if (strpos($arg, "APP_ENV") !== false) {
            $arg = str_replace("APP_ENV=", "", $arg);
            $env = $arg;
        }
    }
}

include "Deployer.php";
$deployer = new Deployer($env, "/Temple");
$deployer->deploy();

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

exec("sudo apachectl restart");

