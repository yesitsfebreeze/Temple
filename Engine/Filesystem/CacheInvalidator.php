<?php

namespace Underware\Engine\Filesystem;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;


class CacheInvalidator extends Cache
{


    /**
     * checks the validation of a list of predefined classes
     */
    public function checkValidation()
    {
        try {
            $this->classesToCheck();
        } catch (Exception $e) {
            $this->invalidate();
        }
    }


    /**
     * all classes which are need to be checked
     */
    private function classesToCheck()
    {
        $this->check($this->Config);
    }


    /**
     * @param Injection $class
     *
     * @return bool
     */
    public function check(Injection $class)
    {

        $classname = get_class($class);
        $cache     = $this->getCache();
        $hash      = md5(serialize($this->Config));

        if ($cache) {
            if (key_exists($classname, $cache)) {

                $currentHash = $cache[ $classname ];
                if ($currentHash == $hash) {
                    return true;
                }

                return $this->update($cache, $classname, $hash);

            } else {
                return $this->update($cache, $classname, $hash);
            }
        }

        return false;
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
        $cache[ $name ] = $hash;
        $this->saveCache($cache);

        throw new Exception(1,"Cache Invalidation failed");
    }
}