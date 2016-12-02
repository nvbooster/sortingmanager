<?php
namespace nvbooster\SortingManager\Tests;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\Control;

class ControlTest extends TestCase
{
    public function testIfConstructed()
    {
        $control = new Control(array(), array(), array(
            'param_column' => 'sb',
            'param_order' => 'so'
        ));

        self::assertInstanceOf(Control::class, $control);
        self::assertEquals('sb', $control->getSortByParam());
        self::assertEquals('so', $control->getSortOrderParam());
    }

    public function testIfNoOptions()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\MissingOptionsException::class);
        $control = new Control(array(), array(), array());
    }

    public function testExtraOptions()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);
        $control = new Control(array(), array(), array(
            'param_column' => 'sb',
            'param_order' => 'so',
            'foo' => 'bar'
        ));
    }

    /**
     * @return Control
     */
    public function createControl()
    {
        $options = array(
            'param_column' => 'sb',
            'param_order' => 'so'
        );

        $columns = array(
            'name1' => array(
                'sort_pos' => 0,
                'sort_order' => 0,
                'options' => array(
                    'foo' => 'bar'
                )
            ),
            'name2' => array(
                'sort_pos' => 2,
                'sort_order' => 1,
                'options' => array(
                    'foo' => 'bar'
                )
            ),
            'name3' => array(
                'sort_pos' => 0,
                'sort_order' => 0,
                'options' => array(
                    'foo' => 'bar'
                )
            ),
            'name4' => array(
                'sort_pos' => 1,
                'sort_order' => -1,
                'options' => array(
                    'foo' => 'bar'
                )
            )
        );

        $sequence = array(
            'a.name4' => -1,
            'a.name2' => 1,
        );

        return new Control($columns, $sequence, $options);
    }

    public function testSortOrder()
    {
        $control = $this->createControl();

        self::assertEquals(-1, $control->getColumnSortOrder('name4'));
        self::assertEquals(0, $control->getColumnSortOrder('name3'));
        self::assertEquals(1, $control->getColumnSortOrder('name2'));
        self::assertNull($control->getColumnSortOrder('name5'));
    }

    public function testSorted()
    {
        $control = $this->createControl();

        self::assertEquals(1, $control->isColumnSorted('name4'));
        self::assertEquals(0, $control->isColumnSorted('name3'));
        self::assertEquals(2, $control->isColumnSorted('name2'));
        self::assertNull($control->isColumnSorted('name5'));
    }

    public function testColumnOptions()
    {
        $control = $this->createControl();

        self::assertEquals(array('foo' => 'bar'), $control->getColumnOptions('name4'));
        self::assertEquals(array('foo' => 'bar'), $control->getColumnOptions('name3'));
        self::assertNull($control->getColumnOptions('name5'));
    }

    public function testSortable()
    {
        $control = $this->createControl();

        self::assertTrue($control->isColumnSortable('name4'));
        self::assertTrue($control->isColumnSortable('name3'));
        self::assertFalse($control->isColumnSortable('name5'));
    }

    public function testSequence()
    {
        $control = $this->createControl();

        self::assertEquals(array('a.name4' => -1, 'a.name2' => 1,), $control->getSortingSequence());
    }

}