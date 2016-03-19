Caramel\Services\Plugins
===============

handles the plugin loading
Class PluginLoader




    * Class name: Plugins
    * Namespace: Caramel\Services
        * Parent class: [Caramel\Services\Service](#Caramel-Services-Service)
            




    Properties
    ----------


    ### $list

    private array $list = array()

    



    * Visibility: **private**
            

    ### $caramel

    protected \Caramel\Caramel $caramel

    



    * Visibility: **protected**
            

    ### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml

    



    * Visibility: **protected**
            

    ### $vars

    protected \Caramel\Models\Vars $vars

    



    * Visibility: **protected**
            

    ### $config

    protected \Caramel\Services\Config $config

    



    * Visibility: **protected**
            

    ### $directories

    protected \Caramel\Services\Directories $directories

    



    * Visibility: **protected**
            

    ### $helpers

    protected \Caramel\Services\Helpers $helpers

    



    * Visibility: **protected**
            

    ### $cache

    protected \Caramel\Services\Cache $cache

    



    * Visibility: **protected**
            

    ### $plugins

    protected \Caramel\Services\Plugins $plugins

    



    * Visibility: **protected**
            

    ### $lexer

    protected \Caramel\Services\Lexer $lexer

    



    * Visibility: **protected**
            

    ### $parser

    protected \Caramel\Services\Parser $parser

    



    * Visibility: **protected**
            

    ### $template

    protected \Caramel\Services\Template $template

    



    * Visibility: **protected**
            

    Methods
    -------


    ### init

    mixed Caramel\Services\Plugins::init()

    initiates the plugins



    * Visibility: **public**
                

    

    ### add

    string Caramel\Services\Plugins::add($dir)

    adds a plugin directory



    * Visibility: **public**
                

            #### Arguments
                    * $dir **mixed**
        
    

    ### remove

    string Caramel\Services\Plugins::remove(integer $pos)

    removes a plugin dir



    * Visibility: **public**
                

            #### Arguments
                    * $pos **integer**
        
    

    ### dirs

    array Caramel\Services\Plugins::dirs()

    returns all plugin dirs



    * Visibility: **public**
                

    

    ### container

    mixed Caramel\Services\Plugins::container($name, $plugins)

    adds a new container to the plugins configuration



    * Visibility: **public**
                

            #### Arguments
                    * $name **mixed**
                    * $plugins **mixed**
        
    

    ### getPlugins

    mixed Caramel\Services\Plugins::getPlugins()

    gets all registered plugins



    * Visibility: **private**
                

    

    ### loadPlugins

    mixed Caramel\Services\Plugins::loadPlugins($pluginFile)

    



    * Visibility: **private**
                

            #### Arguments
                    * $pluginFile **mixed**
        
    

    ### loadPlugin

    mixed Caramel\Services\Plugins::loadPlugin(string $file)

    loads all plugins



    * Visibility: **private**
                

            #### Arguments
                    * $file **string**
        
    

    ### getPluginName

    string Caramel\Services\Plugins::getPluginName(string $file)

    extracts the plugin name



    * Visibility: **private**
                

            #### Arguments
                    * $file **string**
        
    

    ### createPlugin

    \Caramel\Plugin\Plugin Caramel\Services\Plugins::createPlugin(string $class)

    creates a new plugin instance with the given class



    * Visibility: **private**
                

            #### Arguments
                    * $class **string**
        
    

    ### addPlugin

    array Caramel\Services\Plugins::addPlugin($position, $plugin)

    



    * Visibility: **private**
                

            #### Arguments
                    * $position **mixed**
                    * $plugin **mixed**
        
    

    ### setCaramel

    mixed Caramel\Services\Service::setCaramel(\Caramel\Caramel $Caramel)

    sets $this->caramel



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Caramel **Caramel\Caramel**
        
    

    ### setYaml

    mixed Caramel\Services\Service::setYaml(\Symfony\Component\Yaml\Yaml $Yaml)

    sets $this->yaml



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Yaml **Symfony\Component\Yaml\Yaml**
        
    

    ### setVars

    mixed Caramel\Services\Service::setVars(\Caramel\Models\Vars $Vars)

    sets $this->vars



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Vars **[Caramel\Models\Vars](#Caramel-Models-Vars)**
        
    

    ### setConfig

    mixed Caramel\Services\Service::setConfig(\Caramel\Services\Config $Config)

    sets $this->config



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Config **[Caramel\Services\Config](#Caramel-Services-Config)**
        
    

    ### setDirectories

    mixed Caramel\Services\Service::setDirectories(\Caramel\Services\Directories $Directories)

    sets $this->directories



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Directories **[Caramel\Services\Directories](#Caramel-Services-Directories)**
        
    

    ### setHelpers

    mixed Caramel\Services\Service::setHelpers(\Caramel\Services\Helpers $Helpers)

    sets $this->helpers



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Helpers **[Caramel\Services\Helpers](#Caramel-Services-Helpers)**
        
    

    ### setCache

    mixed Caramel\Services\Service::setCache(\Caramel\Services\Cache $Cache)

    sets $this->cache



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Cache **[Caramel\Services\Cache](#Caramel-Services-Cache)**
        
    

    ### setPlugins

    mixed Caramel\Services\Service::setPlugins(\Caramel\Services\Plugins $Plugins)

    sets $this->plugins



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Plugins **[Caramel\Services\Plugins](#Caramel-Services-Plugins)**
        
    

    ### setLexer

    mixed Caramel\Services\Service::setLexer(\Caramel\Services\Lexer $Lexer)

    sets $this->lexer



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Lexer **[Caramel\Services\Lexer](#Caramel-Services-Lexer)**
        
    

    ### setParser

    mixed Caramel\Services\Service::setParser(\Caramel\Services\Parser $Parser)

    sets $this->parser



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Parser **[Caramel\Services\Parser](#Caramel-Services-Parser)**
        
    

    ### setTemplate

    mixed Caramel\Services\Service::setTemplate(\Caramel\Services\Template $Template)

    sets $this->template



    * Visibility: **public**
                * This method is defined by [Caramel\Services\Service](#Caramel-Services-Service)
    

            #### Arguments
                    * $Template **[Caramel\Services\Template](#Caramel-Services-Template)**
        
    
