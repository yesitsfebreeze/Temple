<?php

namespace Caramel;


/**
 * Class Cache
 *
 * @package Caramel
 */
class Cache
{
    /** @var Config $config */
    private $config;

    /** @var string $cacheDir */
    private $cacheDir;

    /** @var Directories $dirs */
    private $dirs;

    /** @var string $cacheFile */
    private $cacheFile = "__cache.php";

    /** @var Helpers $helpers */
    private $helpers;


    /**
     * Cache constructor.
     *
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->helpers  = $caramel->helpers;
        $this->config   = $caramel->config();
        $this->dirs     = $this->config->directories();
        $this->cacheDir = $this->dirs->getCacheDir();
    }


    /**
     * saves a file to the cache
     *
     * @param $file
     * @param $content
     * @return string $file
     */
    public function save($file, $content)
    {
        $this->setTime($file);
        $file = $this->createFile($file);
        file_put_contents($file, $content);

        return $file;
    }


    /**
     * returns if a file is modified
     *
     * @param $file
     * @return bool
     */
    public function modified($file)
    {
        $modified = false;

        $cache        = $this->getCache();
        $dependencies = $cache["dependencies"][ $file ];
        if (!$dependencies) {
            $modified = true;
        } else {
            $times = $cache["times"];
            foreach ($dependencies as $dependency) {
                $templates = $this->helpers->templates($dependency);
                foreach ($templates as $template) {
                    $cacheTime   = $times[ $dependency ][ md5($template) ];
                    $currentTime = filemtime($template);
                    if ($cacheTime != $currentTime) {
                        $modified = true;
                    }
                }
            }
        }

        return $modified;
    }


    /**
     * adds a dependency to the cache
     *
     * @param string $parent
     * @param string $file
     * @return bool
     */
    public function dependency($parent, $file)
    {
        if (!$file || $file == "") new Error("Please set a file for your dependency");
        if (!$parent || $parent == "") new Error("Please set a parent file for your dependency");

        $file   = $this->clean($file);
        $parent = $this->clean($parent);

        $cache = $this->getCache();
        if (is_null($cache["dependencies"][ $parent ])) {
            $cache["dependencies"][ $parent ] = array();
        }
        if (!in_array($file, $cache["dependencies"][ $parent ])) {
            array_push($cache["dependencies"][ $parent ], $file);
        }

        return $this->saveCache($cache);
    }


    /**
     * writes the modify times for the current template
     * into our cache file
     *
     * @param $file
     * @return bool
     */
    private function setTime($file)
    {
        $file      = $this->clean($file);
        $cache     = $this->getCache();
        $templates = $this->helpers->templates($file);
        foreach ($templates as $template) {
            $cache["times"][ $file ][ md5($template) ] = filemtime($template);
        }

        return $this->saveCache($cache);
    }


    /**
     * returns the cache array
     *
     * @return array
     */
    private function getCache()
    {
        $cacheFile = $this->createFile($this->cacheFile);
        $cache     = unserialize(file_get_contents($cacheFile));

        return $cache;
    }


    /**
     * saves the array to the cache
     *
     * @param array $cache
     * @return bool
     */
    private function saveCache($cache)
    {
        $cacheFile = $this->createFile($this->cacheFile);

        return file_put_contents($cacheFile, serialize($cache));
    }


    /**
     * returns the cache path for the given file
     *
     * @param $file
     * @return string
     */
    public function getPath($file)
    {
        $this->updateCacheDir();
        # remove the template dir
        foreach ($this->dirs->getTemplateDirs() as $dir) {
            $file = str_replace($dir, "", $file);
        }

        # make sure we have a php extension
        $file = $this->extension($file);

        # replace slashes with an dot
        $file = str_replace("/", '.', $file);

        # add cache directory and escape the slashes with an underscore
        $file = $this->cacheDir . $file;

        return $file;
    }


    /**
     * adds a php extension to the files path
     *
     * @param $file
     * @return mixed|string
     */
    private function extension($file)
    {
        $file             = str_replace("." . $this->config->get("extension"), ".php", $file);
        $currentExtension = array_reverse(explode(".", $file))[0];
        if ($currentExtension != "php") {
            $file = $file . ".php";
        }

        return $file;
    }


    /**
     * creates the file if its not already there
     *
     * @param $file
     * @return mixed|string
     */
    private function createFile($file)
    {
        $file = $this->getPath($file);
        # create the file
        if (!file_exists($file)) touch($file);

        return $file;
    }


    /**
     * empties the cache directory
     *
     * @param bool $dir
     * @return bool|Error
     */
    public function clear($dir = NULL)
    {
        try {
            if (is_null($dir)) {
                $this->updateCacheDir();
                $dir = $this->cacheDir;
            }
            foreach (scandir($dir) as $item) {
                if ($item != '..' && $item != '.') {
                    $item = $dir . "/" . $item;
                    if (!is_dir($item)) {
                        unlink($item);
                    } else {
                        $this->clear($item);
                    }
                }
            }

            return rmdir($dir);
        } catch (\Exception $e) {
            return new Error($e);
        }
    }


    /**
     * removes the template dirs and the extension form a file path
     *
     * @param $file
     * @return string
     */
    private function clean($file)
    {
        foreach ($this->dirs->getTemplateDirs() as $templateDir) {
            $file = str_replace($templateDir, "", $file);
        }

        $file = str_replace("." . $this->config->get("extension"), "", $file);

        return $file;
    }


    /**
     * updates the cache directory if we changed it via php
     */
    private function updateCacheDir()
    {
        $this->dirs->setCacheDir($this->config->get("cache_dir"));
        $this->cacheDir = $this->config->get("cache_dir");
    }

}