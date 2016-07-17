<?php

namespace Underware\Engine\Filesystem;


use Underware\Engine\Config;
use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;


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
    private $cacheFile = ".base";


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


    public function generate()
    {
        // todo: compile all templates to cache
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
            if ($cacheTime != $currentTime) {
                $modified = true;
            }
        }

        return $modified;
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
            throw new Exception("Please set a file for your dependency");
        }

        if (!$parent || $parent == "") {
            throw new Exception("Please set a parent file for your dependency");
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
     *
     * @return bool
     */
    private function saveCache($cache)
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

        # make sure we have a php extension
        $file = $this->extension($file);

        # replace slashes with an dot
        $file = str_replace(DIRECTORY_SEPARATOR, '.', $file);
        $file = $this->getDirectory() . DIRECTORY_SEPARATOR . $file;

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
        $file = str_replace(".cachefile", "", $file);
        $file = str_replace(".php", "", $file);
        $file = $file . ".cachefile.php";

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