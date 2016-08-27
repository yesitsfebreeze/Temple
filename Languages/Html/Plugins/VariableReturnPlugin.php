<?php

namespace Temple\Languages\Html\Plugins;

use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Variables;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class VariableReturnPlugin extends Event
{

    public function dispatch($input, Variables $Variables)
    {

        if (!is_string($input)) {
            return $input;
        }

        $pattern = $this->Instance->Config()->getLanguageConfig($this->getLanguage())->getVariablePattern();
        $pattern = explode('%', $pattern);
        $pattern = "/" . preg_quote($pattern[0]) . "(.*?)" . preg_quote($pattern[1]) . "/";
        preg_match_all($pattern, $input, $matches);

        if (!isset($matches[0][0])) {
            return $input;
        }
        if (sizeof($matches[0]) > 1) {
            throw new Exception(402,"Variable assignments can't be concatenated!");
        }

        $key = $matches[1][0];

        return $Variables->get($key);
    }

}