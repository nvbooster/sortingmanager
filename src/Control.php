<?php

namespace nvbooster\SortingManager;
use nvbooster\SortingManager\ControlInterface;

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
     * @param array $columns
     * @param array $sequence
     */
    public function __construct($columns, $sequence)
    {
        $this->columns = $columns;
        $this->sequence = $sequence;
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
}