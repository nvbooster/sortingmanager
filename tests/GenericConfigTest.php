<?php
namespace nvbooster\SortingManager\Tests;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\GenericConfig;
use nvbooster\SortingManager\SortingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenericConfigTest extends TestCase
{
    public function testIfConstructed()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        self::assertInstanceOf(GenericConfig::class, $config);
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

        $manager = new SortingManager();
        $config = new GenericConfig($manager, $options);

        $resolvedOptions = $config->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testEmptyOptions()
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
        $config = new GenericConfig($manager, array());

        $resolvedOptions = $config->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testExtraOptions()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);

        $manager = new SortingManager();
        $config = new GenericConfig($manager, array('fakeparam' => 5));

        $config->getOptions();
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
        $config = new GenericConfig($manager);

        $config->setOptions($options);
        $resolvedOptions = $config->getOptions();

        foreach ($options as $key => $value) {
            self::assertEquals($value, $resolvedOptions[$key]);
        }
    }

    public function testSetName()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);
        self::assertNull($config->getName());

        $config->setName('dummy');
        self::assertEquals('dummy', $config->getName());
    }

    public function testGetManager()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);
        self::assertSame($manager, $config->getManager());
    }

    public function testAddColumn()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        $config
            ->addColumn('name1', 'field1')
            ->addColumn('name2', 'field2', array())
            ->addColumn('name3', 'field3', array('testoption' => 1));

        self::assertEquals(array(
            'name1' => array(
                'name' => 'name1',
                'field' => 'field1',
                'options' => array()
            ),
            'name2' => array(
                'name' => 'name2',
                'field' => 'field2',
                'options' => array()
            ),
            'name3' => array(
                'name' => 'name3',
                'field' => 'field3',
                'options' => array(
                    'testoption' => 1
                )
            )
        ), $config->getColumns());
    }

    public function testUnnamedColumn()
    {
        $this->expectException(\LogicException::class);

        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        $config->addColumn('', 'field2');
    }

    public function testDoubleColumn()
    {
        $this->expectException(\LogicException::class);

        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        $config
            ->addColumn('name1', 'field1')
            ->addColumn('name1', 'field2');
    }

    public function testDefaultSorting()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        self::assertEquals(array(), $config->getSortingSequence());

        $config->setDefaultSorting(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array(), $config->getSortingSequence());

        $config->addColumn('name2', 'field1');
        $config->setDefaultSorting(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name2' => 1), $config->getSortingSequence());

        $config->addColumn('name1', 'field2');
        $config->setDefaultSorting(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name1' => -3, 'name2' => 1), $config->getSortingSequence());

        $config->addColumn('name3', 'ff');
        $config->setDefaultSorting(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name1' => -3, 'name2' => 1, 'name3' => 0), $config->getSortingSequence());
    }

    public function testSorting()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        self::assertEquals(array(), $config->getSortingSequence());

        $config->setSortingSequence(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array(), $config->getSortingSequence());

        $config->addColumn('name2', 'field1');
        $config->setSortingSequence(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name2' => 1), $config->getSortingSequence());

        $config->addColumn('name1', 'field2');
        $config->setSortingSequence(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name1' => -3, 'name2' => 1), $config->getSortingSequence());

        $config->addColumn('name3', 'ff');
        $config->setSortingSequence(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name1' => -3, 'name2' => 1, 'name3' => 0), $config->getSortingSequence());
    }


    public function testSetSortingSequence()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);

        $config
            ->addColumn('name1', 'field1')
            ->addColumn('name2', 'field2')
            ->addColumn('name3', 'ff')
            ->addColumn('name4', 'fe');
        $config->setDefaultSorting(array('name1' => -3, 'name2' => 1, 'name3' => 0));
        self::assertEquals(array('name1' => -3, 'name2' => 1, 'name3' => 0), $config->getSortingSequence());

        $config->setSortingSequence(array('name4' => 1, 'name2' => -1));
        self::assertEquals(array('name4' => 1, 'name2' => -1), $config->getSortingSequence());

        $config->setDefaultSorting(array('name3' => -1));
        self::assertEquals(array('name4' => 1, 'name2' => -1), $config->getSortingSequence());

        $config->setSortingSequence(array());
        self::assertEquals(array(), $config->getSortingSequence());
    }

    public function testRegister()
    {
        $manager = new SortingManager();
        $config = new GenericConfig($manager);
        $config
            ->setName('dummy')
            ->register();

        self::assertSame($config, $manager->getConfig('dummy'));
    }

    public function testColumnOptions() {
        $resolver = new OptionsResolver();

        $manager = new SortingManager();
        $config = new GenericConfig($manager);
        $config->configureColumnOptions($resolver);

        $options = array(
            'label' => 'testval_1',
            'column_ascend_class' => 'testval_2',
            'column_descend_class' => 'testval_3',
            'column_sortable_class' => 'testval_4',
            'translation_domain' => 'testval_5'
        );

        self::assertEquals($options, $resolver->resolve($options));
        self::assertEquals(array(), $resolver->resolve(array()));
    }

    public function testExtraColumnOptions() {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);
        $resolver = new OptionsResolver();

        $manager = new SortingManager();
        $config = new GenericConfig($manager);
        $config->configureColumnOptions($resolver);

        $options = array(
            'storage' => 'array',
            'sort_columns_count' => 2,
            'param_column' => 'testval1',
            'param_order' => 'testval2',
        );

        $resolver->resolve($options);
    }
}