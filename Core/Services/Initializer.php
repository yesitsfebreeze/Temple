<?php

namespace Caramel\Services;


use Caramel\Caramel;
use Caramel\Models\Config;
use Caramel\Models\Vars;

class Initializer
{

    /**
     * initiates the Caramel Config
     *
     * @param Config $config
     * @param        $dir
     * @throws \Caramel\Exceptions\CaramelException
     */
    public function initConfig(Config $config, $dir)
    {
        $config->addConfigFile($dir . "/config.json");
        $config->setDefaults($dir);
    }


    /**
     * initiates the Caramel Directories
     *
     * @param Directories $directories
     * @param Config      $config
     */
    public function initDirectories(Directories $directories, Config $config)
    {
        $directories->setConfig($config);
    }


    /**
     * initiates the Caramel Cache
     *
     * @param Cache       $cache
     * @param Config      $config
     * @param Template    $template
     * @param Directories $directories
     */
    public function initCache(Cache $cache, Config $config, Template $template, Directories $directories)
    {
        $cache->setConfig($config);
        $cache->setTemplate($template);
        $cache->setDirectories($directories);
    }


    /**
     * initiates the Caramel Container
     *
     * @param Containers $containers
     * @param Config     $config
     */
    public function initContainers(Containers $containers, Config $config)
    {
        $containers->setConfig($config);
    }


    /**
     * initiates the Caramel Lexer
     *
     * @param Lexer    $lexer
     * @param Config   $config
     * @param Template $template
     */
    public function initLexer(Lexer $lexer, Config $config, Template $template)
    {
        $lexer->setConfig($config);
        $lexer->setTemplate($template);
    }


    /**
     * initiates the Caramel Plugins
     *
     * @param Plugins     $plugins
     * @param Vars        $vars
     * @param Config      $config
     * @param Directories $directories
     * @param Cache       $cache
     * @param Lexer       $lexer
     * @param Parser      $parser
     * @param Template    $template
     * @throws \Caramel\Exceptions\CaramelException
     */
    public function initPlugins(Plugins $plugins, Vars $vars, Config $config, Directories $directories, Cache $cache, Lexer $lexer, Parser $parser, Template $template)
    {
        $plugins->setVars($vars);
        $plugins->setConfig($config);
        $plugins->setDirectories($directories);
        $plugins->setCache($cache);
        $plugins->setLexer($lexer);
        $plugins->setParser($parser);
        $plugins->setTemplate($template);
        $pluginDir = $config->get("framework_dir") . "../Plugins";
        $plugins->addPluginDir($pluginDir);
    }


    /**
     * initiates the Caramel Parser
     *
     * @param Parser  $parser
     * @param Config  $config
     * @param Cache   $cache
     * @param Plugins $plugins
     */
    public function initParser(Parser $parser, Config $config, Cache $cache, Plugins $plugins)
    {
        $parser->setConfig($config);
        $parser->setCache($cache);
        $parser->setPlugins($plugins);
    }


    /**
     * initiates the Caramel Template
     *
     * @param Template    $template
     * @param Config      $config
     * @param Cache       $cache
     * @param Directories $directories
     * @param Lexer       $lexer
     * @param Parser      $parser
     * @param Caramel     $caramel
     * @param Plugins     $plugins
     */
    public function initTemplate(Template $template, Config $config, Cache $cache, Directories $directories, Lexer $lexer, Parser $parser, Caramel $caramel, Plugins $plugins)
    {
        $template->setConfig($config);
        $template->setCache($cache);
        $template->setDirectories($directories);
        $template->setLexer($lexer);
        $template->setParser($parser);
        $template->setCaramel($caramel);
        $template->setPlugins($plugins);
    }
}