<?php

/* index.twig */
class __TwigTemplate_a0c326ceaf48c1cd195b299e6decb9ab224f9984e110aa349afebe277640e9ab extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
    <head>
        <title>
            ";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "
        </title>
        <link rel=\"stylesheet\" href=\"docs/assets/prod/style.css\">
    </head>
    <body>
        <header>

        </header>
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 4,  19 => 1,);
    }
}
/* <html>*/
/*     <head>*/
/*         <title>*/
/*             {{ title }}*/
/*         </title>*/
/*         <link rel="stylesheet" href="docs/assets/prod/style.css">*/
/*     </head>*/
/*     <body>*/
/*         <header>*/
/* */
/*         </header>*/
/*     </body>*/
/* </html>*/
/* */
