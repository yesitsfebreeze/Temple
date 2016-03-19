## Template

Class Template




* Class name: Template
* Namespace: Caramel\Services
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Template](#caramelservicestemplate)


### Methods


#### show

    mixed Caramel\Services\Template::show($file)

Renders and includes the passed file



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)


##### Arguments
* $file **mixed**



#### add

    string Caramel\Services\Template::add($dir)

adds a template directory



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)


##### Arguments
* $dir **mixed**



#### remove

    string Caramel\Services\Template::remove(integer $pos)

removes a template directory



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)


##### Arguments
* $pos **integer**



#### dirs

    array Caramel\Services\Template::dirs()

returns all template directories



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)




#### fetch

    string Caramel\Services\Template::fetch($file)

Renders and returns the passed dom



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)


##### Arguments
* $file **mixed**



#### parse

    mixed|string Caramel\Services\Template::parse($file)

parsed a template file



* Visibility: **public**
* This method is defined by [Template](#caramelservicestemplate)


##### Arguments
* $file **mixed**



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


