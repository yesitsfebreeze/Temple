<?php

namespace Temple\Engine\Languages;


use Temple\Engine\Instance;
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

    /** @var  Instance $Instance */
    protected $Instance;

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
        $useCore = $this->Instance->Config()->isUseCoreLanguage();
        if ($useCore) {
            $this->Instance->Config()->addLanguage("./Engine/Languages/Core");
        }
        $defaultLanguagePath = $this->Instance->Config()->getDefaultLanguage();
        if ($defaultLanguagePath != "") {
            $this->Instance->Config()->addLanguage($defaultLanguagePath, "default");
        }
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

        var_dump($configClassName);
        if (!class_exists($configClassName)) {
            throw new Exception(1, "There is not the right class declaration within  %" . $config . "%!");
        }

        $config = new $configClassName($this->Instance);
        $this->Instance->Config()->addLanguageConfig($config);
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
        $languages    = array();
        $templateFile = $this->DirectoryHandler->getTemplatePath($file);
        if (file_exists($templateFile)) {
            $handle = fopen($templateFile, "r");
            while (($line = fgets($handle)) !== false) {
                if (trim($line) != '') {

                    preg_match("/^(.*?)(?:$|\s)/", trim($line), $tag);
                    $tag = trim($tag[0]);

                    if ($this->Instance->Config()->isUseCoreLanguage()) {
                        array_unshift($languages, "core");
                    }

                    if ($tag == $this->Instance->Config()->getLanguageTagName()) {
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
            throw new Exception(123, "There is no language for this file", $file);
        }

        if ($this->Instance->Config()->isUseCoreLanguage()) {
            array_unshift($languages, "core");
        }
        array_unshift($languages, "default");

        return $this->iterate($languages);
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

        $registeredLanguages = $this->Instance->Config()->getLanguages();

        foreach ($languages as $name) {
            $default = $this->Instance->Config()->getDefaultLanguage();
            $default = explode("/",$default);
            $default = strtolower(end($default));
            if ($name == $default) {
                $name = "default";
            }

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

            $this->registerLanguage($language);

            return $this->languages[ $languageClassName ]["class"];

        }

        throw new Exception(1, "There are no languages registered!");
    }


    /**
     * registers all nodes of a language
     *
     * @param BaseLanguage $language
     */
    public function registerLanguage(BaseLanguage $language)
    {
        $name              = $language->getConfig()->getName();
        $languageClassName = $this->getClassName($name, "Language");

        if (!$this->languages[ $languageClassName ]["registered"]) {
            $language->register();
            $this->languages[ $languageClassName ]["registered"] = true;
        }
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
        $class         = "\\" . $frameworkName . "\\Language\\" . ucfirst(strtolower($language)) . "\\$name";

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
            $config = new $configClass($this->Instance);

            $language                        = array();
            $language["class"]               = new $loaderClass($this->Instance, $config->getName(), $path);
            $language["registered"]          = false;
            $this->languages[ $loaderClass ] = $language;

            unset($config);
        }

        return $this->languages[ $loaderClass ]["class"];
    }


    /**
     * @return Instance
     */
    public function getInstance()
    {
        return $this->Instance;
    }


    /**
     * @param Instance $Instance
     */
    public function setInstance($Instance)
    {
        $this->Instance = $Instance;
    }


}