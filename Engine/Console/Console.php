<?php


namespace Temple\Engine\Console;


use Temple\Engine\Console\Commands\TestCommand;
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
            file_put_contents($this->cacheFile,serialize($this->cache));
        }

        $this->register(new TestCommand());
    }


    public function register(Command $command)
    {
        $command->define();
        $this->save($command);
    }


    /**
     * remove the class from my cache file
     */
    public function delete()
    {

    }


    /**
     * @param string $name
     * @param array  $args
     */
    public function execute($name, $args)
    {

        $this->cache = $this->getCache();
        $command = $this->cache[$name];

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
            $className = $command->getClassName();
            $name = $command->getName();
            $path = $command->getPath();
            $command = array(
                "className" => $className,
                "path" => $path
            );
            $this->cache[$name] = $command;
        }

        file_put_contents($this->cacheFile,serialize($this->cache));
    }


    /**
     * @return string
     */
    private function getCache()
    {
        return serialize(file_get_contents($this->cacheFile));
    }


}