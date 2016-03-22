<?php

/* api/index.twig */
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
            <div class=\"logo\">
                ";
        // line 12
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "
            </div>
            <div class=\"menu\">
                <nav>

                </nav>
            </div>
        </header>
        ";
        // line 20
        $this->loadTemplate($this->getAttribute((isset($context["includes"]) ? $context["includes"] : null), "index", array(), "array"), "api/index.twig", 20)->display($context);
        // line 21
        echo "    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "api/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 21,  46 => 20,  35 => 12,  24 => 4,  19 => 1,);
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
/* */
/*         <header>*/
/*             <div class="logo">*/
/*                 {{ title }}*/
/*             </div>*/
/*             <div class="menu">*/
/*                 <nav>*/
/* */
/*                 </nav>*/
/*             </div>*/
/*         </header>*/
/*         {% include includes["index"] %}*/
/*     </body>*/
/* </html>*/
/* */
