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
     *
     * @param string $dir
     * @param string $namespace
     */
    public function __construct($dir, $namespace)
    {
        $this->dir       = $dir;
        $this->namespace = $namespace;
        $this->load();
    }


    /**
     * loads the classes
     */
    public function load()
    {

        $namespace = $this->namespace;
        $dir  = $this->dir;
        spl_autoload_register(function ($class) use ($namespace,$dir) {
            $class = substr($class, strlen($namespace . "\\"));
            $file  = __DIR__ . "/" . $dir . "/" . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) require $file;
        });
    }
}