<?php


namespace Caramel\Services;


use Caramel\Factories\NodeFactory;
use Caramel\Factories\PluginFactory;
use Caramel\Models\ServiceModel;
use Caramel\Repositories\ServiceRepository;

class InitService
{

    /** @var ServiceRepository $services */
    private $services;

    /** @var ConfigService $config */
    private $config = NULL;

    /** @var CacheService $cache */
    private $cache = NULL;

    /** @var DirectoryService $directories */
    private $dirs = NULL;

    /** @var PluginService $plugins */
    private $plugins = NULL;

    /** @var TemplateService $template */
    private $template = NULL;

    /** @var LexerService $lexer */
    private $lexer = NULL;

    /** @var ParserService $parser */
    private $parser = NULL;


    /**
     * Init constructor.
     *
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->dirs = new DirectoryService();
        $this->config = new ConfigService($this->dirs);
        $this->cache = new CacheService();
        $this->pluginFactory = new PluginFactory();
        $this->plugins = new PluginService();
        $this->nodeFactory = new NodeFactory();
        $this->template = new TemplateService();
        $this->lexer = new LexerService();
        $this->parser = new ParserService();

        $this->cache = $this->initServices($this->cache);
        $this->dirs = $this->initServices($this->dirs);
        $this->plugins = $this->initServices($this->plugins);
        $this->template = $this->initServices($this->template);
        $this->lexer = $this->initServices($this->lexer);
        $this->parser = $this->initServices($this->parser);

        $this->config->addConfigFile($dir . "Config.php");
        $this->dirs->add($dir . "Plugins", "plugins");

        $this->services = new ServiceRepository();
        # add everything which should be accessible within a plugin
        $this->services->add("config", $this->config);
        $this->services->add("cache", $this->cache);
        $this->services->add("dirs", $this->dirs);
        $this->services->add("plugins", $this->plugins);
        $this->services->add("template", $this->template);

        $this->plugins->init($this->services);
    }


    /**
     * @return ServiceRepository
     */
    public function getServices()
    {
        return $this->services;
    }


    /**
     * @return ServiceModel
     * @param string $service
     */
    public function getService($service)
    {
        if ($this->$service instanceof ServiceModel) {
            return $this->$service;
        }

        return false;
    }


    /**
     * @param ServiceModel $handler
     * @return mixed
     */
    private function initServices($handler)
    {

        $handler->setPlugins($this->plugins);
        $handler->setConfig($this->config);
        $handler->setCache($this->cache);
        $handler->setDirectories($this->dirs);
        $handler->setTemplate($this->template);
        $handler->setParser($this->parser);
        $handler->setLexer($this->lexer);
        $handler->setPluginFactory($this->pluginFactory);
        $handler->setNodeFactory($this->nodeFactory);

        return $handler;
    }

}