<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/tech/templates/page/page.html.twig */
class __TwigTemplate_9c17c5255f50c874cb6e844100e67a2b278bbf09387fd07893190f3239a4e892 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453 = $this->extensions["Drupal\\webprofiler\\Twig\\Extension\\ProfilerExtension"];
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453->enter($__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "themes/tech/templates/page/page.html.twig"));

        // line 53
        echo "<div id=\"page-wrapper\">
  <div id=\"page\">
    <header id=\"header\" class=\"header\" role=\"banner\">
      <div class=\"section layout-container clearfix\">
        ";
        // line 57
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "secondary_menu", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
        echo "
        ";
        // line 58
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 58), 58, $this->source), "html", null, true);
        echo "
        ";
        // line 59
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 59), 59, $this->source), "html", null, true);
        echo "
      </div>
    </header>
    ";
        // line 62
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 62)) {
            // line 63
            echo "      <div class=\"highlighted\">
        <aside class=\"layout-container section clearfix\" role=\"complementary\">
          ";
            // line 65
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
            echo "
        </aside>
      </div>
    ";
        }
        // line 69
        echo "    ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_top", [], "any", false, false, true, 69)) {
            // line 70
            echo "      <div class=\"featured-top\">
        <aside class=\"featured-top__inner section layout-container clearfix\" role=\"complementary\">
          ";
            // line 72
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_top", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
            echo "
        </aside>
      </div>
    ";
        }
        // line 76
        echo "    <div id=\"main-wrapper\" class=\"layout-main-wrapper layout-container clearfix\">
      <div id=\"main\" class=\"layout-main clearfix\">
        ";
        // line 78
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "breadcrumb", [], "any", false, false, true, 78), 78, $this->source), "html", null, true);
        echo "
        <main id=\"content\" class=\"column main-content\" role=\"main\">
          <section class=\"section\">
            <a id=\"main-content\" tabindex=\"-1\"></a>
            My Theme: ";
        // line 82
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getActiveThemePath(), "html", null, true);
        echo " 
            Custom::";
        // line 83
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "custom_title", [], "any", false, false, true, 83), 83, $this->source), "html", null, true);
        echo "
            ";
        // line 84
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "title", [], "any", false, false, true, 84), 84, $this->source), "html", null, true);
        echo "
            ";
        // line 85
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 85), 85, $this->source), "html", null, true);
        echo "
          </section>
        </main>
        ";
        // line 88
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 88)) {
            // line 89
            echo "          <div id=\"sidebar-first\" class=\"column sidebar\">
            <aside class=\"section\" role=\"complementary\">
              ";
            // line 91
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 91), 91, $this->source), "html", null, true);
            echo "
            </aside>
            <div class=\"custom_extension\">
              ";
            // line 94
            if (($context["logged_in"] ?? null)) {
                // line 95
                echo "                User Login
              ";
            } else {
                // line 97
                echo "                User not login
              ";
            }
            // line 99
            echo "              <hr>
              ";
            // line 100
            if ($this->extensions['Drupal\learning\TwigExtension']->isLogin()) {
                // line 101
                echo "                User Login
              ";
            } else {
                // line 103
                echo "                User not login
              ";
            }
            // line 105
            echo "              <hr>
              Display Block:: ";
            // line 106
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\learning\TwigExtension']->displayBlock("featurenews"));
            echo "
              <hr>
              Search Form:: ";
            // line 108
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\learning\TwigExtension']->getSearchForm(), "html", null, true);
            echo "
              <hr>
              ";
            // line 110
            $context["years"] = $this->extensions['Drupal\learning\TwigExtension']->getYears();
            // line 111
            echo "              <ul class=\"years\">
              ";
            // line 112
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["years"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["y"]) {
                // line 113
                echo "                <li>";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["y"], 113, $this->source), "html", null, true);
                echo "</li>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['y'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 115
            echo "              </ul>
              <br>
              replace_spaces::
              ";
            // line 118
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\learning\TwigExtension\FilterTwigExtension']->replaceSpaces("Hello India"), "html", null, true);
            echo "
              <a href=\"javascript:void(0)\" id=\"once_click\">Click Once</a>
              Twig link: <a href=\"";
            // line 120
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("learning.feedback"));
            echo "\">Feedback Form</a>
            </div>
          </div>
        ";
        }
        // line 124
        echo "        ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 124)) {
            // line 125
            echo "          <div id=\"sidebar-second\" class=\"column sidebar\">
            <aside class=\"section\" role=\"complementary\">
              ";
            // line 127
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 127), 127, $this->source), "html", null, true);
            echo "
            </aside>
          </div>
        ";
        }
        // line 131
        echo "      </div>
    </div>
    ";
        // line 133
        if (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_first", [], "any", false, false, true, 133) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_second", [], "any", false, false, true, 133)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_third", [], "any", false, false, true, 133))) {
            // line 134
            echo "      <div class=\"featured-bottom\">
        <aside class=\"layout-container clearfix\" role=\"complementary\">
          ";
            // line 136
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_first", [], "any", false, false, true, 136), 136, $this->source), "html", null, true);
            echo "
          ";
            // line 137
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_second", [], "any", false, false, true, 137), 137, $this->source), "html", null, true);
            echo "
          ";
            // line 138
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom_third", [], "any", false, false, true, 138), 138, $this->source), "html", null, true);
            echo "
        </aside>
      </div>
    ";
        }
        // line 142
        echo "    <footer class=\"site-footer\">
      <div class=\"layout-container\">
        ";
        // line 144
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 144)) {
            // line 145
            echo "          <div class=\"site-footer__top clearfix\">
            ";
            // line 146
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 146), 146, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 149
        echo "      </div>
    </footer>
  </div>
</div>
";
        
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453->leave($__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453_prof);

    }

    public function getTemplateName()
    {
        return "themes/tech/templates/page/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  255 => 149,  249 => 146,  246 => 145,  244 => 144,  240 => 142,  233 => 138,  229 => 137,  225 => 136,  221 => 134,  219 => 133,  215 => 131,  208 => 127,  204 => 125,  201 => 124,  194 => 120,  189 => 118,  184 => 115,  175 => 113,  171 => 112,  168 => 111,  166 => 110,  161 => 108,  156 => 106,  153 => 105,  149 => 103,  145 => 101,  143 => 100,  140 => 99,  136 => 97,  132 => 95,  130 => 94,  124 => 91,  120 => 89,  118 => 88,  112 => 85,  108 => 84,  104 => 83,  100 => 82,  93 => 78,  89 => 76,  82 => 72,  78 => 70,  75 => 69,  68 => 65,  64 => 63,  62 => 62,  56 => 59,  52 => 58,  48 => 57,  42 => 53,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Bartik's theme implementation to display a single page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.html.twig template normally located in the
 * core/modules/system directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - base_path: The base URL path of the Drupal installation. Will usually be
 *   \"/\" unless you have installed Drupal in a sub-directory.
 * - is_front: A flag indicating if the current page is the front page.
 * - logged_in: A flag indicating if the user is registered and signed in.
 * - is_admin: A flag indicating if the user has permission to access
 *   administration pages.
 *
 * Site identity:
 * - front_page: The URL of the front page. Use this instead of base_path when
 *   linking to the front page. This includes the language domain or prefix.
 *
 * Page content (in order of occurrence in the default page.html.twig):
 * - node: Fully loaded node, if there is an automatically-loaded node
 *   associated with the page and the node ID is the second argument in the
 *   page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - page.header: Items for the header region.
 * - page.highlighted: Items for the highlighted region.
 * - page.primary_menu: Items for the primary menu region.
 * - page.secondary_menu: Items for the secondary menu region.
 * - page.featured_top: Items for the featured top region.
 * - page.content: The main content of the current page.
 * - page.sidebar_first: Items for the first sidebar.
 * - page.sidebar_second: Items for the second sidebar.
 * - page.featured_bottom_first: Items for the first featured bottom region.
 * - page.featured_bottom_second: Items for the second featured bottom region.
 * - page.featured_bottom_third: Items for the third featured bottom region.
 * - page.footer_first: Items for the first footer column.
 * - page.footer_second: Items for the second footer column.
 * - page.footer_third: Items for the third footer column.
 * - page.footer_fourth: Items for the fourth footer column.
 * - page.footer_fifth: Items for the fifth footer column.
 * - page.breadcrumb: Items for the breadcrumb region.
 *
 * @see template_preprocess_page()
 * @see html.html.twig
 */
#}
<div id=\"page-wrapper\">
  <div id=\"page\">
    <header id=\"header\" class=\"header\" role=\"banner\">
      <div class=\"section layout-container clearfix\">
        {{ page.secondary_menu }}
        {{ page.header }}
        {{ page.primary_menu }}
      </div>
    </header>
    {% if page.highlighted %}
      <div class=\"highlighted\">
        <aside class=\"layout-container section clearfix\" role=\"complementary\">
          {{ page.highlighted }}
        </aside>
      </div>
    {% endif %}
    {% if page.featured_top %}
      <div class=\"featured-top\">
        <aside class=\"featured-top__inner section layout-container clearfix\" role=\"complementary\">
          {{ page.featured_top }}
        </aside>
      </div>
    {% endif %}
    <div id=\"main-wrapper\" class=\"layout-main-wrapper layout-container clearfix\">
      <div id=\"main\" class=\"layout-main clearfix\">
        {{ page.breadcrumb }}
        <main id=\"content\" class=\"column main-content\" role=\"main\">
          <section class=\"section\">
            <a id=\"main-content\" tabindex=\"-1\"></a>
            My Theme: {{ active_theme_path() }} 
            Custom::{{ page.custom_title }}
            {{ page.title }}
            {{ page.content }}
          </section>
        </main>
        {% if page.sidebar_first %}
          <div id=\"sidebar-first\" class=\"column sidebar\">
            <aside class=\"section\" role=\"complementary\">
              {{ page.sidebar_first }}
            </aside>
            <div class=\"custom_extension\">
              {% if logged_in %}
                User Login
              {% else %}
                User not login
              {% endif %}
              <hr>
              {% if is_login() %}
                User Login
              {% else %}
                User not login
              {% endif %}
              <hr>
              Display Block:: {{ display_block('featurenews') }}
              <hr>
              Search Form:: {{ PrintSearchForm() }}
              <hr>
              {% set years = getYears() %}
              <ul class=\"years\">
              {% for y in years %}
                <li>{{ y }}</li>
              {% endfor%}
              </ul>
              <br>
              replace_spaces::
              {{ \"Hello India\"|replace_spaces }}
              <a href=\"javascript:void(0)\" id=\"once_click\">Click Once</a>
              Twig link: <a href=\"{{ url('learning.feedback') }}\">Feedback Form</a>
            </div>
          </div>
        {% endif %}
        {% if page.sidebar_second %}
          <div id=\"sidebar-second\" class=\"column sidebar\">
            <aside class=\"section\" role=\"complementary\">
              {{ page.sidebar_second }}
            </aside>
          </div>
        {% endif %}
      </div>
    </div>
    {% if page.featured_bottom_first or page.featured_bottom_second or page.featured_bottom_third %}
      <div class=\"featured-bottom\">
        <aside class=\"layout-container clearfix\" role=\"complementary\">
          {{ page.featured_bottom_first }}
          {{ page.featured_bottom_second }}
          {{ page.featured_bottom_third }}
        </aside>
      </div>
    {% endif %}
    <footer class=\"site-footer\">
      <div class=\"layout-container\">
        {% if page.footer %}
          <div class=\"site-footer__top clearfix\">
            {{ page.footer }}
          </div>
        {% endif %}
      </div>
    </footer>
  </div>
</div>
", "themes/tech/templates/page/page.html.twig", "/opt/homebrew/var/www/drupal93/web/themes/tech/templates/page/page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 62, "set" => 110, "for" => 112);
        static $filters = array("escape" => 57, "replace_spaces" => 118);
        static $functions = array("active_theme_path" => 82, "is_login" => 100, "display_block" => 106, "PrintSearchForm" => 108, "getYears" => 110, "url" => 120);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'for'],
                ['escape', 'replace_spaces'],
                ['active_theme_path', 'is_login', 'display_block', 'PrintSearchForm', 'getYears', 'url']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
