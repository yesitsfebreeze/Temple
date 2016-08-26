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
     * @param       $value
     * @param  null $identifier
     */
    public function save($value, $identifier = null)
    {
        $this->getCurrent($value, $identifier);
    }


    /**
     * @param       $value
     * @param  null $identifier
     */
    public function update($value, $identifier = null)
    {
        $this->save($value, $identifier);
    }


    /**
     * @param      $value
     * @param null $identifier
     *
     * @return bool
     */
    public function changed($value, $identifier = null)
    {
        return $this->get($value, $identifier) !== $value;
    }


    /**
     * @param null $value
     * @param null $identifier
     *
     * @return array|mixed|null
     */
    public function get($value = null, $identifier = null)
    {
        if (is_null($value)) {
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

        return $this->getCurrent($value, $identifier);
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
     * @param      $value
     * @param null $identifier
     *
     * @return mixed|null
     */
    protected function getCurrent($value, $identifier = null)
    {
        $cache = $this->getCache();
        if (is_string($value)) {
            $name = "Temple\\" . str_replace("/", "\\", $value);
        } else {
            $name = get_class($value);
        }

        if (!is_null($identifier)) {
            $name = $identifier . ":::" . get_class($value);
        }

        if (isset($cache[ $name ])) {
            if (!is_null($value)) {
                $serializedValue = serialize($value);
                if ($cache[ $name ] != $serializedValue) {
                    $cache[ $name ] = $value;
                    $this->saveCache($cache);
                }
            }
        } else {
            if (is_null($value)) {
                return null;
            } else {
                $cache[ $name ] = $value;
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