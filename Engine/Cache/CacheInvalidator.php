<?php

namespace Temple\Engine\Cache;


use Temple\Engine\EventManager\Event;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\LanguageConfig;


class CacheInvalidator extends Injection
{

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var  EventCache $EventCache */
    protected $EventCache;


    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager" => "EventManager",
            "Engine/Cache/EventCache"          => "EventCache"
        );
    }


    /**
     * checks the validation of a list of predefined classes
     *
     * @param LanguageConfig $LanguageConfig
     * @param int            $identifier
     *
     * @return bool
     */
    public function checkValidation(LanguageConfig $LanguageConfig, $identifier = 0)
    {

        // this checks if any registered event
        // within the current language has changed
        $events = $this->EventManager->getEvents($LanguageConfig->getName());
        $events = $this->checkEventCache($events, $identifier);
        var_dump($events);
        if ($events) {
            return true;
        }


        return false;
    }


    /**
     * this checks if any of the language registered events has changed
     *
     * @param      $events
     * @param int  $identifier
     * @param bool $changed
     *
     * @return bool
     */
    private function checkEventCache($events, $identifier = 0, $changed = false)
    {
        if ($changed) {
            $returnValue = true;
        }

        if (is_array($events)) {
            foreach ($events as $event) {
                $returnValue = $this->checkEventCache($event, $identifier, $changed);
                var_dump($returnValue);
            }
        } else if ($events instanceof Event) {

            $returnValue = $this->EventCache->changed($events);
            var_dump($returnValue);
            if ($returnValue) {
                $TemplateCache = new TemplateCache();
                $cacheFile     = $TemplateCache->getCacheFile();
                unlink($cacheFile);
            }

            $this->EventCache->save($events);
        }

        return $returnValue;
    }


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