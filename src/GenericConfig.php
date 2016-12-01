<?php

namespace nvbooster\SortingManager;
use nvbooster\SortingManager\ConfigInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class GenericConfig implements ConfigInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var SortingManager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var sorting
     */
    protected $sorting = false;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @param SortingManager $manager
     * @param array          $options
     */
    public function __construct(SortingManager $manager, $options = array())
    {
        $this->manager = $manager;
        $this->options = array();
        $this->defaults = array();
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setName()
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setDefaultSorting()
     */
    public function setDefaultSorting($sorting = array())
    {
        $this->defaults = $sorting;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::addColumn()
     */
    public function addColumn($name, $field, $options = array())
    {
        if (!$name) {
            throw new \LogicException('Column must have a name');
        }

        $name = (string) $name;

        if (key_exists($name, $this->columns)) {
            throw new \LogicException('Column name must be unique per Config instance');
        }

        $columnConfig = array(
            'name' => $name,
            'field' => $field,
            'options' => $options
        );

        $this->columns[$name] = $columnConfig;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::getManager()
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setOptions()
     */
    public function setOptions($options = array())
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setSortingSequence()
     */
    public function setSortingSequence($sorting = array())
    {
        foreach (array_keys($sorting) as $column) {
            if (key_exists($column, $this->columns)) {
                unset($sorting[$column]);
            }
        }

        $this->sorting = $sorting;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::getOptions()
     */
    public function getOptions()
    {
        $resolver = $this->getOptionsResolver();
        $options = array_merge($this->manager->getOptions(), $this->options);

        return $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::handleRequest()
     */
    public function handleRequest(Request $request)
    {
        $this->manager->handleRequest($this, $request);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::getSortingSequence()
     */
    public function getSortingSequence()
    {
        return ($this->sorting !== false) ? $this->sorting : $this->defaults;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::createControl()
     */
    public function createControl()
    {
        return $this->manager->createControl($this);
    }

    /**
     * @return OptionsResolver
     */
    protected function getOptionsResolver()
    {
        if (!$this->resolver) {
            $this->resolver = new OptionsResolver();
            $this->configureOptions($this->resolver);
            $this->manager->configureOptions($this->resolver);
        }

        return $this->resolver;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::register()
     */
    public function register($name = null)
    {
        $this->manager->registerConfig($this, $name);
    }
}