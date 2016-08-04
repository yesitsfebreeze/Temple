<?php

namespace Temple\Languages\Html\Plugins;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Node\Node;
use Temple\Languages\Core\Nodes\BlockNode;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class CleanCommentsPlugin extends Event
{

    /** @var  Dom $Dom */
    private $Dom;


    public function dispatch(Dom $Dom)
    {
        $this->Dom       = $Dom;


        return $Dom;
    }


}