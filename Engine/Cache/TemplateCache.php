<?php

namespace Temple\Engine\Cache;


use Temple\Engine\Config;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\Languages\BaseLanguage;
use Temple\Engine\Languages\Languages;
use Temple\Languages\Core\Language;


class TemplateCache extends BaseCache
{

    /** @var  string $cacheFile */
    protected $cacheFile = "template.cache";

    /** @var  Config $Config */
    protected $Config;

    /** @var  ConfigCache $ConfigCache */
    protected $ConfigCache;

    /** @var  Languages $Languages */
    protected $Languages;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var  Language $Language */
    protected $Language;


    /**
     * set dependencies
     *
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Engine/Config" => "Config",
            "Engine/Cache/ConfigCache" => "ConfigCache",
            "Engine/Languages/Languages" => "Languages",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler"
        );
    }


    /**
     * returns if the file has changed since the last update
     *
     * @param      $value
     * @param int  $identifier
     * @param bool $update
     *
     * @return bool
     */
    public function changed($value, $identifier = 0, $update = true)
    {

        if ($this->Config->isConfigCacheEnabled()) {
            if ($this->ConfigCache->changed($this->Config, $this->Config->getIdentifier())) {
                $this->clear();
                $this->ConfigCache->clear();

                return true;
            }
        }

        if (!$this->Config->isCacheEnabled()) {
            if ($update) {
                $this->update($value, $identifier);
            }

            return true;
        }

        $cacheFile = $this->getCacheFilePath($value);
        $templateFile = $this->DirectoryHandler->getTemplatePath($value);
        $cache = $this->getCache();


        if (!isset($cache[ $identifier ])) {
            if ($update) {
                $this->update($value, $identifier);
            }

            return true;
        }

        if (!isset($cache[ $identifier ][ $value ])) {
            if ($update) {
                $this->update($value, $identifier);
            }

            return true;
        }

        if ($cache[ $identifier ][ $value ]["needed"]) {
            // this is checking if the variable file from the cache
            // is deleted and therefore the template would not work
            // if so we reprocess the template
            $languageConfig = $this->Language->getConfig();
            if (!is_null($languageConfig->getVariableCache())) {
                $variableCacheFile = $this->getCacheFilePath($value, "_variables");
                if (!file_exists($variableCacheFile)) {
                    if ($update) {
                        $this->update($value, $identifier);
                    }

                    return true;
                }
            }

            if (!file_exists($cacheFile)) {
                if ($update) {
                    $this->update($value, $identifier);
                }

                return true;
            }
        }


        if ($cache[ $identifier ][ $value ]["time"] != filemtime($templateFile)) {
            if ($update) {
                $this->update($value, $identifier);
            }

            return true;
        }

        // iterate over all dependencies and see if one of those has changed
        if (isset($cache[ $identifier ][ $value ]["dependencies"]) && sizeof($cache[ $identifier ][ $value ]["dependencies"]) > 0) {
            $dependencies = $cache[ $identifier ][ $value ]["dependencies"];
            foreach ($dependencies as $dependency) {
                if ($this->changed($dependency["file"], $identifier, false)) {
                    if ($update) {
                        $this->update($value, $identifier);
                    }
                    foreach ($dependencies as $template) {
                        if (isset($cache[ $identifier ][ $template["file"] ])) {
                            $cache[ $identifier ][ $template["file"] ]["time"] = 0;
                        }
                    }
                    $this->saveCache($cache);

                    return true;
                }

                // if a file got renamed or deleted we
                // check if it exists, otherwise we have to reprocess
                $dependencyTemplateFile = $this->DirectoryHandler->getTemplatePath($dependency["file"]);
                if (!file_exists($dependencyTemplateFile)) {
                    if ($update) {
                        $this->update($value, $identifier);
                    }

                    return true;
                }
            }
        }

        return false;
    }


    /**
     * dumps the file with the given content to the cache
     *
     * @param     $value
     * @param     $content
     * @param int $identifier
     *
     * @return string
     */
    public function dump($value, $content, $identifier = 0)
    {

        $cacheFile = $this->getCacheFilePath($value);

        if (!is_dir($cacheFile)) {
            $this->DirectoryHandler->createDir($cacheFile);
        }

        if (!file_exists($cacheFile)) {
            touch($cacheFile);
        }

        $this->update($value, $identifier);
        file_put_contents($cacheFile, $content);

        return $cacheFile;
    }


    /**
     * update the cache time for a template file
     *
     * @param      $value
     * @param int  $identifier
     *
     * @return bool
     */
    public function update($value, $identifier = 0)
    {
        $value = $this->DirectoryHandler->normalizeExtension($value);
        $templateFile = $this->DirectoryHandler->getTemplatePath($value);

        $cache = $this->getCache();
        if (!isset($cache[ $identifier ])) {
            $cache[ $identifier ] = array();
        }
        if (!isset($cache[ $identifier ][ $value ])) {
            $cache[ $identifier ][ $value ] = array();
            $cache[ $identifier ][ $value ]["dependencies"] = array();
            $cache[ $identifier ][ $value ]["needed"] = true;
        }
        $cache[ $identifier ][ $value ]["location"] = $templateFile;
        $cache[ $identifier ][ $value ]["time"] = filemtime($templateFile);

        return $this->saveCache($cache);
    }


    /**
     * adds a dependency to the given file
     * so temple can check if it need to be reprocessed
     *
     * @param      $parent
     * @param      $file
     * @param bool $needed
     * @param int  $identifier
     */
    public function addDependency($parent, $file, $needed = true, $identifier = 0)
    {
        $cache = $this->getCache();

        // normalize all incoming files
        // to be sure they are indexed within the cache
        $parent = $this->DirectoryHandler->normalizeExtension($parent);
        $file = $this->DirectoryHandler->normalizeExtension($file);

        if (isset($cache[ $identifier ][ $parent ])) {
            $cache[ $identifier ][ $parent ]["dependencies"][ $file ] = array(
                "file" => $file,
                "needed" => $needed
            );
            if (isset($cache[ $identifier ][ $file ])) {
                $cache[ $identifier ][ $file ]["needed"] = $needed;
            }
        }

        $this->saveCache($cache);
    }


    /**
     * returns a template from the cache
     *
     * @param string $value
     * @param mixed  $identifier
     *
     * @throws Exception
     * @return string
     */
    public function get($value = null, $identifier = null)
    {
        if (is_null($value)) {
            throw new Exception(123, "Please pass a template file!");
        }

        $cacheFile = $this->getCacheFilePath($value);

        return $cacheFile;
    }


    /**
     * returns the path to the file within the cache directory of the language
     *
     * @param string $file
     * @param string $postfix
     *
     * @return string
     */
    private function getCacheFilePath($file, $postfix = "")
    {
        // get rid of the cache postfix to enable the Languages
        // class to get the extension for the file
        if ($postfix !== "") {
            $file = $this->DirectoryHandler->normalizeExtension($file);
            $file = preg_replace("/\." . $this->Config->getExtension() . "$/", $postfix, $file);
        }

        if (strpos($file, "_variables") != false) {
            $file = str_replace("_variables", "", $file);
            $postfix = "_variables";
        }

        // add the language template extension to it
        $file = $this->DirectoryHandler->normalizeExtension($file);

        // get the full path to the cache of the language
        /** @var BaseLanguage $language */
        $this->Language = $this->Languages->getLanguageFromFile($file);
        $languageConfig = $this->Language->getConfig();
        $cacheDir = $languageConfig->getCacheDir();
        $extension = $languageConfig->getExtension();
        $cacheFile = $cacheDir . preg_replace("/\." . $this->Config->getExtension() . "$/", $postfix . "." . $extension, $file);

        return $cacheFile;
    }

}