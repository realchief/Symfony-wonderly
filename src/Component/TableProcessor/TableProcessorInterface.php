<?php

namespace Component\TableProcessor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface TableProcessorInterface
 *
 * @package Component\TableProcessor
 */
interface TableProcessorInterface
{

    /**
     * @param Request  $request    A HTTP Request instance.
     * @param string   $entityFqcn A entity fqcn used for paginating.
     * @param callable $callback   A callback fo additional query builder processing.
     *
     * @return Response
     */
    public function processTableRequest(
        Request $request,
        string $entityFqcn,
        callable $callback = null
    );
}
