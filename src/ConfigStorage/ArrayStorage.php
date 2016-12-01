<?php
namespace nvbooster\SortingManager\ConfigStorage;

use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;
use nvbooster\SortingManager\ConfigInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class ArrayStorage implements ConfigStorageInterface
{
    /**
     * @var array
     */
    protected $storage;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->storage = array();
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::retrieve()
     */
    public function retrieve(ConfigInterface $config)
    {
        if (isset($this->storage[$this->getKey($config)])) {
            $config->setSortingSequence($this->storage[$this->getKey($config)]);
        }
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::store()
     */
    public function store(ConfigInterface $config)
    {
        $this->storage[$this->getKey($config)] = $config->getSortingSequence();
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::has()
     */
    public function has(ConfigInterface $config)
    {
        return isset($this->storage[$this->getKey($config)]);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::getAlias()
     */
    public function getAlias()
    {
        return 'array';
    }

    /**
     * @param ConfigInterface $config
     *
     * @return string
     */
    protected function getKey(ConfigInterface $config)
    {
        return md5(get_class($config) . $config->getName());
    }
}