Caramel\Plugin\Plugin
===============

Class Plugin




    * Class name: Plugin
    * Namespace: Caramel\Plugin
    * This is an **abstract** class
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


    ### position

    integer Caramel\Plugin\Plugin::position()

    



    * Visibility: **public**
    * This method is **abstract**.
                

    

    ### getName

    string Caramel\Plugin\Plugin::getName()

    



    * Visibility: **public**
                

    

    ### preProcess

    \Caramel\Models\Dom Caramel\Plugin\Plugin::preProcess(\Caramel\Models\Dom $dom)

    this is called before we even touch a node
so we can add stuff to our config etc



    * Visibility: **public**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### process

    \Caramel\Models\Node Caramel\Plugin\Plugin::process(\Caramel\Models\Node $node)

    the function we should use for processing a node



    * Visibility: **public**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### check

    boolean Caramel\Plugin\Plugin::check($node)

    the function to check if we want to
modify a node



    * Visibility: **public**
                

            #### Arguments
                    * $node **mixed**
        
    

    ### realProcess

    \Caramel\Models\Node Caramel\Plugin\Plugin::realProcess(\Caramel\Models\Node $node)

    processes the actual node
if all requirements are met



    * Visibility: **public**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### postProcess

    \Caramel\Models\Dom Caramel\Plugin\Plugin::postProcess(\Caramel\Models\Dom $dom)

    this is called after the plugins processed
all nodes



    * Visibility: **public**
                

            #### Arguments
                    * $dom **[Caramel\Models\Dom](#Caramel-Models-Dom)**
        
    

    ### processOutput

    string Caramel\Plugin\Plugin::processOutput($output)

    this is called after the plugins processed
all nodes and converted it into a html string



    * Visibility: **public**
                

            #### Arguments
                    * $output **mixed**
        
    

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
        
    
