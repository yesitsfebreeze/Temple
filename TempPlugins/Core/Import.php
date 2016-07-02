<?php

namespace Pavel\Plugin;


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
     * @return int;
     */
    public function position()
    {
        return 2;
    }


    public function isProcessor()
    {
        return true;
    }


    /**
     * @param HtmlNode $node
     * @return bool
     */
    public function check(HtmlNode $node)
    {
        $this->configService->extend("self_closing", "import");

        return ($node->get("tag.tag") == "import");
    }


    /**
     * @param HtmlNode $node
     * 
*@return HtmlNode $node
     * @throws Exception
     */
    public function process(HtmlNode $node)
    {
        $node->set("tag.display", false);

        $file = $this->getPath($node);
        if ($file == $node->get("namespace")) {
            throw new Exception("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
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
     * @param HtmlNode $node
     * @return string $file
     */
    private function getPath(HtmlNode $node)
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
     * @param HtmlNode $node
     * @return mixed
     */
    private function getParentPath(HtmlNode $node)
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