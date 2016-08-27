<?php

namespace Temple\Engine\Cache;


use Temple\Engine\Exception\Exception;
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
     * returns the cache file location
     */
    public function getCacheFile()
    {
        $path = $this->createCacheFile();

        return $path;
    }


    /**
     * @param       $value
     * @param  int  $identifier
     *
     * @throws Exception
     */
    public function save($value, $identifier = 0)
    {
        if (!is_object($value)) {
            throw new Exception(23123123, "%\$value% must be an object!");
        }

        if (!is_null($value)) {
            $cache = $this->getCache();

            $name = get_class($value);

            if (!isset($cache[ $identifier ])) {
                $cache[ $identifier ] = array();
            }

            $cache[ $identifier ][ $name ] = $this->realSave($value);

            $this->saveCache($cache);
        }
    }


    /**
     * @param $value
     *
     * @return string
     */
    protected function realSave($value)
    {
        return serialize($value);
    }


    /**
     * @param       $value
     * @param  int  $identifier
     */
    public function update($value, $identifier = 0)
    {
        $this->save($value, $identifier);
    }


    /**
     * @param      $value
     * @param int  $identifier
     *
     * @return bool
     */
    public function changed($value, $identifier = 0)
    {
        return $this->get($value, $identifier) !== $value;
    }


    /**
     * @param null $value
     * @param int  $identifier
     *
     * @return array|mixed|null
     */
    public function get($value = null, $identifier = 0)
    {
        if (is_null($value)) {
            if (is_null($identifier)) {
                return $this->getCache();
            } else {
                $cache = $this->getCache();
                if (isset($cache[ $identifier ])) {
                    return $cache[ $identifier ];
                }

                return null;
            }
        }

        $cache = $this->getCache();
        if (isset($cache[ $identifier ])) {
            if (is_string($value)) {
                $name = "Temple\\" . str_replace("/", "\\", $value);
            } else {
                $name = get_class($value);
            }
            if (isset($cache[ $identifier ][ $name ])) {
                return $cache[ $identifier ][ $name ];
            };
        }

        return null;
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
     * @return int
     */
    protected function saveCache($cache)
    {
        $path = $this->createCacheFile();

        return file_put_contents($path, serialize($cache));
    }


    /**
     * creates cache file if it doesn't exist already
     */
    protected function createCacheFile()
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