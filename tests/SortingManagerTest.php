<?php
namespace nvbooster\SortingManager\Tests;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\ConfigStorage\ArrayStorage;
use nvbooster\SortingManager\GenericConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SortingManagerTest extends TestCase
{
    public function testIfConstructed()
    {
        $manager = new SortingManager();

        self::assertInstanceOf(SortingManager::class, $manager);

        $options = $manager->getOptions();
        self::assertArrayHasKey('storage', $options);
        self::assertArrayHasKey('sort_columns_count', $options);
        self::assertArrayHasKey('param_column', $options);
        self::assertArrayHasKey('param_order', $options);
        self::assertArrayHasKey('column_ascend_class', $options);
        self::assertArrayHasKey('column_descend_class', $options);
        self::assertArrayHasKey('column_sortable_class', $options);
        self::assertArrayHasKey('translation_domain', $options);

        self::assertEquals('array', $options['storage']);
        self::assertEquals(1, $options['sort_columns_count']);
        self::assertEquals('sc', $options['param_column']);
        self::assertEquals('so', $options['param_order']);
        self::assertEquals('sm_asc', $options['column_ascend_class']);
        self::assertEquals('sm_desc', $options['column_descend_class']);
        self::assertEquals('sm_column', $options['column_sortable_class']);
        self::assertEquals('sortingmanager', $options['translation_domain']);
    }

    public function testInvalidStorage()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);

        $options = array(
            'storage' => 'random'
        );

        $manager = new SortingManager($options);
        $options = $manager->getOptions();
    }

    public function testInvalidSortColumnsCountType()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);

        $options = array(
            'sort_columns_count' => '3'
        );

        $manager = new SortingManager($options);
        $manager->getOptions();
    }

    public function testInvalidSortColumnsCountValue()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);

        $options = array(
            'sort_columns_count' => 0
        );

        $manager = new SortingManager($options);
        $manager->getOptions();
    }

    public function testOptions()
    {
        $options = array(
            'storage' => 'array',
            'sort_columns_count' => 2,
            'param_column' => 'testval1',
            'param_order' => 'testval2',
            'column_ascend_class' => 'testval3',
            'column_descend_class' => 'testval4',
            'column_sortable_class' => 'testval5',
            'translation_domain' => 'testval6'
        );

        $manager = new SortingManager($options);
        $resolvedOptions = $manager->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testEmptyOptions()
    {
        $manager = new SortingManager(array());
        $options = $manager->getOptions();

        self::assertEquals('array', $options['storage']);
        self::assertEquals(1, $options['sort_columns_count']);
        self::assertEquals('sc', $options['param_column']);
        self::assertEquals('so', $options['param_order']);
        self::assertEquals('sm_asc', $options['column_ascend_class']);
        self::assertEquals('sm_desc', $options['column_descend_class']);
        self::assertEquals('sm_column', $options['column_sortable_class']);
        self::assertEquals('sortingmanager', $options['translation_domain']);
    }

    public function testExtraOptions()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException::class);

        $manager = new SortingManager(array('fakeparam' => 5));
        $manager->getOptions();
    }

    public function testSetOptions()
    {
        $options = array(
            'storage' => 'array',
            'sort_columns_count' => 2,
            'param_column' => 'testval1',
            'param_order' => 'testval2',
            'column_ascend_class' => 'testval3',
            'column_descend_class' => 'testval4',
            'column_sortable_class' => 'testval5',
            'translation_domain' => 'testval6'
        );

        $manager = new SortingManager();
        $manager->setOptions($options);
        $resolvedOptions = $manager->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testRegisterStorageUniqueException()
    {
        $this->expectException(\LogicException::class);

        $storage = new ArrayStorage();
        $manager = new SortingManager();

        $manager->registerStorage($storage);
    }

    public function testRegisterStorageAlias()
    {
        $storage = new ArrayStorage();
        $manager = new SortingManager();

        $manager->registerStorage($storage, 'array_2');

        self::assertSame($storage, $manager->getStorage('array_2'));

        self::assertNotSame($storage, $manager->getStorage('array'));
        self::assertInstanceOf(ArrayStorage::class, $manager->getStorage('array'));
    }

    public function testRegisterStorageNoAlias()
    {
        $this->expectException(\LogicException::class);
        $storage = $this->getMockBuilder(ArrayStorage::class)->getMock();
        $storage->method('getAlias')->willReturn(null);
        $manager = new SortingManager();

        $manager->registerStorage($storage);
    }

    public function testRegisterStorageAutoalias()
    {
        $manager = new SortingManager();
        $storage = $this->getMockBuilder(ArrayStorage::class)->getMock();
        $storage->method('getAlias')->willReturn('arraystub');

        $manager->registerStorage($storage);

        self::assertSame($storage, $manager->getStorage('arraystub'));
    }

    public function testNotRegisteredStorage()
    {
        $manager = new SortingManager();
        self::assertSame(false, $manager->getStorage('random'));
    }

    public function testCreateConfig()
    {
        $manager = new SortingManager();

        $config = $manager->createConfig('dummy');
        self::assertInstanceOf(GenericConfig::class, $config);
        self::assertEquals('dummy', $config->getName());

        $options = $config->getOptions();

        self::assertEquals('array', $options['storage']);
        self::assertEquals(1, $options['sort_columns_count']);
        self::assertEquals('sc', $options['param_column']);
        self::assertEquals('so', $options['param_order']);
        self::assertEquals('sm_asc', $options['column_ascend_class']);
        self::assertEquals('sm_desc', $options['column_descend_class']);
        self::assertEquals('sm_column', $options['column_sortable_class']);
        self::assertEquals('sortingmanager', $options['translation_domain']);
    }

    public function testCreateConfigOptions()
    {
        $manager = new SortingManager();

        $options = array(
            'storage' => 'array',
            'sort_columns_count' => 2,
            'param_column' => 'testval1',
            'param_order' => 'testval2',
            'column_ascend_class' => 'testval3',
            'column_descend_class' => 'testval4',
            'column_sortable_class' => 'testval5',
            'translation_domain' => 'testval6'
        );

        $config = $manager->createConfig('dummy', $options);

        $resolvedOptions = $config->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testRegisterConfig()
    {
        $manager = new SortingManager();

        $preconfig = $manager
            ->createConfig('dummy')
            ->addColumn('name1', 'n.name1');
        $preconfig->setSortingSequence(array('name1' => -1));
        $manager->getStorage('array')->store($preconfig);

        $config = $manager
            ->createConfig('dummy')
            ->addColumn('name1', 'n.name1');

        self::assertEquals(array(), $config->getSortingSequence());
        self::assertFalse($manager->getConfig('dummy'));

        $manager->registerConfig($config);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());
        self::assertSame($config, $manager->getConfig('dummy'));
        self::assertFalse($manager->getConfig('other'));

    }

    public function testRegisterDoubleConfig()
    {
        $this->expectException(\LogicException::class);
        $manager = new SortingManager();
        $config = $manager->createConfig('dummy');
        $config2 = $manager->createConfig('dummy2');

        $manager->registerConfig($config);
        $manager->registerConfig($config2, 'dummy');
    }

    public function testRegisterConfigNoname()
    {
        $this->expectException(\LogicException::class);
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        $manager->registerConfig($config);
    }

    public function testRegisterOtherManagerConfig()
    {
        $this->expectException(\LogicException::class);
        $manager = new SortingManager();
        $config = $manager->createConfig('dummy');

        $manager2 = new SortingManager();
        $manager2->registerConfig($config);
    }

    public function testHandleRequest()
    {
        $options = array(
            'param_column' => 'sort_by',
            'param_order' => 'sort_order',
            'sort_columns_count' => 2
        );

        $manager = new SortingManager();
        $config = $manager->createConfig('dummy', $options);

        $config
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->addColumn('name3', 'n.name3')
            ->addColumn('name4', 'n.name4')
            ->addColumn('name5', 'n.name5')
            ->setDefaultSorting(array('name1' => 1, 'name2' => -1))
            ->register();

        self::assertEquals(array('name1' => 1, 'name2' => -1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name4', 'sort_order' => '1'));
        $config->handleRequest($request);

        self::assertEquals(array('name4' => 1, 'name1' => 1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name1', 'sort_order' => '1'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => 1, 'name4' => 1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name1', 'sort_order' => '-1'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1, 'name4' => 1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name6', 'sort_order' => '-1'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name7', 'sort_order' => '-1'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name5'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array('sort_by' => 'name5', 'sort_order' => '0'));
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());

        $request = Request::create('/', 'GET', array());
        $config->handleRequest($request);

        self::assertEquals(array('name1' => -1), $config->getSortingSequence());

    }

    public function testCreateControl()
    {
        $options = array(
            'column_ascend_class' => 's_asc',
            'column_descend_class' => 's_desc',
            'column_sortable_class' => 's',
            'sort_columns_count' => 3
        );

        $manager = new SortingManager();
        $config = $manager
            ->createConfig('dummy', $options)
            ->addColumn('name1', 'a.name1')
            ->addColumn('name2', 'a.name2', array(
                'label' => 'dodo'
            ))
            ->addColumn('name3', 'a.name3', array(
                'translation_domain' => 'mydomain'
            ))
            ->addColumn('name4', 'b.name4')
            ->addColumn('name5', 'b.name5')
            ->setDefaultSorting(array(
                'name4' => 1,
                'name2' => 1,
                'name3' => -1
            ));

        $control = $config->createControl();

        self::assertEquals(
            array(
                'b.name4' => 1,
                'a.name2' => 1,
                'a.name3' => -1
            ),
            $control->getSortingSequence()
        );

        self::assertEquals(0, $control->isColumnSorted('name1'));
        self::assertEquals(2, $control->isColumnSorted('name2'));
        self::assertEquals(3, $control->isColumnSorted('name3'));
        self::assertEquals(1, $control->isColumnSorted('name4'));
        self::assertEquals(0, $control->isColumnSorted('name5'));

        self::assertEquals(0, $control->getColumnSortOrder('name1'));
        self::assertEquals(1, $control->getColumnSortOrder('name2'));
        self::assertEquals(-1, $control->getColumnSortOrder('name3'));
        self::assertEquals(1, $control->getColumnSortOrder('name4'));
        self::assertEquals(0, $control->getColumnSortOrder('name5'));

        self::assertEquals(
            array(
                'label' => 'dummy.name1.label',
                'column_ascend_class' => 's_asc',
                'column_descend_class' => 's_desc',
                'column_sortable_class' => 's',
                'translation_domain' => 'sortingmanager'
            ),
            $control->getColumnOptions('name1')
        );

        self::assertEquals(
            array(
                'label' => 'dodo',
                'column_ascend_class' => 's_asc',
                'column_descend_class' => 's_desc',
                'column_sortable_class' => 's',
                'translation_domain' => 'sortingmanager'
            ),
            $control->getColumnOptions('name2')
        );

        self::assertEquals(
            array(
                'label' => 'dummy.name3.label',
                'column_ascend_class' => 's_asc',
                'column_descend_class' => 's_desc',
                'column_sortable_class' => 's',
                'translation_domain' => 'mydomain'
            ),
            $control->getColumnOptions('name3')
        );

        self::assertEquals(
            array(
                'label' => 'dummy.name4.label',
                'column_ascend_class' => 's_asc',
                'column_descend_class' => 's_desc',
                'column_sortable_class' => 's',
                'translation_domain' => 'sortingmanager'
            ),
            $control->getColumnOptions('name4')
        );

        self::assertEquals(
            array(
                'label' => 'dummy.name5.label',
                'column_ascend_class' => 's_asc',
                'column_descend_class' => 's_desc',
                'column_sortable_class' => 's',
                'translation_domain' => 'sortingmanager'
            ),
            $control->getColumnOptions('name5')
        );


    }
}