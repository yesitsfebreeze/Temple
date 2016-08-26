<?php


namespace Temple\Engine\Structs\Node;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Dom;


/**
 * Class Node
 *
 * @package Temple\Engine\Structs
 */
abstract class Node extends Event implements NodeInterface
{

    /** @var  string $plain */
    protected $plain;

    /** @var  Dom */
    protected $Dom;

    /** @var  string $namespace */
    protected $namespace;

    /** @var  int $level */
    protected $level = 0;

    /** @var  int $line */
    protected $line = 0;

    /** @var  string $file */
    protected $file;

    /** @var  string $relativeFile */
    protected $relativeFile;

    /** @var  bool $function */
    protected $function = false;

    /** @var  bool $selfClosing */
    protected $selfClosing = false;

    /** @var  int $indent */
    protected $indent = 0;

    /** @var  Node $parent */
    protected $parent;

    /** @var  Node $previousNode */
    protected $previousNode;

    /** @var  array $children */
    protected $children = array();

    /** @var  bool $commentNode */
    protected $commentNode = false;

    /** @var  bool $showComment */
    protected $showComment = true;


    /**
     * @param array $plain
     * @param Dom   $Dom
     *
     * @return Node|array
     */
    public function dispatch($plain = null, Dom $Dom = null)
    {
        if ($plain instanceof Node) {
            return $plain;
        }

        $this->setPlain($plain);
        $this->setDom($Dom);
        $this->setPreviousNode($Dom->getPreviousNode());
        $this->setNamespace($Dom->getNamespace());
        $this->setLevel($Dom->getLevel());
        $this->setLine($Dom->getCurrentLine());
        $this->setFile($Dom->getFile());
        $this->setRelativeFile(str_replace($_SERVER['DOCUMENT_ROOT'], "", $Dom->getFile()));


        if ($this->check()) {
            return $this;
        } else {
            return array($plain, $Dom);
        }
    }


    /**
     * @return Dom
     */
    public function getDom()
    {
        return $this->Dom;
    }


    /**
     * @param Dom $Dom
     *
     * @return Dom
     */
    public function setDom(Dom $Dom)
    {
        return $this->Dom = $Dom;
    }


    /**
     * must return true or false if the node should be used
     *
     * @throws Exception
     */
    public function check()
    {
        throw new Exception(1, "Please implement the check method for %" . $this->getName() . "%!");
    }


    /**
     * compiles children nodes and returns the output
     *
     * @return string
     * @throws Exception
     */
    public function compileChildren()
    {
        $output   = "";
        $language = $this->Dom->getLanguage()->getConfig()->getName();

        /** @var Node $child */
        foreach ($this->getChildren() as $child) {

            $nodeOutput = $child->compile();
            // this makes sure that if we have no events the right value gets returned
            $nodeOutput = $this->EngineWrapper->EventManager()->dispatch($language, "plugin.nodeOutput", array($nodeOutput, $child));
            if (!is_string($nodeOutput) && !is_array($nodeOutput)) {
                throw new Exception(600, "There went something wrong with the %plugin.nodeOutput% event!");
            } else if (is_array($nodeOutput)) {
                $nodeOutput = $nodeOutput[0];
            }

            $output .= $nodeOutput;
        }

        return $output;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return get_class($this);
    }


    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }


    /**
     * @param $namespace
     *
     * @return mixed
     */
    public function setNamespace($namespace)
    {
        return $this->namespace = $namespace;
    }


    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * @param $level
     *
     * @return mixed
     */
    public function setLevel($level)
    {
        return $this->level = $level;
    }


    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }


    /**
     * @param $line
     *
     * @return mixed
     */
    public function setLine($line)
    {
        return $this->line = $line;
    }


    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param $file
     *
     * @return mixed
     */
    public function setFile($file)
    {
        return $this->file = $file;
    }


    /**
     * @return string
     */
    public function getRelativeFile()
    {
        return $this->relativeFile;
    }


    /**
     * @param $relativeFile
     *
     * @return mixed
     */
    public function setRelativeFile($relativeFile)
    {
        return $this->relativeFile = $relativeFile;
    }


    /**
     * @return boolean
     */
    public function isFunction()
    {
        return $this->function;
    }


    /**
     * @param boolean $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }


    /**
     * @return boolean
     */
    public function isSelfClosing()
    {
        return $this->selfClosing;
    }


    /**
     * @param $selfClosing
     *
     * @return mixed
     */
    public function setSelfClosing($selfClosing)
    {
        return $this->selfClosing = $selfClosing;
    }


    /**
     * @return float|int
     * @throws Exception
     */
    public function getIndent()
    {
        # get tab or space whitespace form the line start
        $whitespace = substr($this->plain, 0, strlen($this->plain) - strlen(ltrim($this->plain)));

        # divide our counted characters trough the amount
        # we used to indent in the first line
        # this should be a non decimal number
        $indentCharacter = $this->Dom->getLanguage()->getConfig()->getIndentCharacter();
        $indentCharacter = ($indentCharacter == "tab") ? "	" : " ";
        if (strlen($whitespace) > 0) {
            if (preg_match('/[^' . preg_quote($indentCharacter) . ']/', $whitespace)) {
                throw new Exception(4, "Please use the %" . $this->Dom->getLanguage()->getConfig()->getIndentCharacter() . "% character for indentation!", $this->getFile(), $this->getLine());
            } else {
                $indent = substr_count($whitespace, $indentCharacter);
                $indent = $indent / $this->Dom->getLanguage()->getConfig()->getIndentAmount();
                # if we have a non decimal number return how many times we indented
                if (is_int($indent)) return $indent;
            }
        } else {
            return 0;
        }

        # else throw an error since the amount of characters doesn't match
        throw new Exception(4, "Indent isn't matching!", $this->getFile(), $this->getLine());
    }


    /**
     * returns html tag
     *
     * @return string
     */
    public function getTag()
    {
        preg_match("/^(.*?)(?:$|\s)/", trim($this->plain), $tag);
        $tag = trim($tag[0]);

        return $tag;
    }


    /**
     * @param $indent
     *
     * @return mixed
     */
    public function setIndent($indent)
    {
        return $this->indent = $indent;
    }


    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }


    /**
     * @param $parent
     *
     * @return mixed
     */
    public function setParent($parent)
    {
        return $this->parent = $parent;
    }


    /**
     * @return Node
     */
    public function getPreviousNode()
    {
        return $this->previousNode;
    }


    /**
     * @param Node $previousNode
     */
    public function setPreviousNode($previousNode)
    {
        $this->previousNode = $previousNode;
    }


    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }


    /**
     * @param $children
     *
     * @return mixed
     */
    public function setChildren($children)
    {
        return $this->children = $children;
    }


    /**
     * @return string
     */
    public function getPlain()
    {
        return $this->plain;
    }


    /**
     * @param $plain
     *
     * @return mixed
     */
    public function setPlain($plain)
    {
        return $this->plain = $plain;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return trim(preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain)));
    }


    /**
     * @return boolean
     */
    public function isCommentNode()
    {
        return $this->commentNode;
    }


    /**
     * @param boolean $commentNode
     */
    public function setCommentNode($commentNode)
    {
        $this->commentNode = $commentNode;
    }


    /**
     * @return boolean
     */
    public function isShowComment()
    {
        return $this->showComment;
    }


    /**
     * @param boolean $showComment
     */
    public function setShowComment($showComment)
    {
        $this->showComment = $showComment;
    }


}