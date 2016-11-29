<?php

namespace nvbooster\SortingManager\QueryHandler;

/**
 * @author nvb <nvb@aproxima.ru>
 */
interface QueryHandlerInterface
{
    /**
     * Get related Config instance
     *
     * @return ConfigInterface
     */
    protected function getConfig();
}