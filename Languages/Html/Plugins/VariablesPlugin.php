<?php

namespace Temple\Languages\Html\Plugins;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Node\Node;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    /**
     * @param           $output
     * @param Node|null $node
     *
     * @return mixed
     */
    public function dispatch($output, Node $node = null)
    {


        $pattern = $this->Instance->Config()->getVariablePattern();
        $pattern = explode('%', $pattern);
        $pattern = "/" . preg_quote($pattern[0]) . "(.*?)" . preg_quote($pattern[1]) . "/";
        preg_match_all($pattern, $output, $matches);

        if (!is_null($node)) {
            if ($node->isFunction()) {
                return $this->replace($matches, $output);
            }
        }

        return $this->replace($matches, $output, true);
    }


    /**
     * replaces all variables in the string with the according type
     *
     * @param array  $matches
     * @param string $output
     * @param bool   $echo
     *
     * @return string $output
     */
    private function replace($matches, $output, $echo = false)
    {
        if ($echo) {
            $before = '<?php echo $this->Variables->get("';
        } else {
            $before = '<?php $this->Variables->get("';
        }

        $middle = '")';
        if ($echo) {
            $after = '; ?>';
        } else {
            $after = ' ?>';
        }

        foreach ($matches[0] as $key => $match) {
            $path   = $this->getPath($matches[1][ $key ]);
            $output = str_replace($match, $before . $path[0] . $middle . $path[1] . $after, $output);
        }

        return $output;
    }


    private function getPath($path)
    {
        $path = explode("->", $path);
        if (!isset($path[1])) {
            $path[1] = "";
        } else {
            $path[1] = "->" . $path[1];
        }

        return $path;
    }

}