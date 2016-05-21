<?php


namespace Caramel\Services;


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
    private $directories = NULL;

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
        $this->config      = new ConfigService();
        $this->cache       = new CacheService();
        $this->directories = new DirectoryService();
        $this->plugins     = new PluginService();
        $this->template    = new TemplateService();
        $this->lexer       = new LexerService();
        $this->parser      = new ParserService();

        $this->cache = $this->initServices($this->cache);
        $this->directories = $this->initServices($this->directories);
        $this->plugins = $this->initServices($this->plugins);
        $this->template = $this->initServices($this->template);
        $this->lexer = $this->initServices($this->lexer);
        $this->parser = $this->initServices($this->parser);

        $this->config->init($dir);
        $this->directories->add($dir . "plugins", "plugins.dirs");

        $this->services = new ServiceRepository();

        # add everything which should be accessible within a plugin
        $this->services->add("config", $this->config);
        $this->services->add("cache", $this->cache);
        $this->services->add("directories", $this->directories);
        $this->services->add("plugins", $this->plugins);
        $this->services->add("template", $this->template);

        $this->plugins->init($this->services);
    }


    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
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
        $handler->setDirectories($this->directories);
        $handler->setTemplate($this->template);
        $handler->setParser($this->parser);
        $handler->setLexer($this->lexer);

        return $handler;
    }

}