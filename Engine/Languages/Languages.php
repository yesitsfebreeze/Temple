<?php

namespace Temple\Engine\Languages;


use Temple\Engine;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;


/**
 * Class Languages
 *
 * @package Temple\Engine
 */
class Languages extends Injection
{

    /** @var  Engine $Engine */
    protected $Engine;

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var array $languages */
    private $languages = array();


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager"   => "EventManager",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler"
        );
    }


    /**
     * the construct function loads all the default languages
     * aka Languages constructor.
     */
    public function initLanguages()
    {
        $useCore = $this->Engine->Config()->isUseCoreLanguage();
        if ($useCore) {
            $this->Engine->Config()->addLanguage("./Languages/Core");
        }
        $defaultLanguagePath = $this->Engine->Config()->getDefaultLanguage();
        $this->Engine->Config()->addLanguage($defaultLanguagePath, "default");
    }


    /**
     * @param $name
     * @param $path
     *
     * @throws Exception
     */
    public function initLanguageConfig($name, $path)
    {
        $config = $path . "Config.php";

        if ($name == "default") {
            $name = explode("/", preg_replace("/\/$/", "", $path));
            $name = strtolower(end($name));
        }

        if (!file_exists($config)) {
            throw new Exception(1, "Please create a Config.php for the %" . $name . "% language!");
        }

        /** @noinspection PhpIncludeInspection */
        require_once $config;

        $configClassName = $this->getClassName($name, "Config");


        if (!class_exists($configClassName)) {
            throw new Exception(1, "There is not the right class declaration within  %" . $config . "%!");
        }

        $config = new $configClassName($this->Engine);
        $this->Engine->Config()->addLanguageConfig($config);
    }


    /**
     * @param $lang
     *
     * @return BaseLanguage|false
     * @throws Exception
     */
    public function getLanguage($lang)
    {
        return $this->iterate($lang);
    }


    /**
     * @param $file
     *
     * @return BaseLanguage|false
     * @throws Exception
     */
    public function getLanguageFromFile($file)
    {
        $languages = array();
        $file = $this->DirectoryHandler->getTemplatePath($file);
        if (file_exists($file)) {
            $handle = fopen($file, "r");
            while (($line = fgets($handle)) !== false) {
                if (trim($line) != '') {

                    preg_match("/^(.*?)(?:$|\s)/", trim($line), $tag);
                    $tag = trim($tag[0]);

                    if ($this->Engine->Config()->isUseCoreLanguage()) {
                        array_unshift($languages, "core");
                    }

                    if ($tag == $this->Engine->Config()->getLanguageTagName()) {
                        $lang = trim(str_replace($tag, "", $line));
                    } else {
                        $lang = "default";
                    }

                    $languages[] = $lang;

                    /** @var  BaseLanguage $lang */
                    return $this->iterate($languages);
                }
            }
        } else {
            var_dump($file);
        }

        return false;
    }


    /**
     * @param $languages
     *
     * @return BaseLanguage
     * @throws Exception
     */
    private function iterate($languages)
    {
        $loadedLanguage = null;

        if (is_string($languages)) {
            $languages = array($languages);
        }

        $registeredLanguages = $this->Engine->Config()->getLanguages();


        foreach ($languages as $name) {
            if (isset($registeredLanguages[ $name ])) {
                $path = realpath($registeredLanguages[ $name ]) . DIRECTORY_SEPARATOR;
                if ($name == "default") {
                    $name = explode("/", preg_replace("/\/$/", "", $path));
                    $name = strtolower(end($name));
                }
                $loadedLanguage = $this->load($path, $name);
            } else {
                throw new Exception(1, "Language %" . $name . "% does not exist!");
            }
        }

        return $loadedLanguage;
    }


    /**
     * @param $path
     * @param $name
     *
     * @return BaseLanguage
     * @throws Exception
     */
    private function load($path, $name)
    {
        if (is_dir($path)) {
            $language = $path . "Language.php";

            if (!file_exists($language)) {
                throw new Exception(1, "Please create a Loader.php for the %" . $name . "% language!");
            }

            /** @noinspection PhpIncludeInspection */
            require_once $language;

            $languageClassName = $this->getClassName($name, "Language");
            $configClassName   = $this->getClassName($name, "Config");

            if (!class_exists($languageClassName)) {
                throw new Exception(1, "There is not the right class declaration within  %" . $language . "%!");
            }


            /** @var BaseLanguage $lang */
            $language = $this->registerLanguageClass($languageClassName, $configClassName, $path);


            if (!$this->languages[ $languageClassName ]["registered"]) {
                $language->register();
                $this->languages[ $languageClassName ]["registered"] = true;
            }

            return $this->languages[ $languageClassName ]["class"];

        }

        throw new Exception(1, "There are no languages registered!");
    }


    /**
     * returns a namespaced class for the give language
     *
     * @param string $language
     * @param string $name
     *
     * @return string
     */
    private function getClassName($language, $name)
    {
        $namespaces    = explode("\\", __NAMESPACE__);
        $frameworkName = reset($namespaces);
        $class         = "\\" . $frameworkName . "\\Languages\\" . ucfirst(strtolower($language)) . "\\$name";

        return $class;
    }


    /**
     * @param $loaderClass
     * @param $configClass
     * @param $path
     *
     * @return mixed
     */
    private function registerLanguageClass($loaderClass, $configClass, $path)
    {
        if (!isset($this->languages[ $loaderClass ])) {
            /** @var LanguageConfig $config */
            $config = new $configClass($this->Engine);

            $language                        = array();
            $language["class"]               = new $loaderClass($this->Engine, $config->getName(), $path);
            $language["registered"]          = false;
            $this->languages[ $loaderClass ] = $language;

            unset($config);
        }

        return $this->languages[ $loaderClass ]["class"];
    }


    /**
     * @return Engine
     */
    public function getEngine()
    {
        return $this->Engine;
    }


    /**
     * @param Engine $Engine
     */
    public function setEngine($Engine)
    {
        $this->Engine = $Engine;
    }


}