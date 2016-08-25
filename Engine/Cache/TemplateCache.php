<?php

namespace Temple\Engine\Cache;


use Temple\Engine\Config;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\BaseLanguage;
use Temple\Engine\Languages\Languages;


class TemplateCache extends Injection
{


    /** @var  Config $Config */
    protected $Config;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var  Languages $Languages */
    protected $Languages;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/Languages/Languages"         => "Languages",
            "Engine/EventManager/EventManager"   => "EventManager"
        );
    }


    /** @var string $cacheFile */
    protected $cacheFile = "template.cache";


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
     * #
     *
     * @param $folder
     *
     * @return string
     */
    public function getDirectory($folder = null)
    {
        if (!is_null($folder)) {
            return $this->DirectoryHandler->createDir($folder);
        }

        $cacheDir = $this->Config->getCacheDir();
        $this->DirectoryHandler->createDir($cacheDir);

        return $this->DirectoryHandler->getCacheDir();

    }


    /**
     * saves the file to the cache and returns its path
     *
     * @param string $file
     * @param string $content
     *
     * @return string
     * @throws Exception
     */
    public function saveTemplate($file, $content)
    {
        $folder = $this->getFolder($file);
        $this->setTime($file);
        /** @var BaseLanguage $language */
        $language  = $this->getLanguage($file)->getConfig()->getName();
        $extension = $this->getExtension($file);
        $file      = $this->createFile($file, $folder);
        $this->Config->addLanguageCacheFolder($this->getDirectory($folder));
        file_put_contents($file, $content);

        $this->EventManager->dispatch($language, "cache.save", array($file, $content, $extension));

        return $file;
    }


    /**
     * @throws Exception
     * @return bool
     */
    public function invalidateCacheFile()
    {
        $cacheFile = $this->createCacheFile();
        if (file_exists($cacheFile)) {
            if (is_writable($cacheFile)) {
                unlink($cacheFile);
            } else {
                throw new Exception(500, "You don't have the permission to delete this file", $cacheFile);
            }
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

        if (!$this->Config->isCacheInvalidation()) {
            return false;
        }

        if (!$this->Config->isCacheEnabled()) {
            return true;
        }

        $file  = $this->cleanFile($file);
        $cache = $this->getCache();

        if (!$cache) {
            return true;
        } else {
            $modified = $this->checkModified($file);
            if (!$modified) {
                $modified = $this->checkDependencies($file);
            }
        }

        return $modified;
    }


    /**
     * checks all of the files dependencies and returns true if they are modified
     *
     * @param string $file
     *
     * @return bool
     */
    private function checkDependencies($file)
    {
        $cache    = $this->getCache();
        $modified = false;
        if (isset($cache["dependencies"]) && isset($cache["dependencies"][ $file ])) {
            foreach ($cache["dependencies"][ $file ] as $dependency) {
                $template = $dependency["file"];
                $type     = $dependency["type"];
                $modified = $this->checkModified($template, $type);
                if ($modified) {
                    break;
                }
            }
        }

        return $modified;
    }


    /**
     * check if a file or its parents are modified
     *
     * @param      $file
     * @param bool $needToExist | if the file has to exist withing the cache
     *
     * @return bool
     */
    public function checkModified($file, $needToExist = true)
    {
        $cache         = $this->getCache();
        $templateCache = $cache["templates"];

        $modified = false;

        foreach ($this->getTemplateFiles($file) as $template) {
            $templatePath = $template;
            $template     = $this->cleanFile($template);

            if (isset($templateCache[ $template ])) {

                $cacheTime   = $templateCache[ $template ][ md5($templatePath) ];
                $currentTime = filemtime($templatePath);
                $timeDiffers = $cacheTime < $currentTime;
                $exists      = true;
                if ($needToExist) {
                    $exists = $this->CacheFilesExist($templatePath);
                }
                if ($timeDiffers || !$exists) {
                    $modified = true;
                }
            } else {
                $modified = true;
            }
            if ($modified) {
                break;
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
    private function CacheFilesExist($templatePath)
    {
        $cacheFilePath = $templatePath;
        foreach ($this->DirectoryHandler->getTemplateDirs() as $templateDir) {
            $cacheFilePath = str_replace($templateDir, "", $cacheFilePath);
        }


        $languageConfig = $this->getLanguage($templatePath)->getConfig();
        $folder         = $languageConfig->getCacheDir();
        $folder         = $this->DirectoryHandler->validate($folder, true);

        $extension = "." . $languageConfig->getExtension();

        // check if all needed variable files exist
        $templateFile       = $folder . str_replace("." . $this->Config->getExtension(), $extension, $cacheFilePath);
        $templateFileExists = file_exists($templateFile);

        $variableCache           = $languageConfig->getVariableCache();
        $variableCacheFileExists = true;
        if ($variableCache) {
            $variableFile            = $folder . str_replace("." . $this->Config->getExtension(), ".variables" . $extension, $cacheFilePath);
            $variableCacheFileExists = file_exists($variableFile);
        }

        if (!$templateFileExists || !$variableCacheFileExists) {
            return false;
        }


        return true;
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
        $folder = $this->getFolder($file);
        # returns the cache file
        $file = $this->createFile($file, $folder);

        return $file;
    }


    /**
     * adds a dependency to the cache
     *
     * @param string $parent
     * @param string $file
     * @param bool   $needToExist
     *
     * @return bool
     * @throws Exception
     */
    public function addDependency($parent, $file, $needToExist = true)
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

        if (!isset($cache["templates"][ $file ])) {
            $this->setTime($file);
        }

        if (!isset($cache["dependencies"][ $parent ])) {
            $cache["dependencies"][ $parent ] = array();
        }

        if (!in_array($file, $cache["dependencies"][ $parent ])) {
            $cache["dependencies"][ $parent ][ $file ] = array("file" => $file, "type" => $needToExist);
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
    public function setTime($file)
    {
        $file      = $this->cleanFile($file);
        $cache     = $this->getCache();
        $templates = $this->getTemplateFiles($file);
        foreach ($templates as $template) {
            $cache["templates"][ $file ][ md5($template) ] = filemtime($template);
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
        $cacheFile = $this->createCacheFile();
        $cache     = unserialize(file_get_contents($cacheFile));

        // set initial templates sub array
        if (!isset($cache["templates"])) {
            $cache["templates"] = array();
        }

        // set initial dependencies sub array
        if (!isset($cache["dependencies"])) {
            $cache["dependencies"] = array();
        }

        return $cache;
    }


    /**
     * saves the array to the cache
     *
     * @param array $cache
     *
     * @return bool
     */
    public function saveCache($cache)
    {
        $cacheFile = $this->createCacheFile();

        return file_put_contents($cacheFile, serialize($cache));
    }


    /**
     * creates a cache file if it doesn't exist
     */
    protected function createCacheFile()
    {

        $dir  = $this->getDirectory();
        $file = $dir . $this->cacheFile . ".php";
        if (!file_exists($file)) {
            touch($file);
        }

        return $file;
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
     * @param $folder
     *
     * @return mixed|string
     */
    private function createFile($file, $folder = null)
    {
        $file = $this->getTemplatePath($file, $folder);
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
     * @param $folder
     *
     * @return string
     */
    public function getTemplatePath($file, $folder = null)
    {
        # remove the template dir
        $file = $this->cleanFile($file);
        $file = $this->extension($file);
        $file = $this->getFolder($folder) . $file;

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
        $extension = $this->getExtension($file);
        $file      = str_replace("." . $this->Config->getExtension(), "", $file);
        $file      = str_replace("." . $extension, "", $file);
        $file      = $file . "." . $extension;

        return $file;
    }


    /**
     * removes the template dirs and the extension form a file path
     *
     * @param $file
     *
     * @return string
     * @throws Exception
     */
    private function cleanFile($file)
    {
        $templateDirs = $this->DirectoryHandler->getTemplateDirs();
        if (!is_null($templateDirs) && sizeof($templateDirs) > 0) {
            foreach ($this->DirectoryHandler->getTemplateDirs() as $templateDir) {
                $file = str_replace($templateDir, "", $file);
            }

            $file = str_replace("." . $this->Config->getExtension(), "", $file);
        } else {
            throw new Exception(123123, "Please add at least one template directory!");
        }

        return $file;
    }


    /**
     * @param string $file
     *
     * @return null|string
     */
    private function getExtension($file)
    {
        $file = str_replace(".__vars", "", $file);
        $languageConfig = $this->Languages->getLanguageFromFile($file)->getConfig();
        $extension      = $languageConfig->getExtension();

        return $extension;
    }


    /**
     * @param $file
     *
     * @return string
     */
    public function getFolder($file)
    {
        $filename       = explode(".", $file);
        $filename       = $filename[0];
        $languageConfig = $this->getLanguage($filename)->getConfig();
        $folder         = $languageConfig->getCacheDir();

        return $folder;
    }


    /**
     * @param $filename
     *
     * @return false|BaseLanguage
     */
    private function getLanguage($filename)
    {
        $filename = $this->DirectoryHandler->templateExists($filename);
        $language = $this->Languages->getLanguageFromFile($filename);

        return $language;
    }

}