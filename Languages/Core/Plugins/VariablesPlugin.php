<?php

namespace WorkingTitle\Languages\Core\Plugins;


use WorkingTitle\Engine\EventManager\Event;


/**
 * Class VariablesPlugin
 *
 * @package WorkingTitle\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    public function dispatch($output)
    {
        $output = preg_replace("/\{\{(.*?)\}\}/", "<?php echo \$this->Variables->get('$1'); ?>", $output);

        return $output;
    }

}