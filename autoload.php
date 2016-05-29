<?php


namespace Temple;


use Temple\Exceptions\ExceptionHandler;

$namespace = __NAMESPACE__;
$dir       = __DIR__ . "/Lib";

spl_autoload_register(function ($class) use ($namespace, $dir) {
    $class = substr($class, strlen($namespace . "\\"));
    $file  = $dir . "/" . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
    }

});

new ExceptionHandler();

require_once __DIR__ . "/Engine.php";
