<?php

namespace Temple\Engine\Cache;


use Temple\Engine\InjectionManager\Injection;


class CacheInvalidator extends Injection
{

//    /** @var  array $tempCache */
//    private $cache;
//
//    protected $cacheFile = "object.cache";
//
//
//    /**
//     * checks the validation of a list of predefined classes
//     */
//    public function checkValidation()
//    {
//        try {
//            $this->classesToCheck();
//        } catch (Exception $e) {
//            $this->invalidateCacheFile();
//            $this->saveCache($this->cache);
//        }
//    }
//
//
//    /**
//     * all classes which are need to be checked
//     */
//    private function classesToCheck()
//    {
//        $this->check($this->Config);
//    }
//
//
//    /**
//     * @param Injection $class
//     *
//     * @throws Exception
//     * @return bool
//     */
//    public function check(Injection $class)
//    {
//
//        if (!$this->Config->isCacheInvalidation()) {
//            return true;
//        }
//
//        $classname = get_class($class);
//        $cache     = $this->getCache();
//
//        if ($cache) {
//            if (!isset($cache["classes"])) {
//                $cache["classes"] = array();
//            }
//            if (key_exists($classname, $cache["classes"])) {
//                $cacheClass = $cache["classes"][ $classname ];
//                if ($class === $cacheClass) {
//                    return true;
//                } else {
////                    return $this->update($cache, $classname, $hash);
//                }
//            } else {
////                return $this->update($cache, $classname, $hash);
//            }
//        }
//
//        throw new Exception( 1, "Cache Invalidation failed -> check()");
//    }
//
//
//    /**
//     * saves new class hash to cache
//     *
//     * @param $cache
//     * @param $name
//     * @param $hash
//     *
//     * @throws Exception
//     */
//    private function update($cache, $name, $hash)
//    {
//        $cache["classes"][ $name ] = $hash;
//        $this->cache               = $cache;
//
//        throw new Exception(1, "Cache Invalidation failed -> update()");
//    }
}