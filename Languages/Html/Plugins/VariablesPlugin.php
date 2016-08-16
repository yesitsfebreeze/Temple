<?php

namespace Temple\Languages\Html\Plugins;

use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Buffer;
use Temple\Engine\Structs\Node\Node;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    /** @var  array $modifiers */
    private $modifiers = false;

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
     * @param bool   $addEcho
     *
     * @return string $output
     */
    private function replace($matches, $output, $addEcho = false)
    {

      /*  if ($echo) {
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
      */

        foreach ($matches[0] as $key => $match) {
            $path   = $this->getPath($matches[1][ $key ]);

            $buffer = new Buffer();

            $buffer->insertAfter('$this->Variables->get("');
            $buffer->insertAfter($path[0]);
            $buffer->insertAfter('"');
            if ($addEcho) {
                $buffer->insertAfter(",true,true");
            }
            $buffer->insertAfter(')');
            if ($path[1] != "") {
                $buffer->insertAfter($path[1]);
            }

            if ($this->modifiers !== false) {
                foreach ($this->modifiers as $modifier) {
                    // get modifier name
                    $name = explode(":",$modifier);
                    $name = $name[0];

                    // todo: get modifier arguments
                    $arguments = array();

                    array_unshift($arguments,$buffer);
                    // get arguments
                    $buffer = $this->Instance->EventManager()->notify("modifier.". $name,$arguments);
                }
            }

            if ($addEcho) {
                $buffer->insertBefore("echo ");
                $buffer->insertAfter(";");
            }

            $buffer->insertBefore("<?php ");
            $buffer->insertAfter(" ?>");

            $output = str_replace($match, $buffer->getContent(), $output);
        }

        return $output;
    }


    /**
     * within here we check if we have some modifiers or object methods
     *
     * @param $path
     *
     * @return array
     */
    private function getPath($path)
    {
        $this->modifiers = false;
        $path = explode("|", $path);

        // gather all modifiers
        if (sizeof($path) > 1) {
            $modifiers = $path;
            array_shift($modifiers);
            $this->modifiers = $modifiers;
        }

        // reset the path to continue
        $path = $path[0];

        $path = explode("->", $path);
        // if there is no object getter we set the second part od the path to an empty string
        if (!isset($path[1])) {
            $path[1] = "";
        } else {
            // if it's there we have to prefix it with the previously removed "->"
            $path[1] = "->" . $path[1];
        }

        return $path;
    }

}