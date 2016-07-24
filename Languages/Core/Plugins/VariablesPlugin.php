<?php

namespace Underware\Languages\Core\Plugins;


use Underware\Engine\EventManager\Event;


/**
 * Class VariablesPlugin
 *
 * @package Underware\Languages\Core\Plugins
 */
class VariablesPlugin extends Event
{

    public function dispatch($output)
    {
        $output = preg_replace("/\{\{(.*?)\}\}/", "<?php echo \$this->Variables->get('$1'); ?>", $output);

        return $output;
    }

}