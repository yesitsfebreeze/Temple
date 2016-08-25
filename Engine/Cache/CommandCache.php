<?php


namespace Temple\Engine\Cache;


use Temple\Engine\Console\Command;


/**
 * Class CommandCache
 *
 * @package Temple\Engine\Console
 */
class CommandCache extends BaseCache
{


    /**  @var string $cacheFileName */
    protected $cacheFile = "command.cache";


    /**
     * @param $name
     *
     * @return array
     */
    public function findCommands($name)
    {
        $cache = $this->getCache();
        $list  = array();
        foreach ($cache as $realCommandName => $unneeded) {
            $groupName   = explode(":", $name);
            $groupName   = reset($groupName);
            $commandName = explode(":", $realCommandName);
            $commandName = reset($commandName);
            if ($commandName == $groupName) {
                $list[] = $realCommandName;
            }
        }

        return $list;
    }


    /**
     * saves the command within the command cache
     *
     * @param      $value
     * @param null $identifier
     */
    public function save($value, $identifier = null)
    {
        $cache = $this->getCache();

        if ($value instanceof Command) {
            $className      = $value->getClassName();
            $name           = $value->getName();
            $path           = $value->getPath();
            $value          = array(
                "className" => $className,
                "path"      => $path
            );
            $cache[ $name ] = $value;
        }

        $this->saveCache($cache);
    }


    /**
     * remove the class from my cache file
     *
     * @param string $name
     *
     * @return bool
     */
    public function remove($name)
    {
        $cache = $this->getCache();

        if (isset($cache[ $name ])) {
            unset($cache[ $name ]);
        }

        $this->saveCache($cache);

        return true;
    }

}
