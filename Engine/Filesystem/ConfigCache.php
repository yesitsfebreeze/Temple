<?php


namespace Temple\Engine\Filesystem;


use Temple\Engine\InjectionManager\Injection;


/**
 * Class ConfigCache
 *
 * @package Temple\Engine\Filesystem
 */
class ConfigCache extends Injection
{

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $cacheFileName
     */
    private $cacheFileName = "cache.configs.php";

    /**
     * @var string $cacheFile
     */
    private $cacheFile;

    /**
     * @var array $cacheFile
     */
    private $cache = array();


    public function __construct()
    {
        $this->path      = __DIR__ . DIRECTORY_SEPARATOR . "../../Cache/";
        $this->cacheFile = $this->path . $this->cacheFileName;

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        if (!file_exists($this->cacheFile)) {
            touch($this->cacheFile);
            file_put_contents($this->cacheFile, serialize($this->cache));
        }
    }


    /**
     * @param string $key
     * @param array  $config
     */
    public function save($key, $config)
    {
        $this->cache         = $this->getCache();
        $this->cache[ $key ] = $config;
        $this->saveCache();
    }


    /**
     * just return the complete cache
     * @return array
     */
    public function getConfigs () {
        return $this->getCache();
    }


    /**
     * @return string
     */
    private function getCache()
    {
        return unserialize(file_get_contents($this->cacheFile));
    }


    /**
     * @return string
     */
    private function saveCache()
    {
        return file_put_contents($this->cacheFile, serialize($this->cache));
    }
}