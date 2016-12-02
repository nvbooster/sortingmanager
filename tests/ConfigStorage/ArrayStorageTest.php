<?php
namespace nvbooster\SortingManager\Tests\ConfigStorage;

use nvbooster\SortingManager\ConfigStorage\ArrayStorage;
use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\GenericConfig;

class ArrayStorageTest extends TestCase
{
    public function testIfConstructed()
    {
        $storage = new ArrayStorage();

        self::assertInstanceOf('nvbooster\SortingManager\ConfigStorage\ArrayStorage', $storage);
        self::assertEquals('array', $storage->getAlias());
    }

    public function testStorage()
    {
        $storage = new ArrayStorage();

        $manager = new SortingManager();
        $config1 = new GenericConfig($manager);
        $config2 = new GenericConfig($manager);
        $config3 = new GenericConfig($manager);

        $config1
            ->setName('dummy_1')
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->setDefaultSorting(array('name1' => 1));
        $config2
            ->setName('dummy_1')
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->setDefaultSorting(array('name1' => 1));

        $config3->setName('dummy_2');

        self::assertFalse($storage->has($config1));

        $config1->setSortingSequence(array('name2' => -1));
        $storage->store($config1);

        self::assertTrue($storage->has($config2));
        self::assertFalse($storage->has($config3));

        $storage->retrieve($config2);
        self::assertEquals(array('name2' => -1), $config2->getSortingSequence());
    }
}