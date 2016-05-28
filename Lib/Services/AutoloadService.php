<?php

namespace Temple\Services;


/**
 * Class Autoloader
 * compatible to the psr-4 standard
 *
 * @package Temple
 */
class AutoloaderService
{

    /**
     * Autoloader constructor.
     * @param string $dir
     */
    public function __construct($dir)
    {
        $namespace = "Temple";

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