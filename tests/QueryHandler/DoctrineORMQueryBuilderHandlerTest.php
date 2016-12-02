<?php
namespace nvbooster\SortingManager\Tests\QueryHandler;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\QueryHandler\DoctrineORMQueryBuilderHandler;
use nvbooster\SortingManager\Control;
use Doctrine\ORM\QueryBuilder;

class DoctrineORMQueryBuilderHandlerTest extends TestCase
{
    public function testIfConstructed()
    {
        $control = new Control(array(), array(), array(
            'param_column' => 'sb',
            'param_order' => 'so'
        ));

        $handler = new DoctrineORMQueryBuilderHandler($control);

        self::assertInstanceOf('nvbooster\SortingManager\QueryHandler\DoctrineORMQueryBuilderHandler', $handler);
    }

    public function testQueryBuilder()
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

        $handler = new DoctrineORMQueryBuilderHandler($control);

        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder
            ->expects($this->exactly(2))
            ->method('addOrderBy')
            ->withConsecutive(
                array('a.name1', 'ASC'),
                array('b.name1', 'DESC')
            )
            ->will($this->returnValue($queryBuilder));

        $handler->modifyQueryBuilder($queryBuilder);
    }

    public function testQueryBuilderSingleColumn()
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

        $handler = new DoctrineORMQueryBuilderHandler($control);

        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder
            ->expects($this->exactly(1))
            ->method('addOrderBy')
            ->withConsecutive(
                array('a.name1', 'ASC')
            )
            ->will($this->returnValue($queryBuilder));

        $handler->modifyQueryBuilder($queryBuilder);
    }

    public function testQueryBuilderNoColumn()
    {
        $control = new Control(
            array(),
            array(),
            array(
                'param_column' => 'sb',
                'param_order' => 'so'
            )
        );

        $handler = new DoctrineORMQueryBuilderHandler($control);

        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder->expects($this->exactly(0))->method('addOrderBy');

        $handler->modifyQueryBuilder($queryBuilder);
    }

}