<?php

namespace Caramel\Services;


/**
 * Class Autoloader
 * compatible to the psr-4 standard
 *
 * @package Caramel
 */
class Autoloader
{

    /**
     * Autoloader constructor.
     * s
     */
    public function __construct()
    {
        $dir       = "../";
        $namespace = "Caramel";

        spl_autoload_register(function ($class) use ($namespace, $dir) {
            $class = substr($class, strlen($namespace . "\\"));
            $file  = __DIR__ . "/" . $dir . "/" . str_replace('\\', '/', $class) . '.php';

            if (file_exists($file)) {
                /** @noinspection PhpIncludeInspection */
                require $file;
            }

        });
    }
}