<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
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

    /** @var array $languages */
    private $languages = array();


    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager" => "EventManager",
            "Engine/Config"                    => "Config"
        );
    }


    /**
     * @param $lang
     */
    public function loadLanguage($lang)
    {

        $languages = array();

        if ($this->Config->isUseCoreLanguage()) {
            array_unshift($languages, "core");
        }

        if ($lang != "") {
            $languages = array_merge($languages, array($lang));
            $this->load($languages);
        } else {
            $languages = array_merge($languages, $this->Config->getDefaultLanguage());
            $this->load($languages);
        }

    }


    /**
     * @param $lang
     *
     * @return mixed
     * @throws Exception
     */
    public function getLanguage($lang)
    {
        $class = $this->getLanguageClass($lang);
        if (class_exists($class)) {
            /** @var Language $lang */
            return $this->createLanguageClass($class);
        } else {
            throw new Exception(1, "Language %" . $lang . "% does not exist!");
        }

    }
    /**
     * @param $file
     *
     * @return Language|false
     * @throws Exception
     */
    public function getLanguageFromFile($file)
    {
        $language = false;

        if (file_exists($file)) {

            $handle = fopen($file, "r");
            while (($line = fgets($handle)) !== false) {
                if (trim($line) != '') {
                    preg_match("/^(.*?)(?:$|\s)/", trim($line), $tag);
                    $tag = trim($tag[0]);

                    if ($tag == $this->Config->getLanguageTagName()) {
                        $lang = trim(str_replace($tag, "", $line));
                    } else {
                        $lang = $this->Config->getDefaultLanguage();
                    }

                    /** @var  Language $lang*/
                    $language = $this->getLanguage($lang);
                    break;
                }
            }
        }

        if (!$language) {
            throw new Exception(700,"The requested language doesn't exist");
        }

        return $language;
    }


    /**
     * @param $languages
     *
     * @throws Exception
     */
    private function load($languages)
    {
        foreach ($languages as $language) {
            $class = $this->getLanguageClass($language);
            if (class_exists($class)) {
                /** @var Language $lang */
                $lang = $this->createLanguageClass($class);
                $this->EventManager->register("language." . $language, $lang);
                $this->EventManager->notify("language." . $language);
            } else {
                throw new Exception(1, "Language %" . $language . "% does not exist!");
            }
        }
    }


    /**
     * returns a namespaced class for the give language
     *
     * @param $lang
     *
     * @return string
     */
    private function getLanguageClass($lang)
    {
        $namespaces    = explode("\\", __NAMESPACE__);
        $frameworkName = reset($namespaces);
        $class = "\\" . $frameworkName . "\\Languages\\" . ucfirst(strtolower($lang)) . "\\LanguageLoader";
        return $class;
    }


    /**
     * @param $class
     *
     * @return mixed
     */
    private function createLanguageClass($class)
    {
        if (!isset($this->languages[$class])) {
            $this->languages[$class] = new $class($this->Config);
        }
        return $this->languages[$class];
    }
}