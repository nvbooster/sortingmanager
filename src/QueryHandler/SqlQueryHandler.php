<?php
namespace nvbooster\SortingManager\QueryHandler;

use nvbooster\SortingManager\QueryHandler\QueryHandlerInterface;
use nvbooster\SortingManager\ControlInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SqlQueryHandler implements QueryHandlerInterface
{

    /**
     * @var ControlInterface
     */
    protected $control;

    /**
     * @param ControlInterface $control
     */
    public function __construct(ControlInterface $control)
    {
        $this->control = $control;
    }

    /**
     * @param string $withKeyword
     *
     * @return string
     */
    public function getOrderSql($withKeyword = true)
    {
        $orderParts = array();

        foreach ($this->control->getSortingSequence() as $field => $order) {
            $orderParts[] = $field . (($order > 0) ? ' ASC': ' DESC');
        }

        $sql = '';
        if (count($orderParts)) {
            if ($withKeyword) {
                $sql .= 'ORDER BY ';
            }

            $sql .= implode(', ', $orderParts);
        }

        return $sql;
    }
}