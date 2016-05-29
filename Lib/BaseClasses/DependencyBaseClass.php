<?php

namespace Temple\BaseClasses;


use Temple\Factories\NodeFactory;
use Temple\Factories\PluginFactory;
use Temple\Services\CacheService;
use Temple\Services\ConfigService;
use Temple\Services\DirectoryService;
use Temple\Services\LexerService;
use Temple\Services\NodeService;
use Temple\Services\ParserService;
use Temple\Services\PluginInitService;
use Temple\Services\TemplateService;

class DependencyBaseClass
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