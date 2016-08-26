<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\EngineWrapper;
use Temple\Engine\InjectionManager\InjectionManager;


/**
 * Class Event
 */
abstract class Event
{


    /** @var EngineWrapper $EngineWrapper */
    protected $EngineWrapper;

    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;

    /** @var string $language */
    protected $language;


    /**
     * @param EngineWrapper $EngineWrapper
     */
    public function setEngineWrapper(EngineWrapper $EngineWrapper)
    {
        $this->EngineWrapper = $EngineWrapper;
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