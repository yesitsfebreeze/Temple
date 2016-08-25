<?php

namespace Temple\Engine;


use Temple\Engine\Cache\CacheInvalidator;
use Temple\Engine\Cache\ConfigCache;
use Temple\Engine\Cache\TemplateCache;
use Temple\Engine\Console\Console;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\Languages;


class EngineWrapper extends Injection
{

    /** @var Config $Config */
    protected $Config;

    /** @var ConfigCache $ConfigCache */
    protected $ConfigCache;

    /** @var Console $Console */
    protected $Console;

    /** @var DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var EventManager $EventManager */
    protected $EventManager;

    /** @var TemplateCache $TemplateCache */
    protected $TemplateCache;

    /** @var CacheInvalidator $CacheInvalidator */
    protected $CacheInvalidator;

    /** @var Languages $Languages */
    protected $Languages;

    /** @var Lexer $Lexer */
    protected $Lexer;

    /** @var Compiler $Compiler */
    protected $Compiler;

    /** @var Template $Template */
    protected $Template;


    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Cache/ConfigCache"           => "ConfigCache",
            "Engine/Console/Console"             => "Console",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/EventManager/EventManager"   => "EventManager",
            "Engine/Cache/TemplateCache"         => "TemplateCache",
            "Engine/Cache/CacheInvalidator"      => "CacheInvalidator",
            "Engine/Languages/Languages"         => "Languages",
            "Engine/Lexer"                       => "Lexer",
            "Engine/Compiler"                    => "Compiler",
            "Engine/Template"                    => "Template"
        );
    }


    /**
     * @return Config
     */
    public function Config()
    {
        return $this->Config;
    }


    /**
     * @return ConfigCache
     */
    public function ConfigCache()
    {
        return $this->ConfigCache;
    }


    /**
     * @return Console
     */
    public function Console()
    {
        return $this->Console;
    }


    /**
     * @return DirectoryHandler
     */
    public function DirectoryHandler()
    {
        return $this->DirectoryHandler;
    }


    /**
     * @return EventManager
     */
    public function EventManager()
    {
        return $this->EventManager;
    }


    /**
     * @return TemplateCache
     */
    public function TemplateCache()
    {
        return $this->TemplateCache;
    }


    /**
     * @return CacheInvalidator
     */
    public function CacheInvalidator()
    {
        return $this->CacheInvalidator;
    }


    /**
     * @return Languages
     */
    public function Languages()
    {
        return $this->Languages;
    }


    /**
     * @return Lexer
     */
    public function Lexer()
    {
        return $this->Lexer;
    }


    /**
     * @return Compiler
     */
    public function Compiler()
    {
        return $this->Compiler;
    }


    /**
     * @return Template
     */
    public function Template()
    {
        return $this->Template;
    }

}