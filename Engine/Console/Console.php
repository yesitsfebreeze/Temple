<?php


namespace Temple\Engine\Console;


use Temple\Engine\Console\Commands\ClearCacheCommand;
use Temple\Engine\Console\Commands\TestCommand;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\ConfigCache;
use Temple\Engine\InjectionManager\Injection;


/**
 * Class Console
 *
 * @package Temple\Engine\Console
 */
class Console extends Injection
{

    /** @var CliOutput $CliOutput */
    protected $CliOutput;

    /** @var ConfigCache $ConfigCache */
    private $ConfigCache;

    /** @var string $path */
    private $path;

    /**  @var string $cacheFileName */
    private $cacheFileName = "cache.commands.php";

    /** @var string $cacheFile */
    private $cacheFile;

    /** @var array $cacheFile */
    private $cache = array();


    /**
     * registers all default console commands
     * Console constructor.
     */
    public function __construct()
    {

        $this->ConfigCache = new ConfigCache();
        $this->CliOutput   = new CliOutput(new CliColors());

        $this->path      = __DIR__ . DIRECTORY_SEPARATOR . "../../Cache/";
        $this->cacheFile = $this->path . $this->cacheFileName;

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        if (!file_exists($this->cacheFile)) {
            touch($this->cacheFile);
            file_put_contents($this->cacheFile, serialize($this->cache));
        }

        $this->registerDefaultCommands();
    }


    /**
     * register all default commands
     */
    private function registerDefaultCommands()
    {
        $this->addCommand(new ClearCacheCommand());
        $this->addCommand(new TestCommand());
    }


    /**
     * @param Command $command
     */
    public function addCommand(Command $command)
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
    public function removeCommand($name)
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
     * @throws Exception
     */
    public function execute($name, $args)
    {

        $this->cache = $this->getCache();

        if (!isset($this->cache[ $name ])) {
            throw new Exception(600, "The console Command " . $name . " wasn't found!");
        }
        $command = $this->cache[ $name ];

        /** @noinspection PhpIncludeInspection */
        require_once($command["path"]);

        /** @var Command $command */
        $command = new $command["className"]();


        if (isset($args[0]) && ($args[0] == "-h" || $args[0] == "--help")) {
            $this->CliOutput->clearBuffer();
            $command->setCliOutput($this->CliOutput);
            $command->getHelp();

            $this->CliOutput->outputBuffer();

            return;
        }

        $command->define();

        /**
         * foreach cached config call the execute function
         */

        $configs = $this->ConfigCache->getConfigs();

        $CliProgress = null;
        if ($command->isUseProgress()) {
            $CliProgress = new CliProgress();
            $command->setCliProgress($CliProgress);
            $CliProgress->addTask(sizeof($configs));
            $CliProgress->start();
        }

        foreach ($configs as $config) {
            $command->setConfig($config);
            $this->CliOutput->clearBuffer();
            $command->setCliOutput($this->CliOutput);
            $command->execute(...$args);
            if (!is_null($CliProgress)) {
                $CliProgress->removeTask(1);
                $CliProgress->update();
            }

        }

        if (!is_null($CliProgress)) {
            $CliProgress->update();
        }

        $command->after();

        $this->CliOutput->outputBuffer();

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