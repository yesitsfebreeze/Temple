<?php

namespace Caramel;

/**
 * Class Cache
 * @package Caramel
 */
class Cache
{

    /**
     * an array with all template dependency files
     * @var array $dependencies
     */
    private $dependencies;

    /**
     * teh completely parsed template
     * @var string $content
     */
    private $content;

    /**
     * Cache constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config       = $config;
        $this->dependencies = array();
        $cache              = $this->createDirectory($this->config->get("cache/dir"));
        $this->config->set("cache/dir", $cache);
    }


    /**
     * @param $file
     * @return bool
     */
    public function isModified($file)
    {
        if ($this->config->get("nocache")) {
            return true;
        } else {
            $modified = false;
            $file     = $this->getCachePath($file);
            $file     = str_replace(".php", ".dependencies.php", $file);
            if (file_exists($file)) {
                $dependencies = unserialize(file_get_contents($file));
                foreach ($dependencies as $dependency => $time) {
                    $currentTime = filemtime($dependency);
                    if ($currentTime > $time) {
                        $modified = true;
                        continue;
                    }
                }
            } else {
                $modified = true;
            }

            return $modified;
        }
    }

    /**
     * @param $path
     * @return string
     */
    private function createDirectory($path)
    {
        $paths = explode("/", $path);
        $path  = "";
        foreach ($paths as $chunk) {
            if ($chunk != "") {
                $path .= '/' . $chunk;
                if (!is_dir($path) && ($path != $chunk)) {
                    mkdir($path, 0777);
                }
            }
        }

        return $path . '/';
    }

    /**
     * @param $file
     * @return bool
     */
    public function addDependency($file)
    {
        $this->dependencies[ $file ] = filemtime($file);

        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    public function removeDependency($file)
    {
        unset($this->dependencies[ $file ]);

        return true;
    }


    /**
     * @param $file
     * @param $content
     * @return string
     */
    public function save($file, $content)
    {
        if ($this->config->get("file_header")) {
            $content = "<!-- " . $this->config->get("file_header") . " -->\n" . $content;
        }

        $this->createDependencyFile($file);

        $file = $this->createFile($file);
        file_put_contents($file, $content);
        $this->content = $content;
        return $this->content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $file
     * @throws \Exception
     */
    private function createDependencyFile($file)
    {
        $dependencies = serialize($this->dependencies);
        $file         = $this->createFile(str_replace('.' . $this->config->get("extension"), "", $file) . ".dependencies");
        file_put_contents($file, $dependencies);
    }

    /**
     * @param $file
     * @return mixed|string
     * @throws \Exception
     */
    public function getCachePath($file)
    {
        # remove the template dir
        foreach ($this->config->getTemplateDir() as $templateDir) {
            $file = str_replace($templateDir, "", $file);
        }
        # add cache directory and escape the slashes with an underscore
        $cacheDir = $this->createDirectory($this->config->get("cache/dir"));
        $file     = $cacheDir . str_replace("/", '_', $file);
        # make sure we have a php extension
        $file = $this->createFileExtension($file);

        return $file;
    }

    /**
     * @param $file
     * @return mixed|string
     */
    private function createFile($file)
    {
        $file = $this->getCachePath($file);
        # create the file
        if (!file_exists($file)) touch($file);

        return $file;
    }

    /**
     * @param $file
     * @return mixed|string
     * @throws \Exception
     */
    private function createFileExtension($file)
    {
        $file             = str_replace("." . $this->config->get("extension"), ".php", $file);
        $currentExtension = array_reverse(explode(".", $file))[0];
        if ($currentExtension != "php") {
            $file = $file . ".php";
        }

        return $file;
    }

}