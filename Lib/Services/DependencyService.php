<?php


namespace Temple\Services;


use Temple\BaseClasses\DependencyBaseClass;
use Temple\Engine;
use Temple\Factories\NodeFactory;
use Temple\Factories\PluginFactory;

class DependencyService
{

    /** @var ConfigService $configService */
    private $configService = NULL;

    /** @var CacheService $cacheService */
    private $cacheService = NULL;

    /** @var DirectoryService $directoryService */
    private $directoryService = NULL;

    /** @var PluginInitService $pluginInitService */
    private $pluginInitService = NULL;

    /** @var TemplateService $templateService */
    private $templateService = NULL;

    /** @var LexerService $lexerService */
    private $lexerService = NULL;

    /** @var ParserService $parserService */
    private $parserService = NULL;


    /**
     * Init constructor.
     *
     * @param string $dir
     */
    public function __construct($dir, Engine $engine)
    {
        $this->directoryService  = new DirectoryService();
        $this->configService     = new ConfigService($this->directoryService);
        $this->cacheService      = new CacheService();
        $this->pluginInitService = new PluginInitService();
        $this->pluginFactory     = new PluginFactory();
        $this->nodeFactory       = new NodeFactory();
        $this->templateService   = new TemplateService();
        $this->lexerService      = new LexerService();
        $this->parserService     = new ParserService();

        $this->enrichClasses();

        $this->configService->addConfigFile($dir . "Config.php");
        $this->directoryService->add($dir . "Plugins", "plugins");

        $this->pluginInitService->init($engine);
    }


    private function enrichClasses()
    {
        $this->cacheService      = $this->initClasses($this->cacheService);
        $this->directoryService  = $this->initClasses($this->directoryService);
        $this->pluginInitService = $this->initClasses($this->pluginInitService);
        $this->templateService   = $this->initClasses($this->templateService);
        $this->lexerService      = $this->initClasses($this->lexerService);
        $this->parserService     = $this->initClasses($this->parserService);
    }

    /**
     * @return DependencyBaseClass
     * @param string $service
     */
    public function getClass($service)
    {
        if ($this->$service instanceof DependencyBaseClass) {
            return $this->$service;
        }

        return false;
    }


    /**
     * @param DependencyBaseClass $handler
     * @return mixed
     */
    private function initClasses(DependencyBaseClass $handler)
    {

        $handler->setPluginInitService($this->pluginInitService);
        $handler->setConfigService($this->configService);
        $handler->setCacheService($this->cacheService);
        $handler->setDirectoryService($this->directoryService);
        $handler->setTemplateService($this->templateService);
        $handler->setParserService($this->parserService);
        $handler->setLexerService($this->lexerService);
        $handler->setPluginFactory($this->pluginFactory);
        $handler->setNodeFactory($this->nodeFactory);

        return $handler;
    }


    /**
     * @return ConfigService
     */
    public function getConfigService()
    {
        return $this->configService;
    }


    /**
     * @return CacheService
     */
    public function getCacheService()
    {
        return $this->cacheService;
    }


    /**
     * @return DirectoryService
     */
    public function getDirectoryService()
    {
        return $this->directoryService;
    }


    /**
     * @return PluginInitService
     */
    public function getPluginInitService()
    {
        return $this->pluginInitService;
    }


    /**
     * @return TemplateService
     */
    public function getTemplateService()
    {
        return $this->templateService;
    }


    /**
     * @return LexerService
     */
    public function getLexerService()
    {
        return $this->lexerService;
    }


    /**
     * @return ParserService
     */
    public function getParserService()
    {
        return $this->parserService;
    }

}