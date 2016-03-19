Caramel\Services\Parser
===============

Class Parser




    * Class name: Parser
    * Namespace: Caramel\Services
        * Parent class: [Caramel\Services\Service](#Caramel-Services-Service)
            




    Properties
    ----------


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


    ### parse

    boolean Caramel\Services\Parser::parse(\Caramel\Models\Dom $dom)

    



    * Visibility: **public**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### check

    boolean Caramel\Services\Parser::check(\Caramel\Models\Dom $dom)

    checks if we have a valid dom object



    * Visibility: **private**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### output

    string Caramel\Services\Parser::output(\Caramel\Models\Dom|mixed $dom)

    merges the nodes to a string



    * Visibility: **private**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)|mixed**
        
    

    ### preProcessPlugins

    mixed Caramel\Services\Parser::preProcessPlugins(\Caramel\Models\Dom $dom)

    execute the plugins before we do anything else



    * Visibility: **private**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### processPlugins

    mixed Caramel\Services\Parser::processPlugins(\Caramel\Models\Dom|array $dom, array $nodes)

    execute the plugins on each individual node
children will parsed first



    * Visibility: **private**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)|array**
                    * $nodes **array**
        
    

    ### postProcessPlugins

    mixed Caramel\Services\Parser::postProcessPlugins(\Caramel\Models\Dom $dom)

    process the plugins after the main plugin process



    * Visibility: **private**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### processOutputPlugins

    mixed Caramel\Services\Parser::processOutputPlugins(string $output)

    process the plugins after rendering is complete



    * Visibility: **private**
                

            #### Arguments
                    * $output **string**
        
    

    ### executePlugins

    mixed Caramel\Services\Parser::executePlugins(\Caramel\Models\Dom|\Caramel\Models\Node|string $element, string $type)

    processes all plugins depending on the passed type



    * Visibility: **private**
                

            #### Arguments
                    * $element **[Caramel\Models\Dom](#Caramel-Models-Dom)|[Caramel\Models\Dom](#Caramel-Models-Node)|string**
                    * $type **string**
        
    

    ### PluginError

    mixed Caramel\Services\Parser::PluginError($element, $plugin, $method, $variable)

    helper method for plugin return errors



    * Visibility: **private**
                

            #### Arguments
                    * $element **mixed**
                    * $plugin **mixed**
                    * $method **mixed**
                    * $variable **mixed**
        
    

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
        
    
