<?php

namespace FrontendBundle\Repository\AbstractRepository;

use Component\EventFilter\Model\ComparableValue;
use Component\EventFilter\Model\EventFilters;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Query\Mysql\Date;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Event;
use Component\TableProcessor\TableProcessorSourceInterface;


use function \nspl\a\map;
use function \nspl\op\methodCaller;
use function Sodium\add;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\User;

/**
 * AbstractRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbstractRepository extends EntityRepository
{

    /** @var array  */
    protected $periodicMaps = [
        '0Monday', '0Tuesday', '0Wednesday', '0Thursday', '0Friday', '0Saturday', '0Sunday',
        '1Monday', '1Tuesday', '1Wednesday', '1Thursday', '1Friday', '1Saturday', '1Sunday',
        '2Monday', '2Tuesday', '2Wednesday', '2Thursday', '2Friday', '2Saturday', '2Sunday',
        '3Monday', '3Tuesday', '3Wednesday', '3Thursday', '3Friday', '3Saturday', '3Sunday',
        '4Monday', '4Tuesday', '4Wednesday', '4Thursday', '4Friday', '4Saturday', '4Sunday',
    ];

    protected $operatorMap = array(
        ComparableValue::OPERATOR_EQ => Comparison::EQ,
        ComparableValue::OPERATOR_NEQ => Comparison::NEQ,
        ComparableValue::OPERATOR_GT => Comparison::GT,
        ComparableValue::OPERATOR_GTE => Comparison::GTE,
        ComparableValue::OPERATOR_LT => Comparison::LT,
        ComparableValue::OPERATOR_LTE => Comparison::LTE
    );

    /**
     * Get Event by day.
     *
     * @param \DateTime       $eventDate      Time start.
     * @param ArrayCollection $parameters     ArrayCollection.
     * @param Composite       $whereCondition Conditions to which a new condition
     *                                        is added.
     *
     * @return array
     */
    protected function findByDay(\DateTime $eventDate, ArrayCollection $parameters, Composite $whereCondition)
    {
        $expr = $this->_em->getExpressionBuilder();

        $days = $this->helpFunctionDefinedDay($eventDate);

        $dateDay = $eventDate->format('m/d/Y');

        $exprCondition1 = $expr->andX(
            $expr->lte('Event.eventDate', ':eventDate'),
            $expr->gte('Event.eventDateEnd', ':eventDate')
        );
        $exprCondition2 = $expr->eq('Periodic.day', ':periodic1');
        $exprCondition3 = $expr->eq('Periodic.day', ':dateDay');

        $exprCondition4 = $expr->orX(
            $expr->eq('Periodic.day', ':periodic2')
        );
        $exprCondition5 = $expr->andX(
            $expr->isNull('Event.eventDate'),
            $expr->isNull('Periodic.id')
        );

        if (isset($days[1])) {
            $whereCondition->add(
                $expr->orX(
                    $exprCondition1,
                    $exprCondition2,
                    $exprCondition3,
                    $exprCondition4,
                    $exprCondition5
                )
            );
            $parameters->add(new Parameter('periodic2', $days[1]));
        } else {
            $whereCondition->add(
                $expr->orX(
                    $exprCondition1,
                    $exprCondition2,
                    $exprCondition3,
                    $exprCondition5
                )
            );
        }
        $parameters->add(new Parameter('periodic1', $days[0]));
        $parameters->add(new Parameter('dateDay', $dateDay));

        return ['whereCondition' => $whereCondition, 'parameters' => $parameters];
    }


    /**
     * @param Composite       $condition  Conditions to which a new condition
     *                                    is added.
     * @param ArrayCollection $parameters Conditions parameters.
     *
     * @return array
     */
    protected function findWithoutDay(Composite $condition, ArrayCollection $parameters)
    {
        $date = new \DateTime();
        $parameters->add(new Parameter('date', $date));

        $expr = $this->_em->getExpressionBuilder();

        $exprCondition1 = $expr->andX(
            $expr->isNull('Event.eventDate'),
            $expr->orX(
                $expr->in('Periodic.day', ':periodicMaps'),
                $expr->isNull('Periodic.id'),
                $expr->gte('Periodic.day', ':periodicToday')
            )
        );
        $exprCondition3 = $expr->gte('Event.eventDateEnd', ':date');
        $exprCondition4 = $expr->andX(
            $expr->isNull('Event.eventDateEnd'),
            $expr->gte('Event.eventDateEnd', ':date')
        );
        $condition->add(
            $expr->orX(
                $exprCondition1,
                $exprCondition3,
                $exprCondition4
            )
        );

        $parameters->add(new Parameter('periodicMaps', $this->periodicMaps));
        $parameters->add(new Parameter('periodicToday', $date->format('m/d/Y')));

        return ['parameters' => $parameters, 'condition' => $condition];
    }


    /**
     * Get Event by day.
     *
     * @param \DateTime $eventDate Time start.
     *
     * @return array
     */
    protected function helpFunctionDefinedDay(\DateTime $eventDate)
    {
        $day = (int)$eventDate->format('j');

        if ($day - 7 <= 0) {
            $date = 1;
        } elseif ($day - 14 <= 0) {
            $date = 2;
        } elseif ($day - 21 <= 0) {
            $date = 3;
        } elseif ($day - 28 <= 0) {
            $date = 4;
        }
        if (isset($date)) {
            return [$date . $eventDate->format('l'), 0 . $eventDate->format('l')];
        }
        return [0 . $eventDate->format('l')];
    }
}
