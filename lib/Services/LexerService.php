<?php

namespace Caramel\Services;


use Caramel\Exception\CaramelException;
use Caramel\Models\DomModel;
use Caramel\Nodes\FunctionNodeModel;
use Caramel\Nodes\HtmlNodeModel;
use Caramel\Nodes\NodeModel;
use Caramel\Models\ServiceModel;
use Caramel\Repositories\StorageRepository;


/**
 * Class Lexer
 *
 * @package Caramel
 */
class LexerService extends ServiceModel
{

    /** @var DomModel $dom */
    private $dom;


    /**
     * returns DomModel object
     *
     * @param string   $filename
     * @param int|bool $level
     * @return array
     */
    public function lex($filename, $level = false)
    {

        # create a new dom model
        $this->dom = new DomModel();
        $filename  = str_replace("." . $this->configService->get("template.extension"), "", $filename);
        $this->dom->set("info.namespace", $filename);
        $this->dom->set("info.line", 1);
        $this->dom->set("info.indent.amount", 0);
        $this->dom->set("info.indent.char", "");
        $this->dom->set("info.templates", $this->templateService->findTemplates($filename));
        $this->dom->set("info.level", $level);
        $this->dom->set("info.file", $this->getFile($level, $filename));
        $this->dom->set("nodes", array());

        # and then process the file
        $this->process();

        return $this->dom;
    }


    /**
     * returns the matching file
     *
     * @param int|null $level
     * @param string   $filename
     * @return StorageRepository
     * @throws CaramelException
     */
    private function getFile($level, $filename)
    {

        $templates = $this->dom->get("info.templates");

        if (is_null($level)) {
            return reset($templates);
        }

        if (isset($templates[ $level ])) {
            return $templates[ $level ];
        }

        throw new CaramelException("Can't find template file for '" . $filename . "' on template level " . $level);

    }


    /**
     * creates a dom for the current file
     *
     * @return mixed
     */
    private function process()
    {
        $handle = fopen($this->dom->get("info.file"), "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $node = $this->createNode($line);
                $this->addNode($node);
                $this->dom->set("tmp.prev", $node);
            }
            $this->dom->set("info.line", $this->dom->get("info.line") + 1);
        }
        fclose($handle);
        $this->dom->delete("tmp");
    }


    /**
     * creates a node matching to the criteria
     *
     * @param $line
     * @return NodeModel
     * @throws CaramelException
     */
    private function createNode($line)
    {

        $this->nodeFactory->addConfig($this->configService);

        $node = $this->nodeFactory->create($line);
        $node->createNode($line);

        $node->set("info.namespace", $this->dom->get("info.namespace"));
        $node->set("info.level", $this->dom->get("info.level"));
        $node->set("info.line", $this->dom->get("info.line"));
        $node->set("info.file", $this->dom->get("info.file"));
        $node->set("info.parent", "test");

        if (!$node->has("tag.tag")) {
            throw new CaramelException("Node models must have a tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        }

        return $node;
    }


    /**
     * adds the node to the dom
     * parent/child logic is handled here
     *
     * @param NodeModel $node
     */
    private function addNode($node)
    {
        # root nodes
        if ($node->get("info.indent") == 0 || $node->get("info.line") == 1) {
            $node->set("info.parent", false);
            $this->dom->set("nodes." . $this->dom->get("info.line"), $node);
        } else {
            $indent     = $node->get("info.indent");
            $prevIndent = $this->dom->get("tmp.prev")->get("info.indent");

            # node position
            if ($indent > $prevIndent) {
                $this->deeper($node);
            }

            if ($indent < $prevIndent) {
                $this->higher($node);
            }

            if ($indent == $prevIndent) {
                $this->same($node);
            }

        }
    }


    /**
     * adds a node to the dom if has a deeper level
     * than the previous node
     *
     * @param NodeModel $node
     * @throws CaramelException
     */
    private function deeper($node)
    {
        $node->set("info.parent", $this->dom->get("tmp.prev"));
        if ($node->get("info.parent")->get("info.selfclosing")) {
            $tag = $node->get("info.parent")->get("tag.tag");
            throw new CaramelException("You can't have children in an $tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        } else {
            $this->children($this->dom->get("tmp.prev"), $node);
        }
    }


    /**
     * adds a node to the dom if has a higher level
     * than the previous node
     *
     * @param NodeModel $node
     */
    private function higher($node)
    {
        $parent = $this->parent($node);
        $node->set("info.parent", $parent);
        $this->children($parent, $node);
    }


    /**
     * adds a node to the dom if has the same  level
     * than the previous node
     *
     * @param NodeModel $node
     */
    private function same($node)
    {
        $parent = $this->dom->get("tmp.prev")->get("info.parent");
        $node->set("info.parent", $parent);
        $this->children($parent, $node);
    }


    /**
     * adds the passed node to children
     *
     * @param NodeModel $target
     * @param NodeModel $node
     * @throws \Exception
     */
    private function children($target, $node)
    {
        $children   = $target->get("children");
        $children[] = $node;
        $target->set("children", $children);
    }


    /**
     * returns the parent of the passed node
     *
     * @param NodeModel      $node
     * @param bool|NodeModel $parent
     * @return NodeModel
     */
    private function parent($node, $parent = false)
    {

        if (!$parent) {
            $temp = $this->dom->get("tmp.prev")->get("info.parent");
        } else {
            $temp = $parent->get("info.parent");
        }
        if ($temp->get("info.indent") == $node->get("info.indent")) {
            $temp = $temp->get("info.parent");

            return $temp;
        } else {
            return $this->parent($node, $temp);
        }
    }

}