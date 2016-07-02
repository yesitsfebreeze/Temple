<?php

namespace Pavel\Plugin;


use Pavel\Models\Plugin;


class Debug extends Plugin
{

    public function isProcessor()
    {
        return true;
    }


    /** @var string $type */
    private $type = "vars";

    /** @var string $search */
    private $search = false;

    /**
     * @var string $style
     */
    private $style = "<style>
            body {
                font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;
                padding: 100px 50px;
                font-weight: 200;
                color: #3C4754;
                background-color: #f1f1f1;
            }
            body > .item {padding: 15px 0;}
            body > .item > span {font-size:18px;}
            .item span {font-size:14px;}
            .item > .item {padding-left: 30px}
            .delimiter {opacity:0.5}
            .type {opacity:0.3}
            .name {color:#59A9E2}
            .equals {opacity:0.6}
            h1 {font-weight:100}
            h1 {font-size:30px}
            h1 {padding:30px 0}
            h2 {font-weight:200}
            h2 {font-size:25px}
            h2 {padding:20px 0}
            .name {color:#59A9E2}
    </style>";


    /*** @inheritdoc */
    public function position()
    {
        return 10;
    }


    /*** @inheritdoc */
    public function check($node)
    {
        $tag = $node->get("tag.tag");

        return ($tag == "debug");
    }


    /*** @inheritdoc */
    public function process($node)
    {
        $node->set("display", false);
        $this->configService->set("debug_enabled", true);
        $type = explode(" ", $node->get("attributes"))[0];
        if ($type == "config" || $type == "vars" || $type == "infos") {
            $this->type = $type;
        } elseif ($type[0] == $this->configService->get("variable_symbol")) {
            $this->type   = "vars";
            $this->search = substr($type, 1);
        }

        return $node;
    }


    /** @inheritdoc */
    public function processOutput($output)
    {

        if ($this->configService->has("debug_enabled")) {
            $window = "<html>";
            $window .= "<head>";
            $window .= "<title>";
            $window .= "Pavel.debug";
            $window .= "</title>";
            $window .= $this->style;
            $window .= "</head>";
            $window .= "<body>";
            if ($this->type == "vars") {

                $vars = $this->vars->get();
                if (!empty($vars)) {
                    if ($this->search) {
                        // TODO: FIND
                        $vars = $this->vars->get($this->search);
                    } else {
                        $vars = $this->vars->get();
                    }
                    if (is_array($vars)) {
                        foreach ($vars as $name => $var) {
                            $window .= $this->parseVar($name, $var, $this->configService->get("variable_symbol"));
                        }
                    } else {
                        $window .= $this->parseVar($this->search, $vars, $this->configService->get("variable_symbol"));
                    }
                } else {
                    $window .= "<h1>";
                    $window .= "No Variables set yet.";
                    $window .= "</h1>";
                }
            } elseif ($this->type == "config") {
                foreach ($this->configService->get() as $name => $var) {
                    $window .= $this->parseVar($name, $var, "");
                }
            } elseif ($this->type == "info") {
                $window .= "<h3>";
                $window .= "Additional Info";
                $window .= "</h3>";
                $window .= "<div>";
                $window .= "Version: 1.1.0";
                $window .= "</div>";
                $window .= "<div>";
                $window .= "<a href='https://github.com/hvlmnns/Pavel' title='gitHub' target='_blank'>gitHub</a>";
                $window .= "</div>";
                $window .= "<div>";
                $window .= "<a href='http://hvlmnns.github.io/Pavel/' title='Documentation' target='_blank'>Documentation</a>";
                $window .= "</div>";
            }


            $window .= "</body>";
            $window .= "</html>";

            $output .= $this->createDebugWindow($window);
        }

        return $output;
    }


    /**
     * parses a tree of arrays
     *
     * @param $name
     * @param $value
     * @param $symbol
     * @return string
     */
    private function parseVar($name, $value, $symbol)
    {
        $result = "";
        if (is_array($value)) {
            return $this->markup("array", $name, $value, $symbol, function ($value) {
                $markup = "";
                foreach ($value as $name => $item) {
                    $markup .= $this->parseVar($name, $item, "");
                }

                return $markup;
            });
        } elseif (is_bool($value)) {
            $value = ($value) ? "true" : "false";

            return $this->markup("object", $name, $value . "", $symbol);
        } elseif (is_object($value)) {
            return $this->markup("object", $name, get_class($value) . "()" . $symbol);
        } elseif (is_integer($value)) {
            return $this->markup("object", $name, $value . "", $symbol);
        } elseif (is_string($value)) {
            return $this->markup("string", $name, '"' . $value . '"', $symbol);
        }

        return $result;
    }


    /**
     * the markup for a variable
     *
     * @param               $type
     * @param               $name
     * @param               $value
     * @param string        $symbol
     * @param \Closure|NULL $callback
     * @return string
     */
    private function markup($type, $name, $value, $symbol = "", \Closure $callback = null)
    {
        $markup = "<div class='item $type'>";
        $markup .= "<span class='type'>" . $type . "</span>";
        $markup .= "<span class='delimiter'>:</span>";
        $markup .= "<span class='name'>" . $symbol . $name . "</span>";
        if ($type != "array") {
            $markup .= "<span class='equals'> = </span>";
        }
        if (is_callable($callback)) {
            $markup .= $callback($value);
        } else {
            $markup .= "<span class='value'>" . $value . "</span>";
        }
        $markup .= "</div>";

        return $markup;
    }


    /**
     * creates the new debug window
     *
     * @param $vars
     * @return string
     */
    private function createDebugWindow($vars)
    {
        $randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

        ob_start();
        ?>
        <a id="cdbl<?php echo $randomString; ?>">
            <script type="text/javascript">
                (function() {
                    var PavelDebugLink<?php echo $randomString; ?> = document.getElementById('cdbl<?php echo $randomString;?>');
                    PavelDebugLink<?php echo $randomString; ?>.addEventListener("click", function() {
                        var debugWindow<?php echo $randomString; ?> = window.open('', "Pavel Debug", '_blank');
                        debugWindow<?php echo $randomString; ?>.document.body.innerHTML = <?php echo json_encode($vars); ?>;
                    });
                    PavelDebugLink<?php echo $randomString; ?>.click();
                })();
            </script>
        </a>
        <?php
        $window = ob_get_contents();
        ob_end_clean();

        return $window;
    }

}