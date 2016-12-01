<?php
namespace nvbooster\SortingManager;

use Symfony\Component\HttpFoundation\Request;
use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SortingManager implements SortingManagerInterface
{
    /**
     * @var array
     */
    protected $storages;

    /**
     * @var array
     */
    protected $configs;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->storages = array();
        $this->configs = array();
        $this->options = array();

        if ($options && is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::registerStorage()
     */
    public function registerStorage(ConfigStorageInterface $storage, $alias = null)
    {
        if (!$alias) {
            $alias = $storage->getAlias();
        }

        if (!$alias) {
            throw new \LogicException('Storage must have an alias');
        }

        $alias = (string) $alias;

        if (key_exists($alias, $this->storages)) {
            throw new \LogicException('Storage alias must be unique per SortingManager instance');
        }

        $this->storages[$alias] = $storage;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'storage' => 'session',
            'sort_columns_count' => 2,
            'param_column' => 'sc',
            'param_order' => 'so',
            'column_ascend_class' => 'sm_asc',
            'column_descend_class' => 'sm_desc',
            'column_sortable_class' => 'sm_column',
            'translation_domain' => 'sortingmanager'
        ));

        $resolver->setAllowedValues(array(
            'storage' => array_keys($this->storages),
        ));

        $resolver->setAllowedTypes(array(
            'sort_columns_count' => 'integer',
        ));
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::registerConfig()
     */
    public function registerConfig(ConfigInterface $config, $name = null)
    {
        if ($config->getManager() != $this) {
            throw new \LogicException('Config manager doesn\'t match');
        }

        if (!$name) {
            $name = $config->getName();
        }

        if (!$name) {
            throw new \LogicException('Config must have a name');
        }

        $name = (string) $name;

        if (key_exists($name, $this->configs)) {
            throw new \LogicException('Config name must be unique per SortingManager instance');
        }

        $this->configs[$name] = $config;

        $options = $config->getOptions();

        $storage = $this->getStorage($options['storage']);

        if ($storage->has($config)) {
            $storage->retrieve($config);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::getStorage()
     */
    public function getStorage($alias)
    {
        $alias = (string) $alias;
        $storage = false;

        if ($alias && key_exists($alias, $this->storages)) {
            $storage = $this->storages[$alias];
        }

        return $storage;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::setOptions()
     */
    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::getOptions()
     */
    public function getOptions()
    {
        $resolver = $this->getOptionsResolver();

        return $resolver->resolve($this->options);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::createConfig()
     */
    public function createConfig($name, array $options = [])
    {
        $config = new GenericConfig($this, $options);
        $config->setName($name);

        return $config;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::handleRequest()
     */
    public function handleRequest(ConfigInterface $config, Request $request)
    {
        $options = $config->getOptions();


        if ($column = $request->get($options['param_column'], false)) {
            $sequence = $config->getSortingSequence();
            $order = $request->get($options['param_order'], 0);

            if (key_exists($column, $sequence)) {
                unset($sequence[$column]);
            }

            if ($order) {
                //normalize
                $order = ($order > 0) ? 1 : -1;
                $sequence = array_merge(array($column => $order), $sequence);
            }

            $sequence = array_slice($sequence, 0, $options['sort_columns_count']);
            $config->setSortingSequence($sequence);

            $storage = $this->getStorage($options['storage']);
            $storage->store($config);
        }
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::getConfig()
     */
    public function getConfig($name)
    {
        $name = (string) $name;
        $storage = false;

        if ($name && key_exists($name, $this->configs)) {
            $storage = $this->configs[$name];
        }

        return $storage;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\SortingManagerInterface::createControl()
     */
    public function createControl(ConfigInterface $config)
    {
        // TODO Auto-generated method stub
    }

    /**
     * @return OptionsResolver
     */
    protected function getOptionsResolver()
    {
        if (!$this->resolver) {
            $this->resolver = new OptionsResolver();
            $this->configureOptions($this->resolver);
        }

        return $this->resolver;
    }
}