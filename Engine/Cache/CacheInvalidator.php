<?php

namespace Temple\Engine\Cache;


use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Storage;


class CacheInvalidator extends ClassCache
{

    /** @var  array $tempCache */
    private $cache;


    /**
     * checks the validation of a list of predefined classes
     */
    public function checkValidation()
    {
        try {
            $this->classesToCheck();
        } catch (Exception $e) {
            $this->invalidateCacheFile();
            $this->saveCache($this->cache);
        }
    }


    /**
     * all classes which are need to be checked
     */
    private function classesToCheck()
    {
        $this->check($this->Config);
        $this->check(new Storage());
    }


    /**
     * @param Injection $class
     *
     * @throws Exception
     * @return bool
     */
    public function check(Injection $class)
    {

        if (!$this->Config->isCacheInvalidation()) {
            return true;
        }

        $classname = get_class($class);
        $cache     = $this->getCache();
        $hash      = md5(serialize($class));

        if ($cache) {
            if (!isset($cache["classes"])) {
                $cache["classes"] = array();
            }
            if (key_exists($classname, $cache["classes"])) {
                $cacheHash = $cache["classes"][ $classname ];
                if ($cacheHash == $hash) {
                    return true;
                } else {
                    return $this->update($cache, $classname, $hash);
                }
            } else {
                return $this->update($cache, $classname, $hash);
            }
        }

        throw new Exception( 1, "Cache Invalidation failed -> check()");
    }


    /**
     * saves new class hash to cache
     *
     * @param $cache
     * @param $name
     * @param $hash
     *
     * @throws Exception
     */
    private function update($cache, $name, $hash)
    {
        $cache["classes"][ $name ] = $hash;
        $this->cache               = $cache;

        throw new Exception(1, "Cache Invalidation failed -> update()");
    }
}