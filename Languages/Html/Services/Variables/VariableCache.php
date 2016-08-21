<?php

namespace Temple\Languages\Html\Services\Variables;


use Temple\Engine\Config;
use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Variables;


class VariableCache extends Event
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  Cache $Cache */
    protected $Cache;

    /** @var  Variables $Cache */
    protected $Variables;

    /** @var  Dom $Dom */
    protected $Dom;

    /** @var  string $file */
    protected $file;

    /** @var  string $url */
    protected $url;


    /**
     * dispatch the variables cache
     */
    public function dispatch()
    {
//        var_dump("test");
    }


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @param Dom $dom
     */
    public function setDom(Dom $dom)
    {
        $this->Dom = $dom;
    }


    /**
     * returns the finished and merge php and template variables
     *
     * @return mixed|Variables
     */
    public function getMergedVariables()
    {
        /** @var Variables $Variables */
        $Variables = $this->unSerializeTemplateVariables($this->file);
        $Variables = $this->mergePhpVariables($Variables, $this->file);

        return $Variables;
    }


    /**
     * saves the template variables in the cache
     */
    public function saveTemplateVariables()
    {
        $Variables = serialize($this->Dom->getVariables());
        $this->Cache->save($this->getTemplateVariablesFileName($this->file), $Variables);
    }


    /**
     * returns the name of the base variables file
     *
     * @param $file
     *
     * @return mixed
     */
    private function getTemplateVariablesFileName($file)
    {
        return str_replace($this->Config->getExtension(), "variables." . $this->Config->getExtension(), $file);
    }


    /**
     * returns the base Variables object
     *
     * @param $file
     *
     * @return mixed
     */
    private function unSerializeTemplateVariables($file)
    {
        $file = $this->cleanExtension($file);
        $file = $this->getTemplateVariablesFileName($file);
        $file = $this->Cache->getFile($file);

        return unserialize(file_get_contents($file));
    }


    /**
     * merges the php assigned variables with the template ones
     *
     * @param Variables $Variables
     * @param           $file
     *
     * @return Variables
     */
    private function mergePhpVariables($Variables, $file)
    {

        if (!$Variables) {
            $Variables = new Variables();
        }

        /** @var Variables $PhpVariables */
        if ($this->Config->isVariableCacheEnabled()) {
            $this->serializePhpVariables($file);
            $PhpVariables         = $this->unSerializePhpVariables($file);
            $unCachedPhpVariables = clone $this->Variables;
            if (!is_null($PhpVariables)) {
                $PhpVariablesArray = $PhpVariables->get();
                if (!is_null($PhpVariablesArray)) {
                    $unCachedPhpVariables->merge($PhpVariablesArray);
                }
            }
            $PhpVariablesArray = $unCachedPhpVariables->get();

        } else {
            $PhpVariables      = $this->Variables;
            $PhpVariablesArray = $PhpVariables->get();
        }

        if (!is_null($PhpVariablesArray)) {
            $Variables->merge($PhpVariablesArray);
        }

        if (!is_null($this->Variables->cached)) {
            $Variables->cached = $this->Variables->cached;
        }
        if (!is_null($this->Variables->unCached)) {
            $Variables->unCached = $this->Variables->unCached;
        }

        return $Variables;
    }


    /**
     * returns the name of the base variables file
     *
     * @param $file
     *
     * @return mixed
     */
    private function getPhpVariablesFileName($file)
    {
        return str_replace($this->Config->getExtension(), "variables." . $this->getUrlHash() . "." . $this->Config->getExtension(), $file);
    }


    /**
     * saves the serialized base variables in the cache file
     *
     * @param     $file
     */
    private function serializePhpVariables($file)
    {
        /** @var Variables $Variables */
        $Variables = serialize($this->Variables->cached);
        $file      = $this->cleanExtension($file);
        $file      = $this->getPhpVariablesFileName($file);
        $this->Cache->save($file, $Variables);
    }


    /**
     * returns the current page url which will be used to cache the php assigned variables
     *
     * @return string
     */
    static function getUrlHash()
    {
        return md5($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    }


    /**
     * returns the base Variables object
     *
     * @param $file
     *
     * @return mixed
     */
    private function unSerializePhpVariables($file)
    {
        $file = $this->cleanExtension($file);
        $file = $this->getPhpVariablesFileName($file);
        $file = $this->Cache->getFile($file);

        return unserialize(file_get_contents($file));
    }


    /**
     * make sure we have the template extension
     *
     * @param $file
     *
     * @return string
     */
    private function cleanExtension($file)
    {
        $file = preg_replace('/\..*?$/', '', $file) . "." . $this->Config->getExtension();

        return $file;
    }


}