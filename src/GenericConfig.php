<?php
namespace nvbooster\SortingManager;

use nvbooster\SortingManager\ConfigInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class GenericConfig extends AbstractConfig
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @param SortingManager $manager
     * @param array          $options
     */
    public function __construct(SortingManager $manager, $options = array())
    {
        $this->manager = $manager;
        $this->options = $options;
        $this->columns = array();
        $this->defaults = array();
    }

    /**
     * Set config name. For internal use, do not call this after creation
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * Add column description to Config
     *
     * @param string $name
     * @param string $field
     * @param array  $options
     *
     * @return self
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
     * @see \nvbooster\SortingManager\ConfigInterface::getColumns()
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set default sorting sequense
     *
     * @param array $sorting
     *
     * @return self
     */
    public function setDefaultSorting($sorting = array())
    {
        foreach (array_keys($sorting) as $column) {
            if (!key_exists($column, $this->getColumns())) {
                unset($sorting[$column]);
            }
        }

        $this->defaults = $sorting;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\AbstractConfig::getSortingDefaults()
     */
    protected function getSortingDefaults()
    {
        return $this->defaults;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
}