<?php

namespace Drupal\Core\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\Context\ContextInterface as ComponentContextInterface;
use Drupal\Component\Plugin\Definition\ContextAwarePluginDefinitionInterface;
use Drupal\Component\Plugin\Exception\ContextException;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Plugin\Context\Context;
use Drupal\Core\Plugin\Context\ContextInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Provides a trait to add context-aware functionality to plugins.
 *
 * @see \Drupal\Core\Plugin\ContextAwarePluginInterface
 *
 * @ingroup plugin_api
 */
trait ContextAwarePluginTrait
{
  /**
   * The data objects representing the context of this plugin.
   *
   * @var \Drupal\Core\Plugin\Context\ContextInterface[]
   */
    protected $context = [];

    /**
     * Tracks whether the context has been initialized from configuration.
     *
     * @var bool
     *
     * @todo Remove this in Drupal 10.0.x.
     *   See https://www.drupal.org/project/drupal/issues/3153956.
     *
     * @internal
     */
    protected $initializedContextConfig = false;

    /**
     * {@inheritdoc}
     */
    public function getContexts()
    {
        // Make sure all context objects are initialized.
        foreach ($this->getContextDefinitions() as $name => $definition) {
            $this->getContext($name);
        }
        return $this->context;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Drupal\Core\Plugin\Context\ContextInterface
     *   The context object.
     */
    public function getContext($name)
    {
        // @todo Remove this entire block in Drupal 10.0.x.
        //   See https://www.drupal.org/project/drupal/issues/3153956.
        if (!$this->initializedContextConfig) {
            $this->initializedContextConfig = true;
            if ($this instanceof ConfigurableInterface) {
                $configuration = $this->getConfiguration();
            } else {
                $reflection = new \ReflectionProperty($this, 'configuration');
                $reflection->setAccessible(true);
                $configuration = $reflection->getValue($this);
            }

            if (isset($configuration['context'])) {
                @trigger_error('Passing context values to plugins via configuration is deprecated in drupal:9.1.0 and will be removed before drupal:10.0.0. Instead, call ::setContextValue() on the plugin itself. See https://www.drupal.org/node/3120980', E_USER_DEPRECATED);
                foreach ($configuration['context'] as $key => $value) {
                    $context_definition = $this->getContextDefinition($key);
                    $this->context[$key] = new Context($context_definition, $value);
                }
            }
        }

        // Check for a valid context value.
        if (!isset($this->context[$name])) {
            $this->context[$name] = new Context($this->getContextDefinition($name));
        }
        return $this->context[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function setContext($name, ComponentContextInterface $context)
    {
        // Check that the context passed is an instance of our extended interface.
        if (!$context instanceof ContextInterface) {
            throw new ContextException("Passed $name context must be an instance of \\Drupal\\Core\\Plugin\\Context\\ContextInterface");
        }
        $this->context[$name] = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextValues()
    {
        $values = [];
        foreach ($this->getContextDefinitions() as $name => $definition) {
            $values[$name] = isset($this->context[$name]) ? $this->context[$name]->getContextValue() : null;
        }
        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextValue($name)
    {
        return $this->getContext($name)->getContextValue();
    }

    /**
     * {@inheritdoc}
     */
    public function setContextValue($name, $value)
    {
        $this->setContext($name, Context::createFromContext($this->getContext($name), $value));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextMapping()
    {
        $configuration = $this instanceof ConfigurableInterface ? $this->getConfiguration() : $this->configuration;
        return isset($configuration['context_mapping']) ? $configuration['context_mapping'] : [];
    }

    /**
     * {@inheritdoc}
     */
    public function setContextMapping(array $context_mapping)
    {
        if ($this instanceof ConfigurableInterface) {
            $configuration = $this->getConfiguration();
            $configuration['context_mapping'] = array_filter($context_mapping);
            $this->setConfiguration($configuration);
        } else {
            $this->configuration['context_mapping'] = $context_mapping;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    abstract protected function getPluginDefinition();

    /**
     * {@inheritdoc}
     *
     * @return \Drupal\Core\Plugin\Context\ContextDefinitionInterface[]
     */
    public function getContextDefinitions()
    {
        $definition = $this->getPluginDefinition();
        if ($definition instanceof ContextAwarePluginDefinitionInterface) {
            return $definition->getContextDefinitions();
        }

        return !empty($definition['context_definitions']) ? $definition['context_definitions'] : [];
    }

    /**
     * {@inheritdoc}
     *
     * @return \Drupal\Core\Plugin\Context\ContextDefinitionInterface
     */
    public function getContextDefinition($name)
    {
        $definition = $this->getPluginDefinition();
        if ($definition instanceof ContextAwarePluginDefinitionInterface) {
            if ($definition->hasContextDefinition($name)) {
                return $definition->getContextDefinition($name);
            }
        } elseif (!empty($definition['context_definitions'][$name])) {
            return $definition['context_definitions'][$name];
        }
        throw new ContextException(sprintf("The %s context is not a valid context.", $name));
    }

    /**
     * {@inheritdoc}
     */
    public function validateContexts()
    {
        $violations = new ConstraintViolationList();

        // @todo Implement the Symfony Validator component to let the validator
        //   traverse and set property paths accordingly.
        //   See https://www.drupal.org/project/drupal/issues/3153847.
        foreach ($this->getContexts() as $context) {
            $violations->addAll($context->validate());
        }
        return $violations;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheContexts()
    {
        $cache_contexts = [];
        // Applied contexts can affect the cache contexts when this plugin is
        // involved in caching, collect and return them.
        foreach ($this->getContexts() as $context) {
            /** @var \Drupal\Core\Cache\CacheableDependencyInterface $context */
            if ($context instanceof CacheableDependencyInterface) {
                $cache_contexts = Cache::mergeContexts($cache_contexts, $context->getCacheContexts());
            }
        }
        return $cache_contexts;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheTags()
    {
        $tags = [];
        // Applied contexts can affect the cache tags when this plugin is
        // involved in caching, collect and return them.
        foreach ($this->getContexts() as $context) {
            /** @var \Drupal\Core\Cache\CacheableDependencyInterface $context */
            if ($context instanceof CacheableDependencyInterface) {
                $tags = Cache::mergeTags($tags, $context->getCacheTags());
            }
        }
        return $tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheMaxAge()
    {
        $max_age = Cache::PERMANENT;

        // Applied contexts can affect the cache max age when this plugin is
        // involved in caching, collect and return them.
        foreach ($this->getContexts() as $context) {
            /** @var \Drupal\Core\Cache\CacheableDependencyInterface $context */
            if ($context instanceof CacheableDependencyInterface) {
                $max_age = Cache::mergeMaxAges($max_age, $context->getCacheMaxAge());
            }
        }
        return $max_age;
    }
}
