<?php

namespace nvbooster\SortingManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author nvb <nvb@aproxima.ru>
 */
interface ConfigInterface
{
    /**
     * Get Config unique name
     *
     * @return string
     */
    public function getName();

    /**
     * Set config name. For internal use, do not call this after creation
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get Config options
     *
     * @return array
     */
    public function getOptions();

    /**
     * Set Config options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions($options = array());

    /**
     * Configure default options for view Control
     *
     * @param OptionsResolver $resolver
     *
     * @return OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Add column description to Config
     *
     * @param string $name
     * @param string $field
     * @param array  $options
     *
     * @return self
     */
    public function addColumn($name, $field, $options = array());

    /**
     * Set default sorting sequense
     *
     * @param array $sorting
     *
     * @return self
     */
    public function setDefaultSorting($sorting = array());


    /**
     * Handle request to update this Config's data. Shortcut to SortingManagerInterface::handleRequest
     *
     * @param Request $request
     *
     * @return self
     */
    public function handleRequest(Request $request);

    /**
     * Create new view Control for this Config. Shortcut to SortingManagerInterface::createControl
     *
     * @return ControlInterface
     */
    public function createControl();

    /**
     * Get current stored column sorting sequence
     *
     * @return array
     */
    public function getSortingSequence();

    /**
     * Set column sorting sequence
     *
     * @return array
     */
    public function setSortingSequence();

    /**
     * Get SortingManager instance
     *
     * @return SortingManagerInterface
     */
    protected function getManager();
}