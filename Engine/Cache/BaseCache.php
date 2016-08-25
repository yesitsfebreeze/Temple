<?php

namespace Temple\Engine\Cache;


use Temple\Engine\InjectionManager\Injection;


abstract class BaseCache extends Injection
{

    /** @var  string $cacheFile */
    protected $cacheFile = "unused.cache";


    /**
     * @param string $cacheFile
     */
    public function setCacheFile($cacheFile)
    {
        $this->cacheFile = $cacheFile;
    }


    /**
     * @param       $object
     * @param  null $identifier
     */
    public function save($object, $identifier = null)
    {
        $this->getCurrent($object, $identifier);
    }


    /**
     * @param      $object
     * @param null $identifier
     *
     * @return bool
     */
    public function isModified($object, $identifier = null)
    {
        return $this->get($object, $identifier) !== $object;
    }


    /**
     * @param null $object
     * @param null $identifier
     *
     * @return array|mixed|null
     */
    public function get($object = null, $identifier = null)
    {
        if (is_null($object)) {
            $cache = $this->getCache();

            if (!is_null($identifier)) {
                $returnCache = array();
                foreach ($cache as $key => $item) {
                    if (strpos($key, $identifier) != false) {
                        $returnCache[] = $item;
                    }
                }
                $cache = $returnCache;
            }

            return $cache;
        }

        return $this->getCurrent($object, $identifier);
    }


    /**
     * @return bool
     */
    public function clear()
    {
        if (file($this->cacheFile)) {
            unlink($this->cacheFile);
        }

        return true;
    }


    /**
     * @param      $object
     * @param null $identifier
     *
     * @return mixed|null
     */
    private function getCurrent($object, $identifier = null)
    {
        $cache = $this->getCache();
        if (is_string($object)) {
            $name = "Temple\\" . str_replace("/", "\\", $object);
        } else {
            $name = get_class($object);
        }

        if (!is_null($identifier)) {
            $name = $identifier . ":::" . get_class($object);
        }

        if (isset($cache[ $name ])) {
            if (!is_null($object)) {
                $serializedValue = serialize($object);
                if ($cache[ $name ] != $serializedValue) {
                    $cache[ $name ] = $object;
                    $this->saveCache($cache);
                }
            }
        } else {
            if (is_null($object)) {
                return null;
            } else {
                $cache[ $name ] = $object;
                $this->saveCache($cache);
            }
        }

        return $cache[ $name ];
    }


    /**
     * @return array
     */
    protected function getCache()
    {
        $path = $this->createCacheFile();

        return unserialize(file_get_contents($path));
    }


    /**
     * @param array $cache
     *
     * @return bool
     */
    protected function saveCache($cache)
    {
        $path = $this->createCacheFile();

        return file_put_contents($path, serialize($cache));
    }


    /**
     * creates cache file if it doesn't exist already
     */
    private function createCacheFile()
    {
        $cacheDir = $cacheFile = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Cache" . DIRECTORY_SEPARATOR;
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $cacheFile = $cacheDir . $this->cacheFile . ".php";
        if (!file_exists($cacheFile)) {
            touch($cacheFile);
        }

        return $cacheFile;
    }


}