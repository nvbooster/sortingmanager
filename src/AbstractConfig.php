<?php
namespace nvbooster\SortingManager;

use nvbooster\SortingManager\ConfigInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author nvb <nvb@aproxima.ru>
 */
abstract class AbstractConfig implements ConfigInterface
{
    /**
     * @var SortingManager
     */
    protected $manager;

    /**
     * @var sorting
     */
    protected $sorting = false;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $resolvedOptions = false;

    /**
     * @var OptionsResolver
     */
    protected $resolver;

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
     * @see \nvbooster\SortingManager\ConfigInterface::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::configureColumnOptions()
     */
    public function configureColumnOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('label');
        $resolver->setDefined('column_ascend_class');
        $resolver->setDefined('column_descend_class');
        $resolver->setDefined('column_sortable_class');
        $resolver->setDefined('translation_domain');
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setOptions()
     */
    public function setOptions($options = array())
    {
        $this->options = array_merge($this->options, $options);
        $this->resolvedOptions = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::setSortingSequence()
     */
    public function setSortingSequence($sorting = array())
    {
        foreach (array_keys($sorting) as $column) {
            if (!key_exists($column, $this->getColumns())) {
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
        if ($this->resolvedOptions === false) {
            $resolver = $this->getOptionsResolver();
            $options = array_merge($this->manager->getOptions(), $this->options);
            $this->resolvedOptions = $resolver->resolve($options);
        }

        return $this->resolvedOptions;
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
        return ($this->sorting !== false) ? $this->sorting : $this->getSortingDefaults();
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

    /**
     * @return array
     */
    protected function getSortingDefaults()
    {
        return array();
    }
}