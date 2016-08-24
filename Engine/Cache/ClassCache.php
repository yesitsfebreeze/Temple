<?php


namespace Temple\Engine\Cache;


use Temple\Engine\Filesystem\DirectoryHandler;


/**
 * Class ClassCache
 *
 * @package Temple\Engine\Cache
 */
class ClassCache extends Cache
{

    /**
     * @var string $cacheFile
     */
    protected $cacheFile = "cache.classes";
    /**
     * @var DirectoryHandler $DirectoryHandler
     */
    protected $DirectoryHandler;

    /**
     * returns the cache array
     *
     * @return array
     */
    public function getCache()
    {
        $cacheFile = $this->createCacheFile();
        $cache     = unserialize(file_get_contents($cacheFile));

        return $cache;
    }
}