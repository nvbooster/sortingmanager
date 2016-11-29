<?php

namespace nvbooster\SortingManager;

use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author nvb <nvb@aproxima.ru>
 */
interface SortingManagerInterface
{
    /**
     * Set default options for new Configs
     *
     * @param array $options
     */
    public function setOptions($options = array());

    /**
     * Create new generic Config
     *
     * @param string $name
     * @param array  $options
     *
     * @return ConfigInterface
     */
    public function createConfig($name, $options = array());

    /**
     * Register Config data storage
     *
     * @param ConfigStorageInterface $storage
     * @param string                 $alias
     */
    public function registerStorage(ConfigStorageInterface $storage, $alias = null);

    /**
     * Register manually created Config
     *
     * @param ConfigInterface $config
     * @param string          $alias
     */
    public function registerConfig(ConfigInterface $config, $alias = null);

    /**
     * Get data storage
     *
     * @param string $alias
     *
     * @return ConfigStorageInterface | false
     */
    public function getStorage($alias);

    /**
     * Get registered Config
     *
     * @param string $name
     *
     * @return ConfigStorageInterface | false
     */
    public function getConfig($name);

    /**
     * Create new view Control for Config
     *
     * @param ConfigInterface $config
     *
     * @return ControlInterface
     */
    public function createControl(ConfigInterface $config);

    /**
     * Configure default options
     *
     * @param OptionsResolver $resolver
     *
     * @return OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Handle request to update Config's data
     *
     * @param ConfigInterface $config
     * @param Request         $request
     */
    public function handleRequest(ConfigInterface $config, Request $request);
}