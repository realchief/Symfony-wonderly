<?php

namespace UserBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Component\TableProcessor\TableProcessorSourceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\Query\Parameter;

class UserRepository extends EntityRepository implements TableProcessorSourceInterface
{
    /**
     * Get data for table processing.
     *
     * @param array $filters Raw array of filters.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(array $filters = [])
    {
        $expr = $this->_em->getExpressionBuilder();
        $condition = $expr->andX();
        $parameters = new ArrayCollection();
        $qb = $this->createQueryBuilder('User')
            ->select('User')
            ->setParameters($parameters)
            ->groupBy('User.id');
        if ($condition->count() > 0) {
            $qb
                ->where($condition)
                ->setParameters($parameters);
        }
        return $qb;
    }

    /**
     * Get action urls for specified entity.
     *
     * @param UrlGeneratorInterface $urlGenerator A UrlGeneratorInterface
     *                                            instance.
     * @param string|integer        $id           A entity instance id.
     *
     * @return array
     */
    public function getActionUrls(UrlGeneratorInterface $urlGenerator, $id)
    {
        return [];
    }

    /**
     * @param array $row Normalize single row of results.
     *
     * @return array
     */
    public function normalize(array $row)
    {
        return $row;
    }

}