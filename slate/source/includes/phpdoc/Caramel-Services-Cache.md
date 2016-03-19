Caramel\Services\Cache
===============

Class Cache




    * Class name: Cache
    * Namespace: Caramel\Services
        * Parent class: [Caramel\Services\Service](#Caramel-Services-Service)
            




    Properties
    ----------


    ### $cacheFile

    private string $cacheFile = "__cache.php"

    



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


    ### set

    string Caramel\Services\Cache::set(string $dir)

    sets the cache directory



    * Visibility: **public**
                

            #### Arguments
                    * $dir **string**
        
    

    ### save

    string Caramel\Services\Cache::save($file, $content)

    saves a file to the cache



    * Visibility: **public**
                

            #### Arguments
                    * $file **mixed**
                    * $content **mixed**
        
    

    ### modified

    boolean Caramel\Services\Cache::modified($file)

    returns if a file is modified



    * Visibility: **public**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### dependency

    boolean Caramel\Services\Cache::dependency(string $parent, string $file)

    adds a dependency to the cache



    * Visibility: **public**
                

            #### Arguments
                    * $parent **string**
                    * $file **string**
        
    

    ### setTime

    boolean Caramel\Services\Cache::setTime($file)

    writes the modify times for the current template
into our cache file



    * Visibility: **private**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### getCache

    array Caramel\Services\Cache::getCache()

    returns the cache array



    * Visibility: **private**
                

    

    ### saveCache

    boolean Caramel\Services\Cache::saveCache(array $cache)

    saves the array to the cache



    * Visibility: **private**
                

            #### Arguments
                    * $cache **array**
        
    

    ### getPath

    string Caramel\Services\Cache::getPath($file)

    returns the cache path for the given file



    * Visibility: **public**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### extension

    mixed|string Caramel\Services\Cache::extension($file)

    adds a php extension to the files path



    * Visibility: **private**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### createFile

    mixed|string Caramel\Services\Cache::createFile($file)

    creates the file if its not already there



    * Visibility: **private**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### clear

    boolean|\Caramel\Services\Error Caramel\Services\Cache::clear(boolean $dir)

    empties the cache directory



    * Visibility: **public**
                

            #### Arguments
                    * $dir **boolean**
        
    

    ### clean

    string Caramel\Services\Cache::clean($file)

    removes the template dirs and the extension form a file path



    * Visibility: **private**
                

            #### Arguments
                    * $file **mixed**
        
    

    ### updateCacheDir

    string Caramel\Services\Cache::updateCacheDir()

    updates the cache directory if we changed it via php



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
        
    
