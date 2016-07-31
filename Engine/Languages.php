<?php

namespace Underware\Engine;


use Underware\Engine\EventManager\EventManager;
use Underware\Engine\Exception\Exception;
use Underware\Engine\InjectionManager\Injection;
use Underware\Engine\Structs\Dom;
use Underware\Engine\Structs\Language\Language;


/**
 * Class Languages
 *
 * @package Underware\Engine
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

            $class = "\\Underware\\Languages\\" . ucfirst(strtolower($language)) . "\\LanguageLoader";
            if (class_exists($class)) {
                /** @var Language $lang */
                $this->EventManager->register("language." . $language, $lang = new $class());
                $this->EventManager->notify("language." . $language);
            } else {
                throw new Exception(1,"Language %" . $language . "% does not exist!");
            }
        }
    }

}