<?php

namespace nvbooster\SortingManager;
use nvbooster\SortingManager\ControlInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class Control implements ControlInterface
{

    /**
     * @var array
     */
    protected $sequence;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $columns
     * @param array $sequence
     * @param array $options
     */
    public function __construct($columns, $sequence, $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(array(
            'param_column',
            'param_order'
        ));

        $this->columns = $columns;
        $this->sequence = $sequence;
        $this->options = $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::getColumnSortOrder()
     */
    public function getColumnSortOrder($name)
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name]['sort_order'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::isColumnSorted()
     */
    public function isColumnSorted($name)
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name]['sort_pos'] ?: false;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::getColumnOptions()
     */
    public function getColumnOptions($name)
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name]['options'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::isColumnSortable()
     */
    public function isColumnSortable($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::getSortingSequence()
     */
    public function getSortingSequence()
    {
        return $this->sequence;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::getSortByParam()
     */
    public function getSortByParam()
    {
        return $this->options['param_column'];
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ControlInterface::getSortOrderParam()
     */
    public function getSortOrderParam()
    {
        return $this->options['param_order'];
    }
}