<?php

namespace Temple\Services;


use Temple\Exception\TempleException;
use Temple\Models\ServiceModel;

class CacheService extends ServiceModel
{


    /** @var string $cacheFile */
    private $cacheFile = ".cache";


    /**
     * sets the cache directory
     *
     * @param string $dir
     * @return string
     */
    public function setDir($dir)
    {
        return $this->directoryService->set("cache", $dir);
    }


    /**
     * returns the cache directory
     *
     * @return string
     */
    public function getDir()
    {
        return $this->directoryService->get("cache");
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


        if (!$this->configService->get("cache.enable")) {
            return true;
        }

        $cache = $this->getCache();
        if (is_bool($cache)) {
            return true;
        }

        $modified = false;

        $times     = $cache["times"];
        $templates = array();
        if (isset($cache["dependencies"])) {
            $dependencies = $cache["dependencies"][ $file ];
            if ($dependencies) {
                foreach ($dependencies as $dependency) {
                    $templates = array_merge($templates, $this->templateService->findTemplates($dependency));
                }
            }
        }

        $file      = str_replace("." . $this->configService->get("template.extension"), "", $file);
        $templates = array_merge($templates, $this->templateService->findTemplates($file));
        foreach ($templates as $template) {
            $templatePath = $template;
            foreach ($this->templateService->getDirs() as $dir) {
                $template = str_replace($dir, "", $templatePath);
            }
            $template = str_replace("." . $this->configService->get("template.extension"), "", $template);
            $cacheTime = $times[ $template ][ md5($templatePath) ];
            $currentTime = filemtime($templatePath);
            if ($cacheTime != $currentTime) {
                $modified = true;
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
     * @throws TempleException
     */
    public function dependency($parent, $file)
    {
        if (!$file || $file == "") throw new TempleException("Please set a file for your dependency");
        if (!$parent || $parent == "") throw new TempleException("Please set a parent file for your dependency");

        $file   = $this->cleanFile($file);
        $parent = $this->cleanFile($parent);

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
        $file      = $this->cleanFile($file);
        $cache     = $this->getCache();
        $templates = $this->templateService->findTemplates($file);
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
        # remove the template dir
        foreach ($this->templateService->getDirs() as $dir) {
            $file = str_replace($dir, "", $file);
        }

        # make sure we have a php extension
        $file = $this->extension($file);

        # replace slashes with an dot
        $file = str_replace(DIRECTORY_SEPARATOR, '.', $file);
        $file = $this->getDir() . DIRECTORY_SEPARATOR . $file;

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
        $file             = str_replace("." . $this->configService->get("template.extension"), ".php", $file);
        $currentExtension = array_reverse(explode(".", $file));
        $currentExtension = $currentExtension[0];
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
     * @return bool
     */
    public function clear($dir = NULL)
    {
        if ($dir == NULL) {
            $dir = $this->getDir();
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
    }


    /**
     * removes the template dirs and the extension form a file path
     *
     * @param $file
     * @return string
     */
    private function cleanFile($file)
    {
        foreach ($this->templateService->getDirs() as $templateDir) {
            $file = str_replace($templateDir, "", $file);
        }

        $file = str_replace("." . $this->configService->get("template.extension"), "", $file);

        return $file;
    }


}