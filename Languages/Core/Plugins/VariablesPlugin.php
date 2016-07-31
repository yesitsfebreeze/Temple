<?php

namespace Rite\Languages\Core\Plugins;


use Rite\Engine\EventManager\Event;


/**
 * Class VariablesPlugin
 *
 * @package Rite\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    public function dispatch($output)
    {
        $output = preg_replace("/\{\{(.*?)\}\}/", "<?php echo \$this->Variables->get('$1'); ?>", $output);

        return $output;
    }

}