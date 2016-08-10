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
     * todo: need to split by -> to enable object orientation
     * 
     * @param           $output
     * @param Node|null $node
     *
     * @return mixed
     */
    public function dispatch($output,Node $node = null)
    {

        if (!is_null($node))  {
            if ($node->isFunction()) {
                return preg_replace("/\{\{(.*?)\}\}/", "<?php \$this->Variables->get('$1') ?>", $output);
            }
        }

        return preg_replace("/\{\{(.*?)\}\}/", "<?php echo \$this->Variables->get('$1'); ?>", $output);
    }

}