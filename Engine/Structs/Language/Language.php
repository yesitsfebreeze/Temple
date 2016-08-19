<?php

namespace Temple\Engine\Structs\Language;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;


class Language extends Event
{

    /**
     * @var string $extension
     */
    private $languageExtension;


    /**
     * @param $args
     */
    public function dispatch($args)
    {
        $languageName = explode("\\", get_class($this));
        array_pop($languageName);
        $languageName = end($languageName);

        $this->register();
        $this->languageExtension = $this->extension();
        $language                = $this->createLanguage($languageName);
        $this->Instance->EventManager()->register("languages." . $languageName, $language);
    }


    /** @inheritdoc */
    public function extension()
    {
        throw new Exception(1, "Please implement the extension function for %" . get_class($this) . "%", __FILE__);
    }


    /** @inheritdoc */
    public function register()
    {
        throw new Exception(1, "Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }


    /**
     * creates a language config which is stored in the dom
     *
     * @param $languageName
     *
     * @return LanguageConfig
     */
    private function createLanguage($languageName)
    {
        $language = new LanguageConfig();
        $language->setExtension($this->languageExtension);
        $language->setName($languageName);

        return $language;
    }


}