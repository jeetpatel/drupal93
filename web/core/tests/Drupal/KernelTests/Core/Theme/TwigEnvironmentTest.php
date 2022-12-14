<?php

namespace Drupal\KernelTests\Core\Theme;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Html;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Site\Settings;
use Drupal\Core\Template\TwigPhpStorageCache;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\DependencyInjection\Definition;
use Twig\Environment;
use Twig\Error\LoaderError;

/**
 * Tests the twig environment.
 *
 * @see \Drupal\Core\Template\TwigEnvironment
 * @group Twig
 */
class TwigEnvironmentTest extends KernelTestBase
{
  /**
   * Modules to enable.
   *
   * @var array
   */
    protected static $modules = ['system'];

    /**
     * Tests inline templates.
     */
    public function testInlineTemplate()
    {
        /** @var \Drupal\Core\Render\RendererInterface $renderer */
        $renderer = $this->container->get('renderer');
        /** @var \Drupal\Core\Template\TwigEnvironment $environment */
        $environment = \Drupal::service('twig');
        $this->assertEquals('test-no-context', $environment->renderInline('test-no-context'));
        $this->assertEquals('test-with-context muuh', $environment->renderInline('test-with-context {{ llama }}', ['llama' => 'muuh']));

        $element = [];
        $unsafe_string = '<script>alert(\'Danger! High voltage!\');</script>';
        $element['test'] = [
      '#type' => 'inline_template',
      '#template' => 'test-with-context <label>{{ unsafe_content }}</label>',
      '#context' => ['unsafe_content' => $unsafe_string],
    ];
        $this->assertEquals('test-with-context <label>' . Html::escape($unsafe_string) . '</label>', $renderer->renderRoot($element));

        // Enable twig_auto_reload and twig_debug.
        $settings = Settings::getAll();
        $settings['twig_debug'] = true;
        $settings['twig_auto_reload'] = true;

        new Settings($settings);
        $this->container = \Drupal::service('kernel')->rebuildContainer();
        \Drupal::setContainer($this->container);

        $element = [];
        $element['test'] = [
      '#type' => 'inline_template',
      '#template' => 'test-with-context {{ llama }}',
      '#context' => ['llama' => 'muuh'],
    ];
        $element_copy = $element;
        // Render it twice so that twig caching is triggered.
        $this->assertEquals('test-with-context muuh', $renderer->renderRoot($element));
        $this->assertEquals('test-with-context muuh', $renderer->renderRoot($element_copy));

        // Tests caching of inline templates with long content to ensure the
        // generated cache key can be used as a filename.
        $element = [];
        $element['test'] = [
      '#type' => 'inline_template',
      '#template' => 'Llamas sometimes spit and wrestle with their {{ llama }}. Kittens are soft and fuzzy and they sometimes say {{ kitten }}. Flamingos have long legs and they are usually {{ flamingo }}. Pandas eat bamboo and they are {{ panda }}. Giraffes have long necks and long tongues and they eat {{ giraffe }}.',
      '#context' => [
        'llama' => 'necks',
        'kitten' => 'meow',
        'flamingo' => 'pink',
        'panda' => 'bears',
        'giraffe' => 'leaves',
      ],
    ];
        $expected = 'Llamas sometimes spit and wrestle with their necks. Kittens are soft and fuzzy and they sometimes say meow. Flamingos have long legs and they are usually pink. Pandas eat bamboo and they are bears. Giraffes have long necks and long tongues and they eat leaves.';
        $element_copy = $element;

        // Render it twice so that twig caching is triggered.
        $this->assertEquals($expected, $renderer->renderRoot($element));
        $this->assertEquals($expected, $renderer->renderRoot($element_copy));

        $name = '{# inline_template_start #}' . $element['test']['#template'];
        $prefix = $environment->getTwigCachePrefix();

        $cache = $environment->getCache();
        $class = $environment->getTemplateClass($name);
        $expected = $prefix . '_inline-template_' . substr(Crypt::hashBase64($class), 0, TwigPhpStorageCache::SUFFIX_SUBSTRING_LENGTH);
        $this->assertEquals($expected, $cache->generateKey($name, $class));
    }

    /**
     * Tests that exceptions are thrown when a template is not found.
     */
    public function testTemplateNotFoundException()
    {
        /** @var \Drupal\Core\Template\TwigEnvironment $environment */
        $environment = \Drupal::service('twig');

        try {
            $environment->loadTemplate('this-template-does-not-exist.html.twig')->render([]);
            $this->fail('Did not throw an exception as expected.');
        } catch (LoaderError $e) {
            $this->assertStringStartsWith('Template "this-template-does-not-exist.html.twig" is not defined', $e->getMessage());
        }
    }

    /**
     * Ensures that templates resolve to the same class name and cache file.
     */
    public function testTemplateClassname()
    {
        /** @var \Drupal\Core\Template\TwigEnvironment $environment */
        $environment = \Drupal::service('twig');

        // Test using an include template path.
        $name_include = 'container.html.twig';
        $class_include = $environment->getTemplateClass($name_include);
        $key_include = $environment->getCache()->generateKey($name_include, $class_include);

        // Test using a namespaced template path.
        $name_namespaced = '@system/container.html.twig';
        $class_namespaced = $environment->getTemplateClass($name_namespaced);
        $key_namespaced = $environment->getCache()->generateKey($name_namespaced, $class_namespaced);

        // Test using a direct filesystem template path.
        $name_direct = 'core/modules/system/templates/container.html.twig';
        $class_direct = $environment->getTemplateClass($name_direct);
        $key_direct = $environment->getCache()->generateKey($name_direct, $class_direct);

        // All three should be equal for both cases.
        $this->assertEquals($class_include, $class_namespaced);
        $this->assertEquals($class_namespaced, $class_direct);
        $this->assertEquals($key_include, $key_namespaced);
        $this->assertEquals($key_namespaced, $key_direct);
    }

    /**
     * Ensures that cacheFilename() varies by extensions + deployment identifier.
     */
    public function testCacheFilename()
    {
        /** @var \Drupal\Core\Template\TwigEnvironment $environment */
        // Note: Later we refetch the twig service in order to bypass its internal
        // static cache.
        $environment = \Drupal::service('twig');
        $template_path = 'core/modules/system/templates/container.html.twig';

        // A template basename greater than the constant
        // TwigPhpStorageCache::SUFFIX_SUBSTRING_LENGTH should get truncated.
        $cache = $environment->getCache();
        $long_name = 'core/modules/system/templates/block--system-messages-block.html.twig';
        $this->assertGreaterThan(TwigPhpStorageCache::SUFFIX_SUBSTRING_LENGTH, strlen(basename($long_name)));
        $class = $environment->getTemplateClass($long_name);
        $key = $cache->generateKey($long_name, $class);
        $prefix = $environment->getTwigCachePrefix();
        // The key should consist of the prefix, an underscore, and two strings
        // each truncated to length TwigPhpStorageCache::SUFFIX_SUBSTRING_LENGTH
        // separated by an underscore.
        $expected = strlen($prefix) + 2 + 2 * TwigPhpStorageCache::SUFFIX_SUBSTRING_LENGTH;
        $this->assertEquals($expected, strlen($key));

        $cache = $environment->getCache();
        $class = $environment->getTemplateClass($template_path);
        $original_filename = $cache->generateKey($template_path, $class);
        \Drupal::service('module_installer')->install(['twig_extension_test']);

        $environment = \Drupal::service('twig');
        $cache = $environment->getCache();
        $class = $environment->getTemplateClass($template_path);
        $new_extension_filename = $cache->generateKey($template_path, $class);
        \Drupal::getContainer()->set('twig', null);

        $this->assertNotEquals($original_filename, $new_extension_filename);
    }

    /**
     * {@inheritdoc}
     */
    public function register(ContainerBuilder $container)
    {
        parent::register($container);

        $definition = new Definition('Twig\Loader\FilesystemLoader', [[sys_get_temp_dir()]]);
        $definition->setPublic(true);
        $container->setDefinition('twig_loader__file_system', $definition)
      ->addTag('twig.loader');
    }

    /**
     * Tests template invalidation.
     */
    public function testTemplateInvalidation()
    {
        $template_before = <<<TWIG
<div>Hello before</div>
TWIG;
        $template_after = <<<TWIG
<div>Hello after</div>
TWIG;

        $tempfile = tempnam(sys_get_temp_dir(), '__METHOD__') . '.html.twig';
        file_put_contents($tempfile, $template_before);

        /** @var \Drupal\Core\Template\TwigEnvironment $environment */
        $environment = \Drupal::service('twig');

        $output = $environment->load(basename($tempfile))->render();
        $this->assertEquals($template_before, $output);

        file_put_contents($tempfile, $template_after);
        $output = $environment->load(basename($tempfile))->render();
        $this->assertEquals($template_before, $output);

        $environment->invalidate();
        // Manually change $templateClassPrefix to force a different template
        // classname, as the other class is still loaded. This wouldn't be a problem
        // on a real site where you reload the page.
        $reflection = new \ReflectionClass(Environment::class);
        $property_reflection = $reflection->getProperty('templateClassPrefix');
        $property_reflection->setAccessible(true);
        $property_reflection->setValue($environment, 'otherPrefix');

        $output = $environment->load(basename($tempfile))->render();
        $this->assertEquals($template_after, $output);
    }
}
