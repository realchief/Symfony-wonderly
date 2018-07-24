<?php

namespace Component\TableProcessor;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function \nspl\a\map;

/**
 * Class JQueryDataTableProcessor
 *
 * @package Component\TableProcessor
 */
class JQueryDataTableProcessor implements TableProcessorInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * JQueryDataTableProcessor constructor.
     *
     * @param EntityManagerInterface $em           A EntityManagerInterface instance.
     * @param PaginatorInterface     $paginator    A PaginatorInterface instance.
     * @param UrlGeneratorInterface  $urlGenerator A UrlGeneratorInterface instance.
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->urlGenerator = $urlGenerator;
    }

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
    ) {
        $offset = $request->query->getInt('start');
        $limit = $request->query->getInt('length', 10);
        $draw = $request->query->getInt('draw', 1);

        $repository = $this->em->getRepository($entityFqcn);
        if (! $repository instanceof TableProcessorSourceInterface) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid entity \'%s\'. Entity repository should be instance of %s',
                $entityFqcn,
                TableProcessorSourceInterface::class
            ));
        }

        $qb = $repository->getQueryBuilder($request->query->get('filter', []))
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        //
        // Apply orders.
        //
        $orders = $request->query->get('order', []);
        $columns = $request->query->get('columns', []);
        foreach ($orders as $order) {
            $qb->addOrderBy($columns[$order['column']]['name'], $order['dir']);
        }

        if ($callback) {
            $qb = $callback($qb);
        }

        $qb = $qb
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);

        //
        // Compute current page. Add one 'cause in KNP Paginator page starts
        // from one.
        //
        $page = ($offset / $limit) + 1;

        /** @var AbstractPagination $pagination */
        $pagination = $this->paginator->paginate($qb, $page, $limit);
        $count = $pagination->getTotalItemCount();

        //
        // Iterate through each value of each row and normalize it and add proper
        // action urls.
        //
        $data = map(function ($row) use ($repository) {
            $row = $this->normalize($repository->normalize($row));
            $row['action'] = $repository->getActionUrls($this->urlGenerator, $row['id']);

            return $row;
        }, $pagination);

        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
        ]);
    }

    /**
     * @param array $data A normalized data.
     *
     * @return array
     */
    private function normalize(array $data)
    {
        return map(function ($value) {
            if (is_array($value)) {
                $value = $this->normalize($value);
            } elseif ($value instanceof \DateTime) {
                $value = $value->format('c');
            }

            return $value;
        }, $data);
    }
}
