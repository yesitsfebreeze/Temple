<?php

namespace Temple\Languages\Html\Modifiers;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Buffer;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class SizeofModifier extends Event
{

    public function dispatch(Buffer $buffer)
    {
        $buffer->insertBefore("<?php sizeof(");
        $buffer->insertAfter(") ?>");
        return $buffer;
    }

}