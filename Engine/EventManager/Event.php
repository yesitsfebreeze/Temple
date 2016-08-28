<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\Instance;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine\Languages\LanguageConfig;


/**
 * Class Event
 */
abstract class Event
{


    /** @var Instance $Instance */
    protected $Instance;

    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;

    /** @var string $languageName */
    protected $languageName;


    /**
     * @param Instance $Instance
     */
    public function setInstance(Instance $Instance)
    {
        $this->Instance = $Instance;
    }


    /**
     * @param InjectionManager $InjectionManager
     */
    public function setInjectionManager(InjectionManager $InjectionManager)
    {
        $this->InjectionManager = $InjectionManager;
    }


    /**
     * @return string
     */
    public function getLanguageName()
    {
        return $this->languageName;
    }


    /**
     * @param string $Language
     */
    public function setLanguageName($Language)
    {
        $this->languageName = $Language;
    }

    /**
     * @return LanguageConfig
     */
    public function getLanguageConfig()
    {
        return $this->Instance->Languages()->getLanguage($this->languageName)->getConfig();
    }

}