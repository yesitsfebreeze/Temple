<?php


// check if we have the right php version
$version = phpversion();
$version = (int) substr(str_replace(".", "", $version), 0, 2);
if ($version < 56) {
    throw new Exception("Your Php Version is to low to use the Temple console, a minimum of 5.6.xx is required!");
}

// require the Temple autoloader
require_once(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "autoload.php");

use Temple\Engine\Exception\Exception as Exception;


// grab console arguments and convert them to usable ones
$arguments = $argv;
array_shift($arguments);
$name = array_shift($arguments);

try {
    $Console = new \Temple\Engine\Console\Console();

    return $Console->execute($name, $arguments);

} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}