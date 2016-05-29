<?php

namespace Temple\Plugin\Core;


use Temple\Exceptions\TempleException;
use Temple\BaseClasses\PluginBaseClass;
use Temple\Models\NodeModel;



/**
 * Class PluginImport
 *
 * @package     Temple
 * @description handles file imports
 * @position    0
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Import extends PluginBaseClass
{

    /**
     * @return int;
     */
    public function position()
    {
        return 0;
    }

    /** @inheritdoc */
    public function forTags()
    {

    }

    /** @inheritdoc */
    public function forNodes()
    {

    }


    /**
     * @param NodeModel $node
     * @return bool
     */
    public function check(NodeModel $node)
    {
        $this->configService->extend("self_closing","import");
        return ($node->get("tag.tag") == "import");
    }


    /**
     * @param NodeModel $node
     * @return NodeModel $node
     * @throws TempleException
     */
    public function process(NodeModel $node)
    {
        $node->set("tag.display", false);

        $file = $this->getPath($node);
        if ($file == $node->get("namespace")) {
            throw new TempleException("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
        }
        $cachePath = $this->templateService->parse($file);

        # add the dependency
        $this->cacheService->dependency($node->get("file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path

     *
*@param NodeModel $node
     * @return string $file
     */
    private function getPath(NodeModel $node)
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
*@param NodeModel $node
     * @return mixed
     */
    private function getParentPath(NodeModel $node)
    {
        $templates = $this->templateService->dirs();
        $path      = explode("/", $node->get("file"));
        array_pop($path);
        $path = implode("/", $path) . "/";

        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}