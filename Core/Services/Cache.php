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
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->config       = $caramel->config();
        $this->dependencies = array();
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
            $content = "<!-- " . $this->config->get("file_header") . " -->" . $content;
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

    public function clearCache()
    {
        $cacheDir = $this->config->getDirectoryHandler()->getCacheDir();

        return $this->recursiveDirectoryRemove($cacheDir);
    }

    /**
     * @param $dir
     * @return bool|Error
     */
    private function recursiveDirectoryRemove($dir)
    {
        try {
            foreach (scandir($dir) as $item) {
                if ($item != '..' && $item != '.') {
                    $item = $dir . "/" . $item;
                    if (!is_dir($item)) {
                        unlink($item);
                    } else {
                        $this->recursiveDirectoryRemove($item);
                    }
                }
            }
            return rmdir($dir);
        } catch (\Exception $e) {
            return new Error($e);
        }
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
        $DirectoryHandler = $this->config->getDirectoryHandler();
        # remove the template dir
        foreach ($DirectoryHandler->getTemplateDir() as $templateDir) {
            $file = str_replace($templateDir, "", $file);
        }
        # add cache directory and escape the slashes with an underscore
        $DirectoryHandler->setCacheDir($this->config->get("cache_dir"));
        $cacheDir = $this->config->get("cache_dir") . "/tempalte/";
        if (!is_dir($cacheDir)) mkdir($cacheDir, 0777);
        $file = $cacheDir . str_replace("/", '_', $file);
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