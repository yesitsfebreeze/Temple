<?php

namespace Temple\Engine\Cache;


use Temple\Engine\Config;


/**
 * Class ConfigCache
 *
 * @package Temple\Engine\Cache
 */
class ConfigCache extends BaseCache
{
    protected $cacheFile = "config.cache";


    /**
     * @param     $value
     * @param int $identifier
     *
     * @return bool
     */
    public function changed($value, $identifier = 0)
    {
        $cached = unserialize($this->get($value, $identifier));
        if (sizeof($cached) == 0) {
            return true;
        }
        if (!isset($cached["static"])) {
            return true;
        }
        $cached = $cached["static"];
        /** @var Config $value */
        $uncached = $value->toArray();
        $uncached = $uncached["static"];

        return $cached !== $uncached;
    }


    /**
     * @param null $value
     * @param int  $identifier
     *
     * @return array|mixed|null
     */
    public function get($value = null, $identifier = 0)
    {
        $cache = $this->getCache();
        if (isset($cache[ $identifier ])) {
            return $cache[ $identifier ];
        }

        return null;
    }


    /**
     * @param       $value
     * @param  int  $identifier
     */
    public function save($value, $identifier = 0)
    {
        if (!is_null($value)) {
            $cache = $this->getCache();

            if (!isset($cache[ $identifier ])) {
                $cache[ $identifier ] = array();
            }

            $cache[ $identifier ] = $this->realSave($value);

            $this->saveCache($cache);
        }
    }

}