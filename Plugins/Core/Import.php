<?php

namespace Caramel\Plugins\Core;


use Caramel\Exceptions\CaramelException;
use Caramel\Models\Node;
use Caramel\Plugin\Plugin;


/**
 * Class PluginImport
 *
 * @package     Caramel
 * @description handles file imports
 * @position    0
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Import extends Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 0;
    }


    /**
     * @param Node $node
     * @return bool
     */
    public function check(Node $node)
    {
        $this->config->extend("self_closing","import");
        return ($node->get("tag.tag") == "import");
    }


    /**
     * @param Node $node
     * @return Node $node
     * @throws CaramelException
     */
    public function process(Node $node)
    {
        $node->set("tag.display", false);

        $file = $this->getPath($node);
        if ($file == $node->get("namespace")) {
            throw new CaramelException("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
        }
        $cachePath = $this->template->parse($file);

        # add the dependency
        $this->cache->dependency($node->get("file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path
     *
     * @param Node $node
     * @return string $file
     */
    private function getPath(Node $node)
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
    private function getParentPath(Node $node)
    {
        $templates = $this->template->dirs();
        $path      = explode("/", $node->get("file"));
        array_pop($path);
        $path = implode("/", $path) . "/";

        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}