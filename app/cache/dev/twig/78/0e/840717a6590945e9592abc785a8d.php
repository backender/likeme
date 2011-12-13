<?php

/* likemeSpamBundle:Default:newSpam.html.twig */
class __TwigTemplate_780e840717a6590945e9592abc785a8d extends Twig_Template
{
    protected function doGetParent(array $context)
    {
        return false;
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<form action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_newSpam"), "html", null, true);
        echo "\" method=\"post\" ";
        echo $this->env->getExtension('form')->renderEnctype($this->getContext($context, "form"));
        echo ">
    ";
        // line 2
        echo $this->env->getExtension('form')->renderWidget($this->getContext($context, "form"));
        echo "

    <input type=\"submit\" />
</form>";
    }

    public function getTemplateName()
    {
        return "likemeSpamBundle:Default:newSpam.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
