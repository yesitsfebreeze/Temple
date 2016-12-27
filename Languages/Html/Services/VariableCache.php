<?php

namespace Temple\Languages\Html\Services;


use Temple\Engine\Cache\VariablesBaseCache;


class VariableCache extends VariablesBaseCache
{

    /**
     * @param $value
     *
     * @return string
     */
    public function createGetterString($value)
    {
        return '<?php $this->getVariables()->get("' . $value .'") ?>';
    }

}