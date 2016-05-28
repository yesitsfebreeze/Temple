<?php


namespace Temple\Services;


use Temple\Factories\NodeFactory;
use Temple\Factories\PluginFactory;
use Temple\Models\ServiceModel;
use Temple\Repositories\ServiceRepository;

class InitService
{

    /** @var ServiceRepository $serviceRepository */
    private $serviceRepository;

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
    public function __construct($dir)
    {
        $this->directoryService = new DirectoryService();
        $this->configService = new ConfigService($this->directoryService);
        $this->cacheService = new CacheService();
        $this->pluginFactory = new PluginFactory();
        $this->pluginInitService = new PluginInitService();
        $this->nodeFactory = new NodeFactory();
        $this->templateService = new TemplateService();
        $this->lexerService = new LexerService();
        $this->parserService = new ParserService();

        $this->cacheService = $this->initServices($this->cacheService);
        $this->directoryService = $this->initServices($this->directoryService);
        $this->pluginInitService = $this->initServices($this->pluginInitService);
        $this->templateService = $this->initServices($this->templateService);
        $this->lexerService = $this->initServices($this->lexerService);
        $this->parserService = $this->initServices($this->parserService);

        $this->configService->addConfigFile($dir . "Config.php");
        $this->directoryService->add($dir . "Plugins", "plugins");

        $this->serviceRepository = new ServiceRepository();
        # add everything which should be accessible within a plugin
        $this->serviceRepository->add("configService", $this->configService);
        $this->serviceRepository->add("cacheService", $this->cacheService);
        $this->serviceRepository->add("directoryService", $this->directoryService);
        $this->serviceRepository->add("pluginInitService", $this->pluginInitService);
        $this->serviceRepository->add("templateService", $this->templateService);

        $this->pluginInitService->init($this->serviceRepository);
    }


    /**
     * @return ServiceRepository
     */
    public function getServices()
    {
        return $this->serviceRepository;
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
    private function initServices(ServiceModel $handler)
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

}