## Cache

Class Cache




* Class name: Cache
* Namespace: Caramel\Services
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $cacheFile

    private string $cacheFile = "__cache.php"





* Visibility: **private**
* This property is defined by [Cache](#caramelservicescache)


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Cache](#caramelservicescache)


### Methods


#### set

    string Caramel\Services\Cache::set(string $dir)

sets the cache directory



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $dir **string**



#### save

    string Caramel\Services\Cache::save($file, $content)

saves a file to the cache



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**
* $content **mixed**



#### modified

    boolean Caramel\Services\Cache::modified($file)

returns if a file is modified



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### dependency

    boolean Caramel\Services\Cache::dependency(string $parent, string $file)

adds a dependency to the cache



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $parent **string**
* $file **string**



#### setTime

    boolean Caramel\Services\Cache::setTime($file)

writes the modify times for the current template
into our cache file



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### getCache

    array Caramel\Services\Cache::getCache()

returns the cache array



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)




#### saveCache

    boolean Caramel\Services\Cache::saveCache(array $cache)

saves the array to the cache



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $cache **array**



#### getPath

    string Caramel\Services\Cache::getPath($file)

returns the cache path for the given file



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### extension

    mixed|string Caramel\Services\Cache::extension($file)

adds a php extension to the files path



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### createFile

    mixed|string Caramel\Services\Cache::createFile($file)

creates the file if its not already there



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### clear

    boolean|\Caramel\Services\Error Caramel\Services\Cache::clear(boolean $dir)

empties the cache directory



* Visibility: **public**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $dir **boolean**



#### clean

    string Caramel\Services\Cache::clean($file)

removes the template dirs and the extension form a file path



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)


##### Arguments
* $file **mixed**



#### updateCacheDir

    string Caramel\Services\Cache::updateCacheDir()

updates the cache directory if we changed it via php



* Visibility: **private**
* This method is defined by [Cache](#caramelservicescache)




#### setCaramel

    mixed Caramel\Services\Service::setCaramel(\Caramel\Caramel $Caramel)

sets $this->caramel



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Caramel **[Caramel](#caramelcaramel)**



#### setYaml

    mixed Caramel\Services\Service::setYaml(\Symfony\Component\Yaml\Yaml $Yaml)

sets $this->yaml



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Yaml **Yaml**



#### setVars

    mixed Caramel\Services\Service::setVars(\Caramel\Models\Vars $Vars)

sets $this->vars



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Vars **[Vars](#caramelmodelsvars)**



#### setConfig

    mixed Caramel\Services\Service::setConfig(\Caramel\Services\Config $Config)

sets $this->config



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Config **[Config](#caramelservicesconfig)**



#### setDirectories

    mixed Caramel\Services\Service::setDirectories(\Caramel\Services\Directories $Directories)

sets $this->directories



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Directories **[Directories](#caramelservicesdirectories)**



#### setHelpers

    mixed Caramel\Services\Service::setHelpers(\Caramel\Services\Helpers $Helpers)

sets $this->helpers



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Helpers **[Helpers](#caramelserviceshelpers)**



#### setCache

    mixed Caramel\Services\Service::setCache(\Caramel\Services\Cache $Cache)

sets $this->cache



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Cache **[Cache](#caramelservicescache)**



#### setPlugins

    mixed Caramel\Services\Service::setPlugins(\Caramel\Services\Plugins $Plugins)

sets $this->plugins



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Plugins **[Plugins](#caramelservicesplugins)**



#### setLexer

    mixed Caramel\Services\Service::setLexer(\Caramel\Services\Lexer $Lexer)

sets $this->lexer



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Lexer **[Lexer](#caramelserviceslexer)**



#### setParser

    mixed Caramel\Services\Service::setParser(\Caramel\Services\Parser $Parser)

sets $this->parser



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Parser **[Parser](#caramelservicesparser)**



#### setTemplate

    mixed Caramel\Services\Service::setTemplate(\Caramel\Services\Template $Template)

sets $this->template



* Visibility: **public**
* This method is defined by [Service](#caramelservicesservice)


##### Arguments
* $Template **[Template](#caramelservicestemplate)**


