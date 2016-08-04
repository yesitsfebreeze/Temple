<?php

namespace Temple\Languages\Core\Plugins;


use Temple\Engine\EventManager\Event;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    public function dispatch($output)
    {
        $output = preg_replace("/\{\{(.*?)\}\}/", "<?php echo \$this->Variables->get('$1'); ?>", $output);

        return $output;
    }

}