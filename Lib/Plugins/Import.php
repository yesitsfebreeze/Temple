<?php

namespace Pavel\Plugins;


use Pavel\Exception\Exception;
use Pavel\Models\HtmlNode;
use Pavel\Models\Plugin;


/**
 * Class PluginImport
 *
 * @package     Pavel
 * @description handles file imports
 * @position    0
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Import extends Plugin
{

    /**
     * attribute mapping
     *
     * @return array
     */
    public function attributes()
    {
        return array(
            "file"
        );
    }


    /**
     * @param HtmlNode $node
     *
     * @return bool
     */
    public function check(HtmlNode $node)
    {
        $this->Instance->Config()->extend("selfClosing", "import");

        return ($node->get("tag.definition") == "import");
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode $node
     * @throws Exception
     */
    public function process($node)
    {
        if (!$this->check($node)) {
            return $node;
        }

        $node->set("tag.display", false);

        $file = $this->getPath($node);
        if ($file == $node->get("info.namespace")) {
            throw new Exception("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
        }
        $cachePath = $this->Instance->Template()->fetch($file);

        # add the dependency
        $this->Instance->Cache()->addDependency($node->get("info.file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path
     *
     * @param HtmlNode $node
     *
     * @return string $file
     */
    private function getPath(HtmlNode $node)
    {

        # if the file has an absolute path
        $path     = $this->attrs["file"];
        $relative = $path[0] != "/";

        if ($relative) {
            $path = $this->getParentPath($node) . $path;
        }

        return $path;
    }


    /**
     * returns the template path to the file which is importing
     *
     * @param HtmlNode $node
     *
     * @return mixed
     */
    private function getParentPath(HtmlNode $node)
    {
        $templates = $this->Instance->Template()->getDirectories();
        $path      = explode("/", $node->get("info.file"));
        array_pop($path);
        $path = implode("/", $path) . "/";

        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}