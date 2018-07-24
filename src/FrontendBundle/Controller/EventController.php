<?php

namespace FrontendBundle\Controller;

use AppBundle\Mailer\Mailer;
use Component\Locator\AddressResolverException;
use Component\Locator\AddressResolverInterface;
use FrontendBundle\Entity\ContactMessage;
use FrontendBundle\Entity\Event;
use FrontendBundle\Entity\ImageEvent;
use FrontendBundle\Repository\EventRepository;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\User;
use Doctrine\ORM\Query;

/**
 * Class EventController
 * @package FrontendBundle\Controller
 */
class EventController extends AbstractController
{

    private $limit = 80;

    /**
     * @Annot\Route("/event/list")
     * @Annot\Template
     *
     * @param Request $request A http request.
     *
     * @return mixed
     */
    public function eventListAction(Request $request)
    {
        $date = $request->get('date');
        if ($date === null) {
            $date = new \DateTime();
            $events = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findAllDay(10);

            $eventsMorning = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '06:00'), \DateTime::createFromFormat('H:i', '12:00'), 10);
            $eventsAfternoon = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '12:00'), \DateTime::createFromFormat('H:i', '17:00'), 10);
            $eventsEvening = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '17:00'), \DateTime::createFromFormat('H:i', '23:59'), 10);
        } else {
            $date = new \DateTime($date);
            $events = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findAllDay(10, $date);

            $eventsMorning = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '06:00'), \DateTime::createFromFormat('H:i', '12:00'), 10, $date);
            $eventsAfternoon = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '12:00'), \DateTime::createFromFormat('H:i', '17:00'), 10, $date);
            $eventsEvening = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->findForTime(\DateTime::createFromFormat('H:i', '17:00'), \DateTime::createFromFormat('H:i', '23:59'), 10, $date);
            $currentDate = new \DateTime();
            if ($date > ($currentDate->modify("+ 4 day"))) {
                $date->modify("- 4 day");
            }
        }
        $categories = $this->getDoctrine()
            ->getRepository('FrontendBundle:Category')
            ->findAll();
        $categoryEvents = [];
        foreach ($categories as $category) {
            $categoryEvents[$category->getTag()] = $this->getDoctrine()
                ->getRepository('FrontendBundle:Event')
                ->getByCategory($category->getId(), 12);
        }

        $dateArray = array();

        for ($i = 0; $i < 9; $i++) {
            $date2 = new \DateTime($date->modify("+ 1 day")->format('y-m-d'));
            array_push($dateArray, $date2);
        }

        return array(
            'events' => $events,
            'eventsMorning' => $eventsMorning,
            'eventsAfternoon' => $eventsAfternoon,
            'eventsEvening' => $eventsEvening,
            'dateArray' => $dateArray,
            'categoryEvents' => $categoryEvents,
        );
    }

    /**
     * @Annot\Route("/event/favorite")
     * @Annot\Template
     *
     * @param Request $request A http request.
     *
     * @return mixed
     */
    public function eventFavoriteAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $query = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->getEventsIfUserLiked($user);
        $allEvents = $this->getPagination($query, $request);
        $allEvents = $this->addCheckedUserLiked($allEvents);

        return ['allEvents' => $allEvents];
    }

    /**
     * @Annot\Route("/event/edit/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param string|integer $id      A event id.
     * @param Request        $request A Request instance.
     *
     * @return array
     */
    public function eventEditAction($id, Request $request)
    {
        /** @var EventRepository $repository */
        $repository = $this->getDoctrine()->getRepository('FrontendBundle:Event');

        $event = $repository->get($id);
        if (! $event instanceof Event) {
            throw $this->createNotFoundException();
        }
        $organizer = $event->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }
        $ages = clone $event->getAge();
        $address = $event->getAddress();
        $form = $this->createForm('FrontendBundle\Form\EventType', $event);
        $form->remove('organize');
        $form->remove('recommend');
        $form->handleRequest($request);
        $periodicTableArray = $this->getPeriodicArray($event);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($event->getPrice() == false) {
                $event->setPrice(null);
            }
            $em = $this->getDoctrine()->getManager();
            try {
                /** @var AddressResolverInterface $addressResolver */
                $addressResolver = $this->get('Component\Locator\AddressResolverInterface');
                $event->resolveAddress($addressResolver);
            } catch (AddressResolverException $exception) {
                return array(
                    'event' => $event,
                    'form' => $form->createView(),
                    'periodicArr' => $periodicTableArray[0],
                    'multipleDatesArr' => $periodicTableArray[1],
                    'errorAddress' => $exception->getMessage(),
                );
            }

            if ($event->getAddress() !== $address) {
                $event->resolveAddress($addressResolver);
            }

            foreach ($ages as $age) {
                $em->remove($age);
            }

            $em->persist($event);
            $em->flush();
        }

        return array(
            'event' => $event,
            'form' => $form->createView(),
            'periodicArr' => $periodicTableArray[0],
            'multipleDatesArr' => $periodicTableArray[1],
        );
    }

    /**
     * @Annot\Route("/event/create")
     * @Annot\Template
     *
     * @param Request $request A Request instance.
     *
     * @return array|Response
     */
    public function eventCreateAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('FrontendBundle\Form\EventType', $event);
        $form->remove('organize');
        $form->remove('recommend');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AddressResolverInterface $addressResolver */
            $addressResolver = $this->get('Component\Locator\AddressResolverInterface');
            $event->resolveAddress($addressResolver);
            if ($event->getPrice() == false) {
                $event->setPrice(null);
            }
            $event->setOrganize($this->getUser()->getOrganize());
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('frontend_event_eventimg', array('id' => $event->getId()));
        }
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Annot\Route("/event/img/delete/{id}")
     *
     * @param string|integer $id A image id.
     *
     * @return Response
     */
    public function eventImgDeleteAction($id)
    {
        /** @var ImageEvent $img */
        $img = $this->getDoctrine()
            ->getRepository('FrontendBundle:ImageEvent')
            ->find($id);
        $organizer = $img->getEvent()->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();

        $path = $this->getParameter('event_image_directory');
        @unlink($path . DIRECTORY_SEPARATOR . $img->getImg());
        $event_id = $img->getEvent()->getId();

        $em->remove($img);
        $em->flush();

        return $this->redirectToRoute('frontend_event_eventimg', array('id' => $event_id));
    }

    /**
     * @Annot\Route("/event/img/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param string|integer $id      A event id.
     * @param Request        $request A Request instance.
     *
     * @return array
     */
    public function eventImgAction($id, Request $request)
    {
        $event = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->find($id);

        $organizer = $event->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }

        $img = new ImageEvent();
        $form = $this->createForm('FrontendBundle\Form\ImageEventType', $img);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $img->getImg();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('event_image_directory'),
                $fileName
            );
            $img->setImg($fileName);
            $img->setEvent($event);
            $em->persist($img);
            $em->flush();
        }

        return array(
            'event' => $event,
            'form' => $form->createView(),
        );
    }

    /**
     * @Annot\Route("api/img/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param string|integer $id      A event id.
     * @param Request        $request A Request instance.
     *
     * @return mixed
     */
    public function apiEventImgAction($id, Request $request)
    {
        $event = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->find($id);

        $organizer = $event->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }
        $arrFile = $request->files->get('file');

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        foreach ($arrFile as $file) {
            $img = new ImageEvent();
            $em = $this->getDoctrine()->getManager();


            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('event_image_directory'),
                $fileName
            );

            $img->setImg($fileName);
            $img->setEvent($event);
            $em->persist($img);
            $em->flush();
        }
        return new Response('success');
    }

    /**
     * @Annot\Route("api/imgLink/check")
     * @Annot\Template
     *
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function apiImgLinkCheckAction(Request $request)
    {
        $link = $request->request->get('picLink');
        if ($this->checkLink($link) === true) {
            return new JsonResponse(array('status' => 'success'));
        } else {
            return new JsonResponse(array(
                'message' => 'This value is not a valid URL.',
                'status' => 'error',
            ));
        }
    }

    /**
     * @Annot\Route("api/imgLink/check")
     * @Annot\Template
     *
     * @param string $link A url link.
     *
     * @return mixed
     */
    private function checkLink(string $link)
    {
        if ($link === '') {
            return false;
        }
        $arr = array("image/jpeg", "image/png", "image/jpg");
        $content = get_headers($link, 1);
        return in_array($content['Content-Type'], $arr);
    }


    /**
     * @Annot\Route("api/imgLink/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param string|integer $id      A event id.
     * @param Request        $request A Request instance.
     *
     * @return mixed
     */
    public function apiEventImgLinkAction($id, Request $request)
    {
        $event = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->find($id);

        $organizer = $event->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }

        $links = $request->request->get('picLink');

        if ($links === '') {
            return new JsonResponse(array(
                'message' => 'This value is not a valid URL.',
                'status' => 'error',
            ));
        }
        $em = $this->getDoctrine()->getManager();

        foreach ($links as $link) {
            $file = file_get_contents($link);
            $fileName = md5(uniqid()) . '.jpg';
            file_put_contents($this->getParameter('event_image_directory') . '/' . $fileName, $file);
            $img = new ImageEvent();
            $img->setImg($fileName);
            $img->setEvent($event);
            $em->persist($img);
        }
        $em->flush();

        return new Response('success');
    }


    /**
     * @Annot\Route("/event/delete/{id}", requirements={ "id": "\d+" })
     *
     * @param string | int $id A image id.
     *
     * @return Response
     */
    public function eventDeleteAction($id)
    {
        $event = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->find($id);

        $organizer = $event->getOrganize();
        if ($this->getUser()->getOrganize() !== $organizer) {
            throw $this->createNotFoundException();
        }
        if (count($organizer->getEvent()->toArray()) == 1) {
            return $this->redirectToRoute('frontend_user_user');
        }
        $em = $this->getDoctrine()->getManager();

        /** @var ImageEvent $img */
        foreach ($event->getImageEvent() as $img) {
            $path = $this->getParameter('event_image_directory');
            @unlink($path . DIRECTORY_SEPARATOR . $img->getImg());
        }
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('frontend_user_user');
    }

    /**
     * @Annot\Route("/api/event/favorite", methods={ "GET", "POST" })
     *
     * @param Request $request A HTTP Request instance.
     *
     * @return mixed
     */
    public function apiEventFavorite(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user === null) {
            return JsonResponse::create([
                'redirect' => $this->generateUrl(
                    'frontend_user_sociallogin',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]);
        }
        /** @var Event $event */
        $event = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->find($request->request->get('id'));
        if ($request->request->get('status')) {
            $user->setFavoriteEvent($event);
        } else {
            $user->removeFavoriteEvent($event);
        }
        $this->get('fos_user.user_manager')->updateUser($user);
        return JsonResponse::create(['success']);
    }

    /**
     * @Annot\Route("/event/{slug}/{id}")
     * @Annot\Template
     *
     * @param string  $slug    A slug.
     * @param string  $id      A id of Event.
     * @param Request $request A $request instance.
     *
     * @return array
     */
    public function eventShowAction(string $slug, string $id, Request $request)
    {
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->getDoctrine()->getRepository('FrontendBundle:Event');
        /** @var Event $event */
        $event = $eventRepository->find($id);

        $messageSent = false;
        $contactMessage = new ContactMessage();
        $contactForm = $this->createForm('FrontendBundle\Form\ContactMessageForm', $contactMessage);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            /** @var Mailer $mailer */
            $mailer = $this->get('app.mailer');
            if ($contactMessage->getSetTo() === null) {
                $mailer->sendMessageIncorrectEvent($contactMessage, $slug, $id);
            } else {
                $mailer->sendMessageOrganizator($contactMessage);
            }
            $messageSent = true;
        }
        $dateString = $this->getDateString($event);

        return array(
            'event' => $event,
            'periodic' => $dateString,
            'favoriteEvent' => $eventRepository->getTrueIfUserLiked($this->getUser(), $event),
            'contactForm' => $contactForm->createView(),
            'messageSent' => $messageSent,
        );
    }

    /**
     * Get Request pagination
     *
     * @param Query   $query   A Query instance.
     * @param Request $request A Query instance.
     *
     * @return mixed
     */
    private function getPagination($query, Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->limit
        );

        return $pagination;
    }

    /**
     * @param AbstractPagination | array $events List events.
     *
     * @return AbstractPagination | array
     */
    protected function addCheckedUserLiked($events)
    {
        return parent::addCheckedUserLiked($events);
    }

    /**
     * Help Function For addCheckedUserLiked
     *
     * @param Event $event A Event instance.
     *
     * @return integer
     */
    protected function getEventId(Event $event)
    {
        return parent::getEventId($event);
    }
}
