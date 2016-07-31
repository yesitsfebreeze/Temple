<?php

namespace WorkingTitle;


$namespace = __NAMESPACE__;
$dir       = __DIR__ . DIRECTORY_SEPARATOR;

spl_autoload_register(function ($class) use ($namespace, $dir) {
    $class = substr($class, strlen($namespace . "\\"));
    $file  = $dir . "/" . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
    }
});
