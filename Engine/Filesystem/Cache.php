<?php

namespace Temple\Engine\Filesystem;


use Temple\Engine\Config;
use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;


class Cache extends Injection
{


    /** @var  Config $Config */
    protected $Config;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler"
        );
    }


    /** @var string $cacheFile */
    private $cacheFile = "timestamp";


    /**
     * sets the cache directory
     *
     * @param string $dir
     *
     * @return string
     */
    public function setDirectory($dir)
    {
        $this->DirectoryHandler->createDir($dir);

        return $this->DirectoryHandler->setCacheDir($dir);
    }


    /**
     * returns the cache directory
     *
     * @return string
     */
    public function getDirectory()
    {
        $cacheDir = $this->Config->getCacheDir();
        $this->DirectoryHandler->createDir($cacheDir);

        return $this->DirectoryHandler->getCacheDir();
    }


    /**
     * saves the file to the cache and returns its path
     *
     * @param     $file
     * @param     $content
     *
     * @return string
     * @throws Exception
     */
    public function save($file, $content)
    {
        $this->setTime($file);
        $file = $this->createFile($file);
        file_put_contents($file, $content);

        return $file;
    }


    /**
     * @return bool
     */
    public function invalidate()
    {

        $cacheFile = $this->getPath($this->cacheFile);
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        return false;
    }


    /**
     * returns if the file passed is newer than the cached file
     *
     * @param $file
     *
     * @return bool
     */
    public function isModified($file)
    {
        if (!$this->Config->isCacheEnabled()) {
            return true;
        }

        $cache    = $this->getCache();
        $modified = false;

        if (!$cache) {
            return true;
        } else {
            $times     = $cache["times"];
            $templates = array();
            if (isset($cache["dependencies"])) {
                $dependencies = $cache["dependencies"][ $file ];
                if ($dependencies) {
                    foreach ($dependencies as $dependency) {
                        $templates = array_merge($templates, $this->getTemplateFiles($dependency));
                    }
                }
            }

            $file      = str_replace("." . $this->Config->getExtension(), "", $file);
            $templates = array_merge($templates, $this->getTemplateFiles($file));
            foreach ($templates as $template) {
                $templatePath = $template;
                $template     = $this->cleanFile($template);
                $cacheTime    = $times[ $template ][ md5($templatePath) ];
                $currentTime  = filemtime($templatePath);

                if ($cacheTime != $currentTime || $this->CacheFilesAreMissing($templatePath)) {
                    $modified = true;
                }
            }
        }


        return $modified;
    }


    /**
     * check if all needed variable files exist
     *
     * @param string $templatePath
     *
     * @return bool
     */
    private function CacheFilesAreMissing($templatePath)
    {
        $cacheFilePath = $templatePath;
        foreach ($this->DirectoryHandler->getTemplateDirs() as $templateDir) {
            $cacheFilePath = str_replace($templateDir, "", $cacheFilePath);
        }

        // check if all needed variable files exist
        $templateFile    = $this->getDirectory() . str_replace("." . $this->Config->getExtension(), ".php", $cacheFilePath);
        $variableFile    = $this->getDirectory() . str_replace("." . $this->Config->getExtension(), ".variables.php", $cacheFilePath);
        $urlVariableFile = $this->getDirectory() . str_replace("." . $this->Config->getExtension(), ".variables." . VariableCache::getUrlHash() . ".php", $cacheFilePath);

        if (!file_exists($variableFile) || !file_exists($urlVariableFile) || !file_exists($templateFile)) {
            return true;
        }

        return false;
    }


    /**
     * returns a cache file
     *
     * @param $file
     *
     * @return string
     */
    public function getFile($file)
    {
        # returns the cache file
        $file = $this->createFile($file);

        return $file;
    }


    /**
     * adds a dependency to the cache
     *
     * @param string $parent
     * @param string $file
     *
     * @return bool
     * @throws Exception
     */
    public function addDependency($parent, $file)
    {

        if (!$file || $file == "") {
            throw new Exception(1, "Please set a file for your dependency");
        }

        if (!$parent || $parent == "") {
            throw new Exception(1, "Please set a parent file for your dependency");
        }

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
     * removes the whole cache directory
     *
     * @param null $dir
     *
     * @return bool
     */
    public function clear($dir = null)
    {
        if ($dir == null) {
            $dir = $this->getDirectory();
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
     * writes the modify times for the current template
     * into our cache file
     *
     * @param $file
     *
     * @return bool
     */
    private function setTime($file)
    {
        $file      = $this->cleanFile($file);
        $cache     = $this->getCache();
        $templates = $this->getTemplateFiles($file);
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
    protected function getCache()
    {
        $cacheFile = $this->createFile($this->cacheFile);
        $cache     = unserialize(file_get_contents($cacheFile));

        return $cache;
    }


    /**
     * saves the array to the cache
     *
     * @param array $cache
     *
     * @return bool
     */
    protected function saveCache($cache)
    {
        $cacheFile = $this->createFile($this->cacheFile);

        return file_put_contents($cacheFile, serialize($cache));
    }


    /**
     * returns all found template files
     *
     * @param $file
     *
     * @return array
     */
    private function getTemplateFiles($file)
    {

        $dirs  = $this->DirectoryHandler->getTemplateDirs();
        $files = array();
        foreach ($dirs as $dir) {
            $templateFile = $dir . $file . "." . $this->Config->getExtension();
            if (file_exists($templateFile)) {
                $files[] = $templateFile;
            }
        }


        return $files;
    }


    /**
     * creates the file if its not already there
     *
     * @param $file
     *
     * @return mixed|string
     */
    private function createFile($file)
    {
        $file = $this->getPath($file);
        # setup the file
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($file)) touch($file);

        return $file;
    }


    /**
     * returns the cache path for the given file
     *
     * @param $file
     *
     * @return string
     */
    public function getPath($file)
    {
        # remove the template dir
        $file = $this->cleanFile($file);
        $file = $this->extension($file);
        $file = $this->getDirectory() . $file;

        return $file;
    }


    /**
     * adds a php extension to the files path
     *
     * @param $file
     *
     * @return mixed|string
     */
    private function extension($file)
    {
        $file = str_replace("." . $this->Config->getExtension(), "", $file);
        $file = str_replace(".php", "", $file);
        $file = $file . ".php";

        return $file;
    }


    /**
     * removes the template dirs and the extension form a file path
     *
     * @param $file
     *
     * @return string
     */
    private function cleanFile($file)
    {
        foreach ($this->DirectoryHandler->getTemplateDirs() as $templateDir) {
            $file = str_replace($templateDir, "", $file);
        }

        $file = str_replace("." . $this->Config->getExtension(), "", $file);

        return $file;
    }


}