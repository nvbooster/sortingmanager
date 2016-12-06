<?php
namespace nvbooster\SortingManager\Tests\QueryHandler;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\QueryHandler\SqlQueryHandler;
use nvbooster\SortingManager\Control;

class SqlQueryHandlerTest extends TestCase
{
    public function testIfConstructed()
    {
        $control = new Control(array(), array(), array(
            'param_column' => 'sb',
            'param_order' => 'so'
        ));

        $handler = new SqlQueryHandler($control);

        self::assertInstanceOf(SqlQueryHandler::class, $handler);
    }

    public function testRawSql()
    {
        $control = new Control(
            array(),
            array(
                'a.name1' => 1,
                'b.name1' => -1
            ),
            array(
                'param_column' => 'sb',
                'param_order' => 'so'
            )
        );

        $handler = new SqlQueryHandler($control);
        self::assertEquals('ORDER BY a.name1 ASC, b.name1 DESC', $handler->getOrderSql());
    }

    public function testRawSqlSingleColumn()
    {
        $control = new Control(
            array(),
            array(
                'a.name1' => 1
            ),
            array(
                'param_column' => 'sb',
                'param_order' => 'so'
            )
        );

        $handler = new SqlQueryHandler($control);
        self::assertEquals('a.name1 ASC', $handler->getOrderSql(false));
    }

    public function testRawSqlNoColumn()
    {
        $control = new Control(
            array(),
            array(),
            array(
                'param_column' => 'sb',
                'param_order' => 'so'
            )
        );

        $handler = new SqlQueryHandler($control);
        self::assertEquals('', $handler->getOrderSql());
    }
}