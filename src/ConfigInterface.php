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
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Configure options for columns
     *
     * @param OptionsResolver $resolver
     */
    public function configureColumnOptions(OptionsResolver $resolver);

    /**
     * @return array
     */
    public function getColumns();

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
     * @param array $sorting
     *
     * @return self
     */
    public function setSortingSequence($sorting);

    /**
     * Get SortingManager instance
     *
     * @return SortingManagerInterface
     */
    public function getManager();

    /**
     * Register Config in SortingManager
     *
     * @param string $name
     *
     * @return self
     */
    public function register($name = null);
}