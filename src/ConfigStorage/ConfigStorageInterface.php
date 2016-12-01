<?php

namespace nvbooster\SortingManager\ConfigStorage;

use nvbooster\SortingManager\ConfigInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
interface ConfigStorageInterface
{
    /**
     * Stores sorting sequence for Config
     *
     * @param ConfigInterface $config
     */
    public function store(ConfigInterface $config);

    /**
     * Retrieve sorting sequence for Config
     *
     * @param ConfigInterface $config
     *
     * @return array
     */
    public function retrieve(ConfigInterface $config);

    /**
     * Checks if there is Config data stored
     * @param ConfigInterface $config
     *
     * @return boolean
     */
    public function has(ConfigInterface $config);

    /**
     * Get default storage alias
     *
     * @return string
     */
    public function getAlias();
}