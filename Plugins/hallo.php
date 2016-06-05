<?php

namespace Temple\Plugin;

use Temple\Models\Nodes\BaseNode;
use Temple\Models\Plugins\Plugin;


class Hallo extends Plugin {

    public function position()
    {
        return 2;
    }


    public function process(BaseNode $node)
    {
        return $node;
    }

}