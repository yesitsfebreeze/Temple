## Directories

handles the directory creation
Class Directories




* Class name: Directories
* Namespace: Caramel\Services
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Directories](#caramelservicesdirectories)


### Methods


#### add

    boolean Caramel\Services\Directories::add(string $dir, string $name, boolean $create)

validates and adds a directory to our config
the $name variable will determent the array name
the $single variable will create a simple string instead of an array
note: the directories will be added top down,
so the last added item will be indexed with 0



* Visibility: **public**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $dir **string**
* $name **string**
* $create **boolean**



#### get

    array|boolean Caramel\Services\Directories::get($name)

returns the selected directory/ies



* Visibility: **public**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $name **mixed**



#### remove

    boolean Caramel\Services\Directories::remove(integer $pos, string $name)





* Visibility: **public**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $pos **integer**
* $name **string**



#### forArray

    boolean|string Caramel\Services\Directories::forArray($name, $dirs, $dir, $create)





* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $name **mixed**
* $dirs **mixed**
* $dir **mixed**
* $create **mixed**



#### forString

    mixed Caramel\Services\Directories::forString($name, $dirs, $dir, $create)





* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $name **mixed**
* $dirs **mixed**
* $dir **mixed**
* $create **mixed**



#### create

    mixed Caramel\Services\Directories::create(boolean $create, string $dir)





* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $create **boolean**
* $dir **string**



#### path

    string Caramel\Services\Directories::path($dir)

checks if we have a relative or an absolute directory
and returns the adjusted directory



* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $dir **mixed**



#### validate

    string Caramel\Services\Directories::validate($dir)

checks if the passed directory exists



* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)


##### Arguments
* $dir **mixed**



#### root

    string Caramel\Services\Directories::root()

gets the current document root



* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)




#### framework

    array|string Caramel\Services\Directories::framework()

Returns the Caramel Directory



* Visibility: **private**
* This method is defined by [Directories](#caramelservicesdirectories)




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


