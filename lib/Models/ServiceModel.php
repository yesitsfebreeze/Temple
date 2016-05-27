<?php

namespace Caramel\Models;


use Caramel\Factories\NodeFactory;
use Caramel\Factories\PluginFactory;
use Caramel\Services\CacheService;
use Caramel\Services\ConfigService;
use Caramel\Services\DirectoryService;
use Caramel\Services\LexerService;
use Caramel\Services\NodeService;
use Caramel\Services\ParserService;
use Caramel\Services\PluginInitService;
use Caramel\Services\TemplateService;

class ServiceModel
{

    /** @var CacheService $cacheService */
    public $cacheService = NULL;

    /** @var ConfigService $configService */
    public $configService = NULL;

    /** @var DirectoryService $directoryService */
    public $directoryService = NULL;

    /** @var PluginFactory $pluginFactory */
    public $pluginFactory = NULL;

    /** @var PluginInitService $pluginInitService */
    public $pluginInitService = NULL;

    /** @var NodeFactory $nodeFactory */
    public $nodeFactory = NULL;

    /** @var TemplateService $templateService */
    public $templateService = NULL;

    /** @var LexerService $lexerService */
    public $lexerService = NULL;

    /** @var ParserService $parserService */
    public $parserService = NULL;


    /**
     * @param CacheService $cacheService
     */
    public function setCacheService($cacheService)
    {
        $this->cacheService = $cacheService;
    }


    /**
     * @param ConfigService $configService
     */
    public function setConfigService($configService)
    {
        $this->configService = $configService;
    }


    /**
     * @param DirectoryService $directoryService
     */
    public function setDirectoryService($directoryService)
    {
        $this->directoryService = $directoryService;
    }


    /**
     * @param PluginInitService $pluginInitService
     */
    public function setPluginInitService($pluginInitService)
    {
        $this->pluginInitService = $pluginInitService;
    }


    /**
     * @param PluginFactory $pluginFactory
     */
    public function setPluginFactory($pluginFactory)
    {
        $this->pluginFactory = $pluginFactory;
    }


    /**
     * @param NodeFactory $nodeFactory
     */
    public function setNodeFactory($nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }


    /**
     * @param TemplateService $templateService
     */
    public function setTemplateService($templateService)
    {
        $this->templateService = $templateService;
    }


    /**
     * @param LexerService $lexerService
     */
    public function setLexerService($lexerService)
    {
        $this->lexerService = $lexerService;
    }


    /**
     * @param ParserService $parserService
     */
    public function setParserService($parserService)
    {
        $this->parserService = $parserService;
    }


}