<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\Instance;
use Temple\Engine\InjectionManager\InjectionManager;


/**
 * Class Event
 */
abstract class Event
{


    /** @var Instance $Instance */
    protected $Instance;

    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;

    /** @var string $language */
    protected $language;


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
    public function getLanguage()
    {
        return $this->language;
    }


    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
}