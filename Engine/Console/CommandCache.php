<?php


namespace Temple\Engine\Console;


use Temple\Engine\InjectionManager\Injection;


/**
 * Class CommandCache
 *
 * @package Temple\Engine\Console
 */
class CommandCache extends Injection
{

    /** @var string $path */
    private $path;

    /**  @var string $cacheFileName */
    private $cacheFileName = "cache.commands.php";

    /** @var string $cacheFile */
    private $cacheFile;

    /** @var array $cache */
    private $cache = array();


    /**
     * CommandCache constructor.
     */
    public function __construct()
    {

        $this->path = __DIR__ . DIRECTORY_SEPARATOR . "../../Cache/";

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
     * @param $name
     *
     * @return array
     */
    public function findCommands($name)
    {
        $cache = $this->getCache();
        $list  = array();
        foreach ($cache as $realCommandName => $unneeded) {
            $groupName   = explode(":", $name);
            $groupName   = reset($groupName);
            $commandName = explode(":", $realCommandName);
            $commandName = reset($commandName);
            if ($commandName == $groupName) {
                $list[] = $realCommandName;
            }
        }

        return $list;
    }


    /**
     * saves the command within the command cache
     *
     * @param Command $command
     */
    public function save(Command $command = null)
    {
        $this->cache = $this->getCache();

        if ($command instanceof Command) {
            $className            = $command->getClassName();
            $name                 = $command->getName();
            $path                 = $command->getPath();
            $command              = array(
                "className" => $className,
                "path"      => $path
            );
            $this->cache[ $name ] = $command;
        }

        $this->saveCache();
    }


    /**
     * remove the class from my cache file
     *
     * @param string $name
     *
     * @return bool
     */
    public function remove($name)
    {
        $this->cache = $this->getCache();

        if (isset($this->cache[ $name ])) {
            unset($this->cache[ $name ]);
        }

        $this->saveCache();

        return true;
    }


    /**
     * @return array
     */
    public function getCache()
    {
        return unserialize(file_get_contents($this->cacheFile));
    }


    /**
     * @return string
     */
    public function saveCache()
    {
        return file_put_contents($this->cacheFile, serialize($this->cache));
    }


}
