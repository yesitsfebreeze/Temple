## Plugin

Class Plugin




* Class name: Plugin
* Namespace: Caramel\Plugin
* This is an **abstract** class
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Plugin](#caramelpluginplugin)


### Methods


#### position

    integer Caramel\Plugin\Plugin::position()





* Visibility: **public**
* This method is **abstract**.
* This method is defined by [Plugin](#caramelpluginplugin)




#### getName

    string Caramel\Plugin\Plugin::getName()





* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)




#### preProcess

    \Caramel\Models\Dom Caramel\Plugin\Plugin::preProcess(\Caramel\Models\Dom $dom)

this is called before we even touch a node
so we can add stuff to our config etc



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $dom **[Dom](#caramelmodelsdom)**



#### process

    \Caramel\Models\Node Caramel\Plugin\Plugin::process(\Caramel\Models\Node $node)

the function we should use for processing a node



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### check

    boolean Caramel\Plugin\Plugin::check($node)

the function to check if we want to
modify a node



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $node **mixed**



#### realProcess

    \Caramel\Models\Node Caramel\Plugin\Plugin::realProcess(\Caramel\Models\Node $node)

processes the actual node
if all requirements are met



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### postProcess

    \Caramel\Models\Dom Caramel\Plugin\Plugin::postProcess(\Caramel\Models\Dom $dom)

this is called after the plugins processed
all nodes



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $dom **[Dom](#caramelmodelsdom)**



#### processOutput

    string Caramel\Plugin\Plugin::processOutput($output)

this is called after the plugins processed
all nodes and converted it into a html string



* Visibility: **public**
* This method is defined by [Plugin](#caramelpluginplugin)


##### Arguments
* $output **mixed**



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


