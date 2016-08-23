<?php


namespace Temple\Engine\Cache;


use Temple\Engine;
use Temple\Engine\Structs\Variables;


/**
 * Class ConfigCache
 *
 * @package Temple\Engine\Cache
 */
abstract class VariablesBaseCache
{

    /** @var  Engine $Engine */
    protected $Engine;

    /** @var  string $file */
    protected $file;


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * returns the finished and merge php and template variables
     *
     * @return mixed|Variables
     */
    public function getVariables()
    {
        /** @var Variables $Variables */
        $Variables = $this->getVariablesFromCache($this->file);

        return $Variables;
    }


    /**
     * saves the template variables in the cache
     *
     * @param Variables $Variables
     */
    public function saveVariables(Variables $Variables)
    {
        $Variables = serialize($Variables);
        $this->Engine->Cache()->save($this->getFileName($this->file), $Variables);
    }


    /**
     * returns the base Variables object
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getVariablesFromCache($file)
    {
        $file = $this->cleanExtension($file);
        $file = $this->getFileName($file);
        $file = $this->Engine->Cache()->getFile($file);

        return unserialize(file_get_contents($file));
    }


    /**
     * returns the name of the base variables file
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getFileName($file)
    {
        return str_replace($this->Engine->Config()->getExtension(), "variables." . $this->Engine->Config()->getExtension(), $file);
    }


    /**
     * make sure we have the template extension
     *
     * @param $file
     *
     * @return string
     */
    protected function cleanExtension($file)
    {
        $file = preg_replace('/\..*?$/', '', $file) . "." . $this->Engine->Config()->getExtension();

        return $file;
    }


    /**
     * @param Engine $Engine
     */
    public function setEngine(Engine $Engine)
    {
        $this->Engine = $Engine;
    }


}