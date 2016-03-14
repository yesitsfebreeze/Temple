<?php

namespace Caramel;


use Caramel\Models\Node;
use Caramel\Services\Error;


/**
 * Class PluginImport
 *
 * @package     Caramel
 * @description handles file imports
 * @position    0
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class PluginImport extends Models\Plugin
{

    /**
     * @return int;
     */
    public function getPosition()
    {
        return 0;
    }


    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag.tag") == "import");
    }


    /**
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        $node->set("tag.display", false);

        $file = $this->getPath($node);
        debug($file);
        if ($file == $node->get("namespace")) {
            new Error("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
        }
        $cachePath = $this->caramel->template()->parse($file);

        # add the dependency
        $this->caramel->cache()->dependency($node->get("file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path
     *
     * @param Node $node
     * @return string $file
     */
    private function getPath($node)
    {
        # if the file has an absolute path
        $path     = $node->get("attributes");
        $relative = $path[0] != "/";

        if ($relative) {
            $path = $this->getParentPath($node) . $path;
        }

        return $path;
    }


    /**
     * returns the template path to the file which is importing
     *
     * @param Node $node
     * @return mixed
     */
    private function getParentPath($node)
    {
        $templates = $this->caramel->template()->dirs();
        $path      = explode("/", $node->get("file"));
        array_pop($path);
        $path = implode("/", $path) . "/";

        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}