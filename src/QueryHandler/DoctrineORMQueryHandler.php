<?php
namespace nvbooster\SortingManager\QueryHandler;

use nvbooster\SortingManager\QueryHandler\SqlQueryHandler;
use Doctrine\ORM\QueryBuilder;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class DoctrineORMQueryHandler extends SqlQueryHandler
{
    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function modifyQueryBuilder(QueryBuilder $qb)
    {
        foreach ($this->control->getSortingSequence() as $field => $order) {
            $qb->addOrderBy($field, (($order > 0) ? ' ASC': ' DESC'));
        }

        return $qb;
    }
}