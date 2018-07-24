<?php

namespace FrontendBundle\Controller;

use Component\EventFilter\Model\EventFilters;
use Component\EventFilter\Storage\EventFilterStorageInterface;
use Doctrine\ORM\QueryBuilder;
use FrontendBundle\Entity\Category;
use FrontendBundle\Form\FilterType;
use FrontendBundle\Repository\CategoryRepository;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DefaultController
 * @package FrontendBundle\Controller
 */
class DefaultController extends AbstractController
{

    /** @var integer */
    private $limit = 8;

    private $userLocation;

    /**
     * @Annot\Route("/aboutus")
     *
     * @return mixed
     */
    public function aboutUsAction()
    {
        return $this->render('@Frontend/Default/aboutUs.html.twig');
    }

    /**
     * @Annot\Route("/advertise-with-us", name="advertise_with_us")
     * @return string
     */
    public function advertiseWithUsAction()
    {
        return $this->render('@Frontend/Default/advertiseWithUs.html.twig');
    }

    /**
     * @Annot\Route("/")
     *
     * @param Request $request A HTTP Request instance.
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $this->detectUserLocation($request);

        $user = $this->getUser();
        $eventFiltersStorage = $this->get(EventFilterStorageInterface::class);

        $eventFilters = new EventFilters();

        if ($user instanceof User) {
            if ($user->getFather() === null && $user->getOrganize() === null) {
                return $this->redirectToRoute('frontend_user_createparent');
            }
            $eventFilters = $eventFiltersStorage->getEventFilters($user);
        }

        $form = $this->createForm(FilterType::class, $eventFilters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('session')->set('eventFilters', $request->request->get('filter'));
            return $this->eventFiltersResult($form, $eventFiltersStorage, $user, $eventFilters);
        } else {
            $this->get('session')->remove('eventFilters');
            return $this->eventResult($form);
        }
    }

    /**
     * Help function for Filters
     *
     * @param FormInterface $form A Form instance.
     *
     * @return mixed
     */
    private function eventResult(FormInterface $form)
    {
        $query = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->getEventsForHomepage($this->userLocation)
            ->getQuery();
        /** @var AbstractPagination $events */
        $allEvents = $this->getPagination($query, 1, $this->limit);
        $pagination = $this->getEventsPaginatorResult($allEvents);

        $categoryEvents = $this->getCountCategory();
        $blog = $this->get('app.blog')->getLastPosts(12);

        $dateArray = $this->createDateArray();

        return $this->render('@Frontend/Default/index.html.twig', array(
            'lastNews' => $this->createLastNews(),
            'recommendEvents' => $this->getReccomendEvents(),
            'form' => $form->createView(),
            'blog' => $blog,
            'categories' => $categoryEvents,
            'allEvents' => $allEvents, //$this->orderEventsByDate($allEvents->getItems()),
            'dateArray' => $dateArray,
            'pagination' => $pagination,
        ));
    }

    /**
     * Help function for Filters
     *
     * @param FormInterface               $form         A Form instance.
     * @param EventFilterStorageInterface $storage      A EventFilterStorageInterface.
     * @param User|null                   $user         A user instance.
     * @param EventFilters                $eventFilters A array filters.
     *
     * @return mixed
     */
    private function eventFiltersResult(
        FormInterface $form,
        EventFilterStorageInterface $storage,
        $user,
        EventFilters $eventFilters
    ) {
        if ($user instanceof User) {
            $storage->saveEventFilter($user, $eventFilters);
        }

        $query = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->searchWithFilters($eventFilters)
            ->getQuery();

        /** @var AbstractPagination $events */
        $events = $this->getPagination($query, 1, $this->limit);

        $pagination = $this->getEventsPaginatorResult($events);
        $categoryEvents = $this->getCountCategory($eventFilters);
        $blog = $this->get('app.blog')->getLastPosts(12);

        return $this->render('@Frontend/Default/index.html.twig', array(
            'form' => $form->createView(),
            'allEvents' => $events,
            'categories' => $categoryEvents,
            'dateArray' => $this->createDateArray(),
            'pagination' => $pagination,
            'blog' => $blog,
            'lastNews' => $this->createLastNews(),
            'recommendEvents' => $this->getReccomendEvents(),
            'afterFilters' => true,
        ));
    }

    /**
     * @Annot\Route("/apiClearFilter", methods={ "GET" })
     *
     * @return mixed
     */
    public function apiClearFilter()
    {
        $this->get('session')->remove('eventFilters');

        return JsonResponse::create([
            'status' => 'success',
        ]);
    }

    /**
     * @Annot\Route("/apiEventByDate", methods={ "GET", "POST" })
     *
     * @param Request $request A HTTP Request instance.
     *
     * @return mixed
     */
    public function apiEventByDate(Request $request)
    {
        $this->detectUserLocation($request);

        if ($request->request->get('category') !== null) {
            return $this->apiEventByCategory($request);
        }
        $dateString = $request->request->get('date');
        $eventFilters = $this->get('session')->get('eventFilters');
        if ($eventFilters !== null) {
            $eventFilters = $this->getEventFilters($request);
            $query = $this->getEventsForApiEventByDateWithFilters($eventFilters);
            $categories = $this->getCountCategory($eventFilters);
        } else {
            $query = $this->getEventsForApiEventByDateWithoutFilters($request);
            if ($dateString !== null) {
                $categories = $this->getCountCategory(null, new \DateTime($dateString));
            }
        }
        $categories = isset($categories)
            ? $this->renderView('@Frontend/Default/partial/categories.html.twig', ['categories' => $categories])
            : false;

        $page = ($request->request->get('page') === null) ? 1 : (int) $request->request->get('page');
        /** @var AbstractPagination $events */
        $events = $this->getPagination($query, $page, $this->limit);
        $paginator = $this->getEventsPaginatorResult($events);

        return JsonResponse::create([
            'success' => 'success',
            'paginator' => $paginator,
            'categories' => $categories,
            'html' => $this->renderView('@Frontend/Default/partial/eventList.html.twig', [
                'allEvents' => $events->getItems(),
                // 'allEvents' => $this->orderEventsByDate($events->getItems()),
            ]),
        ]);
    }

    /**
     * @Annot\Route("/apiEventByCategory", methods={ "GET", "POST" })
     *
     * @param Request $request A HTTP Request instance.
     *
     * @return mixed
     */
    public function apiEventByCategory(Request $request)
    {
        $dateString = $request->request->get('date');
        $category = $request->request->get('category');
        $eventFilters = $this->get('session')->get('eventFilters');
        if ($eventFilters !== null) {
            if (empty($eventFilters['categories'])) {
                $eventFilters['categories'] = [$request->request->get('category')];
                $request->request->set('filter', $eventFilters);
                $query = $this->getEventsForApiEventByDateWithFilters($this->getEventFilters($request, false));
            } else {
                $request->request->set('filter', $eventFilters);
                $query = $this->getEventsForApiEventByDateWithFiltersBycategory($this->getEventFilters($request, false), $category);
            }
        } else {
            $repository = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event');
            if ($dateString !== null) {
                $query = $repository->findEventByCategoryByDay(new \DateTime($dateString), $category);
            } else {
                $query = $repository->getEventsByCategoryForHomepage($category);
            }
        }

        $page = ($request->request->get('page') === null) ? 1 : (int) $request->request->get('page');
        /** @var AbstractPagination $events */
        $events = $this->getPagination($query, $page, $this->limit);
        $paginator = $this->getEventsPaginatorResult($events);

        return JsonResponse::create([
            'success' => 'success',
            'paginator' => $paginator,
            'html' => $this->renderView('@Frontend/Default/partial/eventList.html.twig', ['allEvents' => $events->getItems()]),
        ]);
    }


    /**
     * @Annot\Route("/edit")
     *
     * @return mixed
     */
    public function editAction()
    {
        return array();
    }

    /**
     * Help function for apiEventByDate
     *
     * @param Request $request A request instance.
     * @param boolean $flag    If $flag is false, request already with filters.
     *
     * @return mixed
     */
    private function getEventFilters(Request $request, bool $flag = true)
    {
        if ($flag) {
            $request->request->set('filter', $this->get('session')->get('eventFilters'));
        }
        $eventFilters = new EventFilters();
        $form = $this->createForm(FilterType::class, $eventFilters);
        $form->handleRequest($request);
        if ($request->request->get('date') !== null) {
            $eventFilters->setDateEvent(new \DateTime($request->request->get('date')));
        }

        return $eventFilters;
    }

    /**
     * Help function for apiEventByDate
     *
     * @param EventFilters $eventFilters A EventFilters instance.
     *
     * @return mixed
     */
    private function getEventsForApiEventByDateWithFilters(EventFilters $eventFilters)
    {
        return $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->searchWithFilters($eventFilters);
    }


    /**
     * Help function for apiEventByDate
     *
     * @param EventFilters $eventFilters A EventFilters instance.
     * @param integer      $category     A Category id.
     *
     * @return mixed
     */
    private function getEventsForApiEventByDateWithFiltersBycategory(EventFilters $eventFilters, int $category)
    {
        $query = $this->getDoctrine() // Repository
        ->getRepository('FrontendBundle:Event')
            ->searchWithFilters($eventFilters);
        $query->join('Event.category', 'Category2');
        $query->andWhere($query->expr()->eq('Category2.id', ':categories2'));
        $query->setParameter('categories2', $category);
        return $query;
    }

    /**
     * Help function for apiEventByDate
     *
     * @param Request $request A request instance.
     *
     * @return mixed
     */
    private function getEventsForApiEventByDateWithoutFilters(Request $request)
    {
        $searchByName = $request->request->get('searchByName','');

        if ($request->request->get('date') !== null) {
            return $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findByDayEvents(new \DateTime($request->request->get('date')), $this->userLocation, $searchByName);
        } else {
            return $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->getEventsForHomepage($this->userLocation, $searchByName);
        }
    }

    /**
     * Help function for Filters
     *
     *
     * @return mixed
     */
    private function createDateArray()
    {
        $dateArray = array();
        $date = new \DateTime();
        for ($i = 0; $i < 100; $i++) {
            $date2 = new \DateTime($date->modify("+ 1 day")->format('y-m-d'));
            array_push($dateArray, $date2);
        }
        $date = new \DateTime($date->modify("- 200 day")->format('y-m-d'));
        for ($i = 0; $i < 100; $i++) {
            $date2 = new \DateTime($date->modify("+ 1 day")->format('y-m-d'));
            array_push($dateArray, $date2);
        }

        return $dateArray;
    }

    /**
     * Help function for Filters
     *
     * @param QueryBuilder $query      A Query instance.
     * @param integer      $pageNumber A page number.
     * @param integer      $limit      A limit result.
     *
     * @return mixed
     */
    private function getPagination($query, int $pageNumber, int $limit)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            $limit,
            ['wrap-queries' => true]
        );
        $this->addCheckedUserLiked($pagination);
        return $pagination;
    }

    /**
     * Help function for Filters
     *
     * @param AbstractPagination $pagination A AbstractPagination instance.
     *
     * @return mixed
     */
    private function getEventsPaginatorResult(AbstractPagination $pagination)
    {
        $countPage = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());
        $currentPage = $pagination->getCurrentPageNumber();

        return $this->renderView('@Frontend/Default/partial/paginationEventList.html.twig', [
            'countPage' => $countPage,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * Help function for Filters
     *
     * @param EventFilters | null $eventFilters A EventFilters instance.
     * @param \DateTime | null    $date         A DateTime instance.
     *
     * @return array
     */
    private function getCountCategory($eventFilters = null, $date = null)
    {
        $categories = $this->getDoctrine()
            ->getRepository('FrontendBundle:Category')
            ->findAll();
        $categoryEvents = [];
        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        if ($eventFilters !== null) {
            foreach ($categories as $category) {
                $query = $this->getEventsForApiEventByDateWithFiltersBycategory($eventFilters, $category->getId());
                $paginator = new Paginator(
                    $query->setFirstResult(0)->setMaxResults(1),
                    $fetchJoinCollection = true
                );
                $categoryEvents[] = [
                    'id' => $category->getId(),
                    'name' => $category->getTag(),
                    'count' => $paginator->count(),
                ];
            }
            return $categoryEvents;
        }
        if ($date !== null) {
            foreach ($categories as $category) {
                $query = $this->getDoctrine()
                    ->getRepository('FrontendBundle:Event')
                    ->findEventByCategoryByDay($date, $category);
                $paginator = new Paginator(
                    $query->setFirstResult(0)->setMaxResults(1),
                    $fetchJoinCollection = true
                );
                $categoryEvents[] = [
                    'id' => $category->getId(),
                    'name' => $category->getTag(),
                    'count' => $paginator->count(),
                ];
            }
            return $categoryEvents;
        }
        foreach ($categories as $category) {
            $query = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->getEventsByCategoryForHomepage($category);
            $paginator = new Paginator(
                $query->setFirstResult(0)->setMaxResults(1),
                $fetchJoinCollection = true
            );
            $categoryEvents[] = [
                'id' => $category->getId(),
                'name' => $category->getTag(),
                'count' => $paginator->count(),
            ];
        }
        return $categoryEvents;
    }

    /**
     * Help Function get Recommend Events
     *
     * @return array
     */
    private function getReccomendEvents()
    {
        $qb = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->getRecommend($this->userLocation);

        $events = $this->getPagination($qb, 1, 50);
        return $this->orderEventsByDate($events->getItems());//$this->addCheckedUserLiked($events);
    }

    /**
     * Help function for LastNews
     *
     *
     * @return mixed
     */
    private function createLastNews()
    {
        if ($this->generateUrl('frontend_default_index', [], UrlGeneratorInterface::ABSOLUTE_URL) === 'http://demo.wonderly.dev7.sibers.com/') {
            return $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->getEventForLastNewsWithoutIP(30);
        }

        if (!$this->userLocation) {
            return $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->getEventForLastNewsWithoutIP(30);
        }

        $events = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->getEventForLastNews(30, $this->userLocation['lat'], $this->userLocation['lon']);

        return $this->orderEventsByDate($events);
    }

    /**
     * @param array $events
     * @return array
     */
    private function orderEventsByDate($events)
    {
        // order by date
        $eventNearestDate = [];
        foreach ($events as $event) {
            $eventNearestDate[$event->getId()] = $this->container
                ->get('app.nearest.date')
                ->getNearestDate($event->getEventDate(), $event->getEventDateEnd(), $event->getPeriodic()->toArray());
        }

        usort($events, function($a, $b) use ($eventNearestDate) {
            $date1 = $eventNearestDate[$a->getId()];
            $date2 = $eventNearestDate[$b->getId()];

            if ($date1 === $date2) {
                return $a->getOrigin() > $b->getOrigin();
            }

            return $date1 > $date2;
        });

        // filter out events that already happened today
        $now = new \DateTime();
        $currentDate = $now->format('m/d/y');
        $currentTime = $now->format('H:i:s');
        $events = array_filter($events, function($event) use ($currentDate, $currentTime, $eventNearestDate) {
            $originDate = $eventNearestDate[$event->getId()];

            if ($currentDate === $originDate) {
                $origin = $event->getOrigin();

                if (!$origin) {
                    return false;
                }

                $originTime = $origin->format('H:i:s');
                return $originTime > $currentTime;
            }

            return $originDate > $currentDate;
        });

        return $events;
    }

    /**
     * @param Request $request
     */
    private function detectUserLocation(Request $request)
    {
        // get location from URL
        $location = $request->query->get('location');

        if ($location) {
            list($currentLatitude, $currentLongitude) = explode(',', $location);
        } else {
            if ($this->get('session')->get('location')) {
                $this->userLocation = $this->get('session')->get('location');
                return;
            }

            // detect by IP
            $ip = $this->get('request_stack')->getCurrentRequest()->server->get("REMOTE_ADDR");
            $ip = ($ip == '94.251.80.158') ? '172.15.14.18' : $ip;

            try {
                $record = $this->get('geoip2.reader')->city($ip); // Replace with IP on server
            } catch (\Exception $e) {
                // can't detect
                return;
            }

            $currentLatitude = $record->location->latitude;
            $currentLongitude = $record->location->longitude;
        }

        $this->userLocation = [
            'lat' => $currentLatitude,
            'lon' => $currentLongitude,
        ];

        $this->get('session')->set('location', $this->userLocation);
    }
}
