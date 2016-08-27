<?php

namespace Temple\Engine\Cache;


/**
 * Class ConfigCache
 *
 * @package Temple\Engine\Cache
 */
class EventCache extends BaseCache
{
    protected $cacheFile = "event.cache";


    /**
     * @param      $value
     * @param int  $identifier
     *
     * @return bool
     */
    public function changed($value, $identifier = 0)
    {
        $result = $this->get($value, $identifier) !== md5($value . "");

        if ($result) {
            $this->save($value);
        }

        return $result;
    }


    /**
     * @param $value
     *
     * @return string
     */
    protected function realSave($value)
    {
        return md5($value);
    }

}