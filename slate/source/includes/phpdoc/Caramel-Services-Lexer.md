## Lexer

Class Lexer




* Class name: Lexer
* Namespace: Caramel\Services
* Parent class: [Service](#caramelservicesservice)





### Properties


#### $dom

    private \Caramel\Models\Dom $dom





* Visibility: **private**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $caramel

    protected \Caramel\Caramel $caramel





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $yaml

    protected \Symfony\Component\Yaml\Yaml $yaml





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $vars

    protected \Caramel\Models\Vars $vars





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $config

    protected \Caramel\Services\Config $config





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $directories

    protected \Caramel\Services\Directories $directories





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $helpers

    protected \Caramel\Services\Helpers $helpers





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $cache

    protected \Caramel\Services\Cache $cache





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $plugins

    protected \Caramel\Services\Plugins $plugins





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $lexer

    protected \Caramel\Services\Lexer $lexer





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $parser

    protected \Caramel\Services\Parser $parser





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


#### $template

    protected \Caramel\Services\Template $template





* Visibility: **protected**
* This property is defined by [Lexer](#caramelserviceslexer)


### Methods


#### lex

    array Caramel\Services\Lexer::lex(string $file, integer|boolean $level)

returns Dom object



* Visibility: **public**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $file **string**
* $level **integer|boolean**



#### prepare

    mixed Caramel\Services\Lexer::prepare(string $file, integer $level)

set the default values for our dom



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $file **string**
* $level **integer**



#### template

    \Caramel\Models\Storage Caramel\Services\Lexer::template($file, $level)

returns the matching file template



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $file **mixed**
* $level **mixed**



#### process

    mixed Caramel\Services\Lexer::process()

creates a dom for the current file



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)




#### info

    \Caramel\Models\Storage Caramel\Services\Lexer::info($line)

returns an array with information about the current node



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **mixed**



#### indent

    float|integer Caramel\Services\Lexer::indent($line)

returns the indent of the current line
also initially sets the indent character and amount



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **mixed**



#### tag

    string Caramel\Services\Lexer::tag(string $line)

returns the tag for the current line



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **string**



#### attributes

    string Caramel\Services\Lexer::attributes(string $line, \Caramel\Models\Storage $info)

returns the attributes for the current line



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **string**
* $info **[Storage](#caramelmodelsstorage)**



#### content

    string Caramel\Services\Lexer::content(string $line, \Caramel\Models\Storage $info)

returns the content for the current line



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **string**
* $info **[Storage](#caramelmodelsstorage)**



#### selfclosing

    string Caramel\Services\Lexer::selfclosing(\Caramel\Models\Storage $info)

returns if the current line has a self closing tag



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $info **[Storage](#caramelmodelsstorage)**



#### node

    \Caramel\Models\Node Caramel\Services\Lexer::node(string $line, \Caramel\Models\Storage $info)

creates a new node from a line



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $line **string**
* $info **[Storage](#caramelmodelsstorage)**



#### add

    mixed Caramel\Services\Lexer::add(\Caramel\Models\Node $node)

adds the node to the dom
parent/child logic is handled here



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### deeper

    mixed Caramel\Services\Lexer::deeper(\Caramel\Models\Node $node)

adds a node to the dom if has a deeper level
than the previous node



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### higher

    mixed Caramel\Services\Lexer::higher(\Caramel\Models\Node $node)

adds a node to the dom if has a higher level
than the previous node



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### same

    mixed Caramel\Services\Lexer::same(\Caramel\Models\Node $node)

adds a node to the dom if has the same  level
than the previous node



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $node **[Node](#caramelmodelsnode)**



#### children

    mixed Caramel\Services\Lexer::children(\Caramel\Models\Node $target, \Caramel\Models\Node $node)

adds the passed node to children



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $target **[Node](#caramelmodelsnode)**
* $node **[Node](#caramelmodelsnode)**



#### parent

    \Caramel\Models\Node Caramel\Services\Lexer::parent(\Caramel\Models\Node $node, boolean|\Caramel\Models\Node $parent)

returns the parent of the passed node



* Visibility: **private**
* This method is defined by [Lexer](#caramelserviceslexer)


##### Arguments
* $node **[Node](#caramelmodelsnode)**
* $parent **boolean|[Node](#caramelmodelsnode)**



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


