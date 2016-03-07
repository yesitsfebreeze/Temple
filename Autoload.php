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
        spl_autoload_register(function ($class) {
            $class = substr($class, strlen($this->namespace . "\\"));
            $file  = __DIR__ . "/" . $this->dir . "/" . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) require $file;
        });
    }
}