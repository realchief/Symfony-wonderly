<?php

namespace Component\TableProcessor;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface TableProcessorSourceInterface
 *
 * @package Component\TableProcessor
 */
interface TableProcessorSourceInterface
{

    /**
     * Get data for table processing.
     *
     * @param array $filters Raw array of filters.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(array $filters);

    /**
     * Get action urls for specified entity.
     *
     * @param UrlGeneratorInterface $urlGenerator A UrlGeneratorInterface
     *                                            instance.
     * @param string|integer        $id           A entity instance id.
     *
     * @return array
     */
    public function getActionUrls(UrlGeneratorInterface $urlGenerator, $id);

    /**
     * @param array $row Normalize single row of results.
     *
     * @return array
     */
    public function normalize(array $row);
}
