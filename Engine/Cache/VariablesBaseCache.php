<?php


namespace Temple\Engine\Cache;


use Temple\Engine\Instance;
use Temple\Engine\Structs\Variables;


/**
 * Class ClassCache
 *
 * @package Temple\Engine\Cache
 */
abstract class VariablesBaseCache
{

    /** @var  Instance $Instance */
    protected $Instance;

    /** @var  string $file */
    protected $file;


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    public function get()
    {
        $config =  $this->Languages->getLangaugeConfig($this->file);
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
        $this->Instance->TemplateCache()->dump($this->getFileName($this->file), $Variables);
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
        $file = $this->Instance->TemplateCache()->get($file);

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
        return str_replace($this->Instance->Config()->getExtension(), "_varaibles." . $this->Instance->Config()->getExtension(), $file);
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
        $file = preg_replace('/\..*?$/', '', $file) . "." . $this->Instance->Config()->getExtension();

        return $file;
    }


    /**
     * @param Instance $Instance
     */
    public function setInstance(Instance $Instance)
    {
        $this->Instance = $Instance;
    }


}