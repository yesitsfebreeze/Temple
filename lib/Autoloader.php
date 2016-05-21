<?php

namespace Caramel;


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
        $dir       = __DIR__;
        $namespace = "Caramel";

        spl_autoload_register(function ($class) use ($namespace, $dir) {
            $class = substr($class, strlen($namespace . "\\"));
            $file  = $dir . "/" . str_replace('\\', '/', $class) . '.php';

            if (file_exists($file)) {
                /** @noinspection PhpIncludeInspection */
                require $file;
            }

        });
    }
}