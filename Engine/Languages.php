<?php

namespace Underware\Engine;


use Underware\Engine\EventManager\EventManager;
use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;
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
        if ($Dom->getPreviousNode() == null) {
            $line = trim($line);
            if (strpos($line, "use") !== false) {
                preg_match("/^(.*?)(?:$|\s)/", $line, $tag);
                $tag       = trim($tag[0]);
                $languages = trim(str_replace($tag, "", $line));
                if (strpos($line, ",") !== false) {
                    $languages = explode(",", $languages);
                } else {
                    $languages = array($languages);
                }
                array_unshift($languages, "core");
                $this->loadLanguages($languages);
            } else {
                $languages = $this->Config->getDefaultLanguages();
                $this->loadLanguages($languages);
            }
        }
    }


    private function loadLanguages($languages)
    {
        foreach ($languages as $language) {

            $class = "\\Underware\\Languages\\" . ucfirst(strtolower($language)) . "\\Loader";
            if (class_exists($class)) {
                /** @var Language $lang */
                $this->EventManager->register("language." . $language, $lang = new $class());
                $this->EventManager->notify("language." . $language);
            } else {
                throw new Exception("%" . $language . "% Language does not exist!");
            }
        }
    }

}