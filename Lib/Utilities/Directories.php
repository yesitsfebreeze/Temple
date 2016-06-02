<?php

namespace Temple\Utilities;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;

/**
 * Class Directories
 *
 * @package Temple
 */
class Directories extends DependencyInstance
{

    /** @var  Config $Config */
    protected $Config;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config"
        );
    }


    /**
     * adds the directory into the config for the respective type
     *
     * @param $dir
     * @param $type
     * @return mixed
     * @throws TempleException
     */
    public function add($dir, $type)
    {
        if ($this->Config->has("dirs." . $type)) {
            $this->Config->set("dirs." . $type,$dir);
            return $this->Config->get("dirs." . $type);
        }
        throw new TempleException("Directory could not be added, because it doesn't exist!", $dir);

    }


    public function remove($dir, $type)
    {
        # removes the directory into the config for the respective type
    }


    public function get($type)
    {
        # get all directories for the passed type
    }


}