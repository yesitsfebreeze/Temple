<?php

namespace Temple\Engine\Structs\Language;


use Temple\Engine\EventManager\Event;


class LanguageConfig extends Event
{

    /**
     * @var string $extension
     */
    private $extension;

    /**
     * @var string $name
     */
    private $name;


    /**
     * @param $languages
     *
     * @return array
     */
    public function dispatch($languages)
    {
        $languages[] = $this;
        return array($languages);
    }


    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {

        $this->extension = $extension;
    }


    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



}