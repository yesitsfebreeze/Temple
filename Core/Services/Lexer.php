<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;
use Caramel\Models\Dom;
use Caramel\Models\Node;
use Caramel\Models\Storage;


/**
 * Class Lexer
 *
 * @package Caramel
 */
class Lexer extends Service
{

    /** @var Dom $dom */
    private $dom;


    /**
     * returns Dom object
     *
     * @param string   $file
     * @param int|bool $level
     * @return array
     */
    public function lex($file, $level = false)
    {
        $this->dom = new Dom();
        $this->prepare($file, $level);
        $this->process();
        $this->dom->delete("temp");

        return $this->dom;
    }


    /**
     * set the default values for our dom
     *
     * @param string  $file
     * @param integer $level
     */
    private function prepare($file, $level)
    {
        $namespace = str_replace("." . $this->config->get("extension"), "", $file);
        $this->dom->set("namespace", $namespace);
        $this->dom->set("nodes", array());
        $file = $this->template($file, $level);
        $this->dom->set("template.level", $file->get("level"));
        $this->dom->set("template.file", $file->get("file"));
        $this->dom->set("template.line", 1);
        $this->dom->set("template.indent.amount", 0);
        $this->dom->set("template.indent.char", "");
    }


    /**
     * returns the matching file template
     *
     * @param $file
     * @param $level
     * @return Storage
     * @throws CaramelException
     */
    private function template($file, $level)
    {

        $template  = new Storage();
        $templates = $this->helpers->templates($file);

        if ($level !== false) {
            if (isset($templates[ $level ])) {
                $template->set("level", $level);
                $template->set("file", $templates[ $level ]);
            } else {
                throw new CaramelException("Can't find template file for '" . $file . "' on template level " . $level);
            }
        } else {
            foreach ($templates as $level => $file) {
                $template->set("level", $level);
                $template->set("file", $file);

                return $template;
            }
        }

        return $template;
    }


    /**
     * creates a dom for the current file
     *
     * @return mixed
     */
    private function process()
    {
        $handle = fopen($this->dom->get("template.file"), "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $info = $this->info($line);
                $node = $this->node($line, $info);
                $this->add($node);
                $this->dom->set("template.prev", $node);
            }
            $this->dom->set("template.line", $this->dom->get("template.line") + 1);
        }
        fclose($handle);
    }


    /**
     * returns an array with information about the current node
     *
     * @param     $line
     * @return Storage
     */
    private function info($line)
    {

        $info = new Storage();
        $info->set("indent", $this->indent($line));
        $line = trim($line);
        $info->set("tag", $this->tag($line));
        $info->set("attributes", $this->attributes($line, $info));
        $info->set("content", $this->content($line, $info));
        $info->set("selfclosing", $this->selfclosing($info));

        return $info;
    }


    /**
     * returns the indent of the current line
     * also initially sets the indent character and amount
     *
     * @param     $line
     * @return float|int
     * @throws CaramelException
     */
    private function indent($line)
    {
        # get tab or space whitespace form the line start
        $whitespace = substr($line, 0, strlen($line) - strlen(ltrim($line)));

        # initially set the indent variables
        if ($this->dom->get("template.indent.amount") == 0) {
            $this->dom->set("template.indent.amount", strlen($whitespace));
            $this->dom->set("template.indent.char", $whitespace[0]);
        }

        # if the indent variables are set
        if ($this->dom->get("template.indent.char") != "" && $this->dom->get("template.indent.amount") != 0) {

            # divide our counted characters trough the amount
            # we used to indent in the first line
            # this should be a non decimal number
            $indent = substr_count($whitespace, $this->dom->get("template.indent.char"));
            $indent = $indent / $this->dom->get("template.indent.amount");
            # if we have a non decimal number return how many times we indented
            if ("integer" == gettype($indent)) return $indent;

            # else throw an error since the amount of characters doesn't match
            throw new CaramelException("Indent isn't matching!", $this->dom->get("template.file"), $this->dom->get("template.line"));
        }

        return 0;
    }


    /**
     * returns the tag for the current line
     *
     * @param string $line
     * @return string
     */
    private function tag($line)
    {
        # match all characters until a word boundary or space or end of the string
        preg_match("/^(.*?)(?:$| )/", $line, $tag);
        $tag = trim($tag[0]);

        return $tag;
    }


    /**
     * returns the attributes for the current line
     *
     * @param string  $line
     * @param Storage $info
     * @return string
     */
    private function attributes($line, $info)
    {
        # replace the tag from the beginning of the line and then trim the string
        $tag        = preg_quote($info->get("tag"));
        $attributes = trim(preg_replace("/^$tag/", "", $line));
        $attributes = explode(">", $attributes);

        return $attributes[0];
    }


    /**
     * returns the content for the current line
     *
     * @param string  $line
     * @param Storage $info
     * @return string
     */
    private function content($line, $info)
    {
        # replace the tag from the beginning of the line and then trim the string
        $tag = $info->get("tag");
        if ($this->helpers->str_find(substr($line, 1), ">")) {
            $content = trim(preg_replace("/^$tag.*?>/", "", $line));
            $content = trim($content) . " ";
        } else {
            $content = "";
        }

        return $content;
    }


    /**
     * returns if the current line has a self closing tag
     *
     * @param Storage $info
     * @return string
     */
    private function selfclosing($info)
    {
        # check if our tag is in the self closing array set in the config
        if (in_array($info->get("tag"), $this->config->get("self_closing"))) return true;

        return false;
    }


    /**
     * creates a new node from a line
     *
     * @param string  $line
     * @param Storage $info
     * @return Node $node
     */
    private function node($line, $info)
    {
        # create a new storage for the node
        $node = new Node();

        # add everything we need to our node
        $node->set("tag.tag", $info->get("tag"));
        $node->set("tag.display", true);
        $node->set("tag.opening.display", true);
        $node->set("tag.opening.prefix", "<");
        $node->set("tag.opening.tag", $info->get("tag"));
        $node->set("tag.opening.postfix", ">");
        $node->set("tag.closing.display", true);
        $node->set("tag.closing.prefix", "</");
        $node->set("tag.closing.tag", $info->get("tag"));
        $node->set("tag.closing.postfix", ">");
        $node->set("namespace", $this->dom->get("namespace"));
        $node->set("level", $this->dom->get("template.level"));
        $node->set("line", $this->dom->get("template.line"));
        $node->set("plain", $line);
        $node->set("indent", $info->get("indent"));
        $node->set("attributes", $info->get("attributes"));
        $node->set("content", $info->get("content"));
        $node->set("display", true);
        $node->set("plugins", true);
        $node->set("selfclosing", $info->get("selfclosing"));
        $node->set("children", array());
        $node->set("file", $this->dom->get("template.file"));
        $node->set("dom", $this->dom);

        return $node;
    }


    /**
     * adds the node to the dom
     * parent/child logic is handled here
     *
     * @param Node $node
     */
    private function add($node)
    {
        # root nodes
        if ($node->get("indent") == 0 || $node->get("line") == 1) {
            $node->delete("parent");
            $this->dom->set("nodes." . $this->dom->get("template.line"), $node);
        } else {
            $indent     = $node->get("indent");
            $prevIndent = $this->dom->get("template.prev")->get("indent");
            # node position
            if ($indent > $prevIndent) $this->deeper($node);
            if ($indent < $prevIndent) $this->higher($node);
            if ($indent == $prevIndent) $this->same($node);

        }
    }


    /**
     * adds a node to the dom if has a deeper level
     * than the previous node
     *
     * @param Node $node
     * @throws CaramelException
     */
    private function deeper($node)
    {
        $node->set("parent", $this->dom->get("template.prev"));
        if ($node->get("parent")->get("selfclosing")) {
            $tag = $node->get("parent")->get("tag.tag");
            throw new CaramelException("You can't have children in an $tag!", $this->dom->get("template.file"), $this->dom->get("template.line"));
        } else {
            $this->children($this->dom->get("template.prev"), $node);
        }
    }


    /**
     * adds a node to the dom if has a higher level
     * than the previous node
     *
     * @param Node $node
     */
    private function higher($node)
    {
        $parent = $this->parent($node);
        $node->set("parent", $parent);
        $this->children($parent, $node);
    }


    /**
     * adds a node to the dom if has the same  level
     * than the previous node
     *
     * @param Node $node
     */
    private function same($node)
    {
        $parent = $this->dom->get("template.prev")->get("parent");
        $node->set("parent", $parent);
        $this->children($parent, $node);
    }


    /**
     * adds the passed node to children
     *
     * @param Node $target
     * @param Node $node
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
     * @param Node      $node
     * @param bool|Node $parent
     * @return Node
     */
    private function parent($node, $parent = false)
    {

        if (!$parent) {
            $temp = $this->dom->get("template.prev")->get("parent");
        } else {
            $temp = $parent->get("parent");
        }
        if ($temp->get("indent") == $node->get("indent")) {
            $temp = $temp->get("parent");

            return $temp;
        } else {
            return $this->parent($node, $temp);
        }
    }

}