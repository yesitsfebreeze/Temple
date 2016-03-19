## Plugins

handles the plugin loading
Class PluginLoader




* Class name: Plugins
* Namespace: Caramel\Services
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $list

    private array $list = array()





* Visibility: **private**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Plugins](#caramelservicesplugins)


### Methods


#### init

    mixed Caramel\Services\Plugins::init()

initiates the plugins



* Visibility: **public**
* This method is defined by [Plugins](#caramelservicesplugins)




#### add

    string Caramel\Services\Plugins::add($dir)

adds a plugin directory



* Visibility: **public**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $dir **mixed**



#### remove

    string Caramel\Services\Plugins::remove(integer $pos)

removes a plugin dir



* Visibility: **public**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $pos **integer**



#### dirs

    array Caramel\Services\Plugins::dirs()

returns all plugin dirs



* Visibility: **public**
* This method is defined by [Plugins](#caramelservicesplugins)




#### container

    mixed Caramel\Services\Plugins::container($name, $plugins)

adds a new container to the plugins configuration



* Visibility: **public**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $name **mixed**
* $plugins **mixed**



#### getPlugins

    mixed Caramel\Services\Plugins::getPlugins()

gets all registered plugins



* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)




#### loadPlugins

    mixed Caramel\Services\Plugins::loadPlugins($pluginFile)





* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $pluginFile **mixed**



#### loadPlugin

    mixed Caramel\Services\Plugins::loadPlugin(string $file)

loads all plugins



* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $file **string**



#### getPluginName

    string Caramel\Services\Plugins::getPluginName(string $file)

extracts the plugin name



* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $file **string**



#### createPlugin

    \Caramel\Plugin\Plugin Caramel\Services\Plugins::createPlugin(string $class)

creates a new plugin instance with the given class



* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $class **string**



#### addPlugin

    array Caramel\Services\Plugins::addPlugin($position, $plugin)





* Visibility: **private**
* This method is defined by [Plugins](#caramelservicesplugins)


##### Arguments
* $position **mixed**
* $plugin **mixed**



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


