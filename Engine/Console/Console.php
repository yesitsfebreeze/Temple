<?php


namespace Temple\Engine\Console;


use Temple\Engine\Cache\CommandCache;
use Temple\Engine\Console\Commands\CacheClearCommandsCommand;
use Temple\Engine\Console\Commands\CacheClearCompleteCommand;
use Temple\Engine\Console\Commands\CacheClearConfigsCommand;
use Temple\Engine\Console\Commands\CacheClearTemplatesCommand;
use Temple\Engine\Console\Commands\CacheBuildTemplatesCommand;
use Temple\Engine\Console\Commands\CurlUrlsCommand;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Cache\ConfigCache;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Storage;


/**
 * Class Console
 *
 * @package Temple\Engine\Console
 */
class Console extends Injection
{

    /** @var CliOutput $CliOutput */
    protected $CliOutput;

    /** @var CliProgress $CliProgress */
    protected $CliProgress;

    /** @var ConfigCache $ConfigCache */
    private $ConfigCache;

    /** @var CommandCache $CommandCache */
    private $CommandCache;


    /**
     * registers all default console commands
     * Console constructor.
     */
    public function __construct()
    {
        $this->ConfigCache  = new ConfigCache();
        $this->CommandCache = new CommandCache();
        $this->CliOutput    = new CliOutput(new CliColors());

        $this->registerDefaultCommands();
    }


    /**
     * registers all default commands
     */
    private function registerDefaultCommands()
    {
        $this->addCommand(new CacheClearCompleteCommand());
        $this->addCommand(new CacheClearCommandsCommand());
        $this->addCommand(new CacheClearConfigsCommand());
        $this->addCommand(new CacheClearTemplatesCommand());
        $this->addCommand(new CacheBuildTemplatesCommand());
        $this->addCommand(new CurlUrlsCommand());
    }


    /**
     * @param Command $command
     */
    public function addCommand(Command $command)
    {
        $command->define();
        $this->CommandCache->save($command);
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
        return $this->CommandCache->remove($name);
    }


    /**
     * @param string $name
     * @param array  $args
     *
     * @return bool
     * @throws Exception
     */
    public function execute($name, $args)
    {

        $cache = $this->CommandCache->getCache();

        if (!isset($cache[ $name ])) {
            return $this->missingCommand($name);
        } else {
            $command = $cache[ $name ];
            /** @noinspection PhpIncludeInspection */
            require_once($command["path"]);
            /** @var Command $command */
            $command = new $command["className"]();
        }


        if (isset($args[0]) && ($args[0] == "-h" || $args[0] == "--help")) {
            return $this->showHelp($command);
        }

        return $this->executeCommand($command, $args);

    }


    /**
     * @param $name
     *
     * @return bool
     */
    private function missingCommand($name)
    {
        $this->CliOutput->writeln("The console Command " . $name . " wasn't found!", "red");

        $commands = $this->CommandCache->findCommands($name);

        if (sizeof($commands) > 0) {
            $this->CliOutput->writeln("Did you mean one of these:", "red");
            $this->CliOutput->writeln("\n");
            foreach ($commands as $command) {
                $this->CliOutput->writeln($command, "white", "red");
            }
            $this->CliOutput->writeln("\n");
        }

        $this->CliOutput->outputBuffer();

        return false;
    }


    /**
     * @param Command $command
     *
     * @return bool
     */
    private function showHelp(Command $command)
    {
        $this->CliOutput->clearBuffer();
        $command->setCliOutput($this->CliOutput);
        $command->getHelp();

        $this->CliOutput->outputBuffer();

        return false;
    }


    /**
     * @param Command $command
     * @param         $args
     *
     * @return bool
     */
    private function executeCommand(Command $command, $args)
    {
        $command->define();

        $configs = $this->ConfigCache->getCache();
        $this->prepareProgress($command, $configs);

        # add storage to store persistent data
        $command->setStorage(new Storage());

        $command = $this->prepareCommand($command);

        if ($command->isUseConfigs()) {
            # execute the command for each cached config
            foreach ($configs as $config) {
                $command = $this->prepareCommand($command);
                $command = $this->callCommandExecute($command, $args, $config);
            }
        } else {
            $command = $this->callCommandExecute($command, $args);
        }

        if (!is_null($this->CliProgress) && !$command->isUseConfigs()) {
            $this->CliProgress->update();
        }

        $command->after();

        $this->CliOutput->outputBuffer();

        return true;
    }


    /**
     * @param Command $command
     * @param         $args
     * @param null    $config
     *
     * @return Command $command
     */
    private function callCommandExecute(Command $command, $args, $config = null)
    {

        $config = unserialize($config);

        if (!is_null($config)) {
            $command->setConfig($config);
        }
        $command->execute(...$args);

        if (!is_null($config) && !is_null($this->CliProgress)) {
            $this->CliProgress->removeTask(1);
            $this->CliProgress->update();
        }

        return $command;
    }


    /**
     * @param Command $command
     *
     * @return Command
     */
    private function prepareCommand(Command $command)
    {
        $this->CliOutput->clearBuffer();
        $command->setCliOutput($this->CliOutput);

        return $command;
    }


    /**
     * @param Command $command
     * @param array   $configs
     */
    private function prepareProgress(Command $command, $configs)
    {

        if ($command->isUseProgress() && !$command->isUseConfigs()) {
            $this->CliOutput->writeln("Error in: " . get_class($command), "red");
            $this->CliOutput->writeln("Can't use Progress without configs!", "red");
            $this->CliOutput->outputBuffer();

            die();
        }


        if ($command->isUseProgress() && $command->isUseConfigs()) {

            $this->CliProgress = new CliProgress();
            $this->CliProgress->setProgressTitle($command->getProgressTitle());
            $this->CliProgress->setProgressTitleColor($command->getProgressTitleColor());
            $this->CliProgress->setProgressTitleBackground($command->getProgressTitleBackground());

            $command->setCliProgress($this->CliProgress);

            if (sizeof($configs) == 0) {
                $this->CliOutput->writeln("No configs found!", "red");
                $this->CliOutput->outputBuffer();
                die();
            }
            $this->CliProgress->addTask(sizeof($configs));

            $this->CliProgress->start();
        } else {
            $this->CliProgress = null;
        }

    }
}