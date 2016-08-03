<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Language\Language;


/**
 * Class Languages
 *
 * @package Temple\Engine
 */
class Languages extends Injection
{


    /** @var  Config $Config */
    protected $Config;

    /** @var  EventManager $EventManager */
    protected $EventManager;


    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager" => "EventManager",
            "Engine/Config"                    => "Config"
        );
    }


    public function load(Dom $Dom, $line)
    {

        $languages = array();

        if ($this->Config->getUseCoreLanguage()) {
            array_unshift($languages, "core");
        }

        if ($Dom->getPreviousNode() == null) {
            $line = trim($line);
            preg_match("/^(.*?)(?:$|\s)/", $line, $tag);
            $tag = trim($tag[0]);
            if ($tag == "use") {
                $loadedLanguages = trim(str_replace($tag, "", $line));
                if (strpos($line, ",") !== false) {
                    $languages = array_merge(explode(",", $loadedLanguages), $languages);
                } else {
                    $languages = array_merge(array($loadedLanguages), $languages);
                }
                $this->loadLanguages($languages);

            } else {
                $languages = array_merge($this->Config->getDefaultLanguages(), $languages);
                $this->loadLanguages($languages);
            }
        }
    }


    private function loadLanguages($languages)
    {
        foreach ($languages as $language) {
            $frameworkName = reset(explode("\\", __NAMESPACE__));
            $class         = "\\" . $frameworkName . "\\Languages\\" . ucfirst(strtolower($language)) . "\\LanguageLoader";
            if (class_exists($class)) {
                /** @var Language $lang */
                $this->EventManager->register("language." . $language, $lang = new $class());
                $this->EventManager->notify("language." . $language);
            } else {
                throw new Exception(1, "Language %" . $language . "% does not exist!");
            }
        }
    }

}