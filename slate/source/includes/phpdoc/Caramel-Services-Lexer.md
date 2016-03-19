Caramel\Services\Lexer
===============

Class Lexer




    * Class name: Lexer
    * Namespace: Caramel\Services
        * Parent class: [Caramel\Services\Service](#Caramel-Services-Service)
            




    Properties
    ----------


    ### $dom

    private \Caramel\Models\Dom $dom

    



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


    ### lex

    array Caramel\Services\Lexer::lex(string $file, integer|boolean $level)

    returns Dom object



    * Visibility: **public**
                

            #### Arguments
                    * $file **string**
                    * $level **integer|boolean**
        
    

    ### prepare

    mixed Caramel\Services\Lexer::prepare(string $file, integer $level)

    set the default values for our dom



    * Visibility: **private**
                

            #### Arguments
                    * $file **string**
                    * $level **integer**
        
    

    ### template

    \Caramel\Models\Storage Caramel\Services\Lexer::template($file, $level)

    returns the matching file template



    * Visibility: **private**
                

            #### Arguments
                    * $file **mixed**
                    * $level **mixed**
        
    

    ### process

    mixed Caramel\Services\Lexer::process()

    creates a dom for the current file



    * Visibility: **private**
                

    

    ### info

    \Caramel\Models\Storage Caramel\Services\Lexer::info($line)

    returns an array with information about the current node



    * Visibility: **private**
                

            #### Arguments
                    * $line **mixed**
        
    

    ### indent

    float|integer Caramel\Services\Lexer::indent($line)

    returns the indent of the current line
also initially sets the indent character and amount



    * Visibility: **private**
                

            #### Arguments
                    * $line **mixed**
        
    

    ### tag

    string Caramel\Services\Lexer::tag(string $line)

    returns the tag for the current line



    * Visibility: **private**
                

            #### Arguments
                    * $line **string**
        
    

    ### attributes

    string Caramel\Services\Lexer::attributes(string $line, \Caramel\Models\Storage $info)

    returns the attributes for the current line



    * Visibility: **private**
                

            #### Arguments
                    * $line **string**
                    * $info **[Caramel\Models\Storage](#Caramel-Models-Storage)**
        
    

    ### content

    string Caramel\Services\Lexer::content(string $line, \Caramel\Models\Storage $info)

    returns the content for the current line



    * Visibility: **private**
                

            #### Arguments
                    * $line **string**
                    * $info **[Caramel\Models\Storage](#Caramel-Models-Storage)**
        
    

    ### selfclosing

    string Caramel\Services\Lexer::selfclosing(\Caramel\Models\Storage $info)

    returns if the current line has a self closing tag



    * Visibility: **private**
                

            #### Arguments
                    * $info **[Caramel\Models\Storage](#Caramel-Models-Storage)**
        
    

    ### node

    \Caramel\Models\Node Caramel\Services\Lexer::node(string $line, \Caramel\Models\Storage $info)

    creates a new node from a line



    * Visibility: **private**
                

            #### Arguments
                    * $line **string**
                    * $info **[Caramel\Models\Storage](#Caramel-Models-Storage)**
        
    

    ### add

    mixed Caramel\Services\Lexer::add(\Caramel\Models\Node $node)

    adds the node to the dom
parent/child logic is handled here



    * Visibility: **private**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### deeper

    mixed Caramel\Services\Lexer::deeper(\Caramel\Models\Node $node)

    adds a node to the dom if has a deeper level
than the previous node



    * Visibility: **private**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### higher

    mixed Caramel\Services\Lexer::higher(\Caramel\Models\Node $node)

    adds a node to the dom if has a higher level
than the previous node



    * Visibility: **private**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### same

    mixed Caramel\Services\Lexer::same(\Caramel\Models\Node $node)

    adds a node to the dom if has the same  level
than the previous node



    * Visibility: **private**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### children

    mixed Caramel\Services\Lexer::children(\Caramel\Models\Node $target, \Caramel\Models\Node $node)

    adds the passed node to children



    * Visibility: **private**
                

            #### Arguments
                    * $target **[Caramel\Models\Node](#Caramel-Models-Node)**
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
        
    

    ### parent

    \Caramel\Models\Node Caramel\Services\Lexer::parent(\Caramel\Models\Node $node, boolean|\Caramel\Models\Node $parent)

    returns the parent of the passed node



    * Visibility: **private**
                

            #### Arguments
                    * $node **[Caramel\Models\Node](#Caramel-Models-Node)**
                    * $parent **boolean|[boolean](#Caramel-Models-Node)**
        
    

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
        
    
