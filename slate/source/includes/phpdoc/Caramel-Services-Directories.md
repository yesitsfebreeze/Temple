Caramel\Services\Directories
===============

handles the directory creation
Class Directories




    * Class name: Directories
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


    ### add

    boolean Caramel\Services\Directories::add(string $dir, string $name, boolean $create)

    validates and adds a directory to our config
the $name variable will determent the array name
the $single variable will create a simple string instead of an array
note: the directories will be added top down,
so the last added item will be indexed with 0



    * Visibility: **public**
                

            #### Arguments
                    * $dir **string**
                    * $name **string**
                    * $create **boolean**
        
    

    ### get

    array|boolean Caramel\Services\Directories::get($name)

    returns the selected directory/ies



    * Visibility: **public**
                

            #### Arguments
                    * $name **mixed**
        
    

    ### remove

    boolean Caramel\Services\Directories::remove(integer $pos, string $name)

    



    * Visibility: **public**
                

            #### Arguments
                    * $pos **integer**
                    * $name **string**
        
    

    ### forArray

    boolean|string Caramel\Services\Directories::forArray($name, $dirs, $dir, $create)

    



    * Visibility: **private**
                

            #### Arguments
                    * $name **mixed**
                    * $dirs **mixed**
                    * $dir **mixed**
                    * $create **mixed**
        
    

    ### forString

    mixed Caramel\Services\Directories::forString($name, $dirs, $dir, $create)

    



    * Visibility: **private**
                

            #### Arguments
                    * $name **mixed**
                    * $dirs **mixed**
                    * $dir **mixed**
                    * $create **mixed**
        
    

    ### create

    mixed Caramel\Services\Directories::create(boolean $create, string $dir)

    



    * Visibility: **private**
                

            #### Arguments
                    * $create **boolean**
                    * $dir **string**
        
    

    ### path

    string Caramel\Services\Directories::path($dir)

    checks if we have a relative or an absolute directory
and returns the adjusted directory



    * Visibility: **private**
                

            #### Arguments
                    * $dir **mixed**
        
    

    ### validate

    string Caramel\Services\Directories::validate($dir)

    checks if the passed directory exists



    * Visibility: **private**
                

            #### Arguments
                    * $dir **mixed**
        
    

    ### root

    string Caramel\Services\Directories::root()

    gets the current document root



    * Visibility: **private**
                

    

    ### framework

    array|string Caramel\Services\Directories::framework()

    Returns the Caramel Directory



    * Visibility: **private**
                

    

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
        
    
