<?php

namespace nvbooster\SortingManager;

/**
 * @author nvb <nvb@aproxima.ru>
 */
interface ControlInterface
{
    /**
     * Get sorting order for the column. 1 stands for ASC, -1 for DESC respectively
     *
     * @param string $name
     *
     * @return int
     */
    public function getColumnSortOrder($name);

    /**
     * Get current stored column sorting sequence
     *
     * @return array
     */
    public function getSortingSequence();

    /**
     * If column is currently used in sorting retrurns its 1-based position in sequence, 0 otherwise
     *
     * @param string $name
     *
     * @return int
     */
    public function isColumnSortable($name);

    /**
     * If column with given name described in Config
     *
     * @param string $name
     *
     * @return boolean
     */
    public function isColumnSorted($name);

    /**
     * Returns field defined for column
     *
     * @param string $name
     *
     * @return string
     */
    public function getColumnField($name);

    /**
     * Returns view options for column
     *
     * @param string $name
     *
     * @return array
     */
    public function getColumnOptions($name);

    /**
     * Get default options
     *
     * @return array
     */
    protected function getOptions();

    /**
     * Get related Config instance
     *
     * @return ConfigInterface
     */
    protected function getConfig();

    /**
     * Get column descriptions
     *
     * @return array
     */
    protected function getColumns();
}