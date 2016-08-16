<?php


namespace Temple\Engine\Console;


use Temple\Engine\Console\Commands\ClearCacheCommand;
use Temple\Engine\InjectionManager\Injection;


/**
 * Class Console
 *
 * @package Temple\Engine\Console
 */
class Console extends Injection
{

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $cacheFileName
     */
    private $cacheFileName = "commandCache.php";

    /**
     * @var string $cacheFile
     */
    private $cacheFile;

    /**
     * @var array $cacheFile
     */
    private $cache = array();


    /**
     * registers all default console commands
     * Console constructor.
     */
    public function __construct()
    {

        $this->path      = __DIR__ . DIRECTORY_SEPARATOR;
        $this->cacheFile = $this->path . $this->cacheFileName;

        if (!file_exists($this->cacheFile)) {
            touch($this->cacheFile);
            file_put_contents($this->cacheFile, serialize($this->cache));
        }
        $this->registerDefaultCommands();
    }


    private function registerDefaultCommands()
    {
        $this->register(new ClearCacheCommand());
    }


    public function register(Command $command)
    {
        $command->define();
        $this->save($command);
    }


    /**
     * remove the class from my cache file
     *
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        $this->cache = $this->getCache();

        if (isset($this->cache[ $name ])) {
            unset($this->cache[ $name ]);
        }

        $this->saveCache();

        return true;
    }


    /**
     * @param string $name
     * @param array  $args
     *
     * @throws \Exception
     */
    public function execute($name, $args)
    {
        $this->cache = $this->getCache();

        if (!isset($this->cache[ $name ])) {
            throw new \Exception("The console Command " . $name . " wasn't found!");
        }
        $command = $this->cache[ $name ];

        /** @noinspection PhpIncludeInspection */
        require_once($command["path"]);

        /** @var Command $command */
        $command = new $command["className"](...$args);

        $command->execute();


    }


    /**
     * saves the command within the command cache
     *
     * @param Command $command
     */
    private function save(Command $command = null)
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