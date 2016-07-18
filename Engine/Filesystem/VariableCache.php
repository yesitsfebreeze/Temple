<?php

namespace Underware\Engine\Filesystem;


use Underware\Engine\Config;
use Underware\Engine\Injection\Injection;
use Underware\Engine\Structs\Dom;
use Underware\Engine\Structs\Variables;


class VariableCache extends Injection
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  Cache $Cache */
    protected $Cache;

    /** @var  Cache $Cache */
    protected $Variables;

    /** @var  Dom $Dom */
    protected $Dom;

    /** @var  string $file */
    protected $file;


    public function dependencies()
    {
        return array(
            "Engine/Config"            => "Config",
            "Engine/Filesystem/Cache"  => "Cache",
            "Engine/Structs/Variables" => "Variables"
        );
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
        $Variables = $this->unSerializeTemplateVariables($this->file);
        $Variables = $this->mergeViewVariables($Variables, $this->file);

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
    private function mergeViewVariables(Variables $Variables, $file)
    {
        $this->serializeViewVariables($file);
        $ViewVariables = $this->unSerializeViewVariables($file);
        $ViewVariables = $ViewVariables->get();
        if (!is_null($ViewVariables)) {
            $Variables->merge($ViewVariables);
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
    private function getViewVariablesFileName($file)
    {
        $hash = md5($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);

        return str_replace($this->Config->getExtension(), "variables." . $hash . "." . $this->Config->getExtension(), $file);
    }


    /**
     * saves the serialized base variables in the cache file
     *
     * @param     $file
     */
    private function serializeViewVariables($file)
    {
        $Variables = serialize($this->Variables);
        $file      = $this->cleanExtension($file);
        $file      = $this->getViewVariablesFileName($file);
        $this->Cache->save($file, $Variables);
    }


    /**
     * returns the base Variables object
     *
     * @param $file
     *
     * @return mixed
     */
    private function unSerializeViewVariables($file)
    {
        $file = $this->cleanExtension($file);
        $file = $this->getViewVariablesFileName($file);
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