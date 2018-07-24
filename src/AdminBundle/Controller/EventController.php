<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\EventsImportType;
use Component\Locator\AddressResolverException;
use Component\Locator\FailedResolveRequestException;
use Component\Locator\UnknownAddressException;
use Component\Locator\AddressResolverInterface;
use Component\TableProcessor\TableProcessorInterface;
use FrontendBundle\Controller\AbstractController;
use FrontendBundle\Entity\Age;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Event;
use FrontendBundle\Entity\ImageEvent;
use FrontendBundle\Entity\Periodic;
use FrontendBundle\Form\EventType;
use FrontendBundle\Form\ImageEventType;
use FrontendBundle\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Component\EventDispatcher\DependencyInjection\ExtractingEventDispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Organize;

/**
 * Class EventController
 * @package AdminBundle\Controller
 *
 * @Annot\Route("/event")
 */
class EventController extends AbstractController
{

    /**
     * @Annot\Route("/create")
     * @Annot\Template
     *
     * @param Request $request A Request instance.
     *
     * @return array|Response
     */
    public function eventCreateAction(Request $request)
    {
        $organizers = $this->getDoctrine()->getRepository('UserBundle:Organize')->findAll();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AddressResolverInterface $addressResolver */
            $addressResolver = $this->get(AddressResolverInterface::class);
            $event->resolveAddress($addressResolver);
            if ($event->getPrice() == false) {
                $event->setPrice(null);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('admin_event_eventimg', array('id' => $event->getId()));
        }
        return [
            'form' => $form->createView(),
            'organizers' => $organizers,
        ];
    }

    /**
     * @Annot\Route("/create/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param integer $id      Organizer Id.
     * @param Request $request A Request instance.
     *
     * @return array|Response
     */
    public function eventCreateUserAction(int $id, Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $organizer = $this->getDoctrine()
                ->getRepository('UserBundle:Organize')
                ->find($id);
            $event->setOrganize($organizer);
            if ($event->getPrice() == false) {
                $event->setPrice(null);
            }
            try {
                /** @var AddressResolverInterface $addressResolver */
                $addressResolver = $this->get(AddressResolverInterface::class);
                $event->resolveAddress($addressResolver);
            } catch (UnknownAddressException $e) {
                return [
                    'form' => $form->createView(),
                    'errorAddress' => $e->getMessage(),
                ];
            } catch (FailedResolveRequestException $e) {
                return [
                    'form' => $form->createView(),
                    'errorAddress' => $e->getMessage(),
                ];
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('admin_event_eventimg', array('id' => $event->getId()));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Annot\Route("/list")
     * @Annot\Template
     *
     * @param Request $request A HTTP Request instance.
     *
     * @return mixed
     */
    public function eventListAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var TableProcessorInterface $tableProcessor */
            $tableProcessor = $this->get(TableProcessorInterface::class);
            return $tableProcessor->processTableRequest($request, Event::class);
        }
        return [];
    }

    /**
     * @Annot\Route("/import", name="admin_event_import")
     */
    public function adminEventImport(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EventsImportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile*/
            $file = $form->get('file')->getData();

            $file_path = $file->getPathName();

            $handle = fopen($file_path,'r');

            $column_count = 23;
            $index = 1;
            $imported = 0;
            while (($data = fgetcsv($handle) ) !== FALSE ) {
                try {
                    if ($index == 1) {
                        if (count($data) != $column_count) {
                            $this->addFlash('danger', 'Invalid File');

                            return $this->redirectToRoute('admin_event_import');
                        }
                    } else {

                        $organizerEmail  = $data[0];
                        $name            = $data[1];
                        $type            = $data[2];
                        $location        = $data[3];
                        $zipCode         = $data[4];
                        $email           = $data[5];
                        $website         = $data[6];
                        $telephone       = $data[7];
                        $indoorOutdoor   = $data[8];
                        $description     = $data[9];
                        $price           = $data[10];
                        $ages            = $data[11];
                        $operationsStart = $data[12];
                        $operationsEnd   = $data[13];
                        $eventStart      = $data[14];
                        $eventEnd        = $data[15];
                        $foodAvailable   = $data[16];
                        $helpfulTips     = $data[17];
                        $recommended     = $data[18];
                        $eventDateStart  = $data[19];
                        $eventDateEnd    = $data[20];
                        $multipleDates   = $data[21];
                        $periodicDates   = $data[22];


                        $organizer = $em->getRepository(Organize::class)->findByEmail($organizerEmail);

                        if ( ! $organizer) {
                            $index++;
                            continue;
                        }

                        $event = new Event();
                        $event->setName($name);

                        $event->setOrganize($organizer);
                        $event->setAddress($location);

                        /** @var AddressResolverInterface $addressResolver */
                        $addressResolver = $this->get(AddressResolverInterface::class);
                        $event->resolveAddress($addressResolver);


                        $event->setZip($zipCode);
                        $event->setEmail($email);
                        $event->setSite($website);
                        $event->setPhonenumber($telephone);
                        $event->setOutdoor($indoorOutdoor);
                        $event->setDescription($description);
                        $event->setPrice($price);

                        if ($type != '') {
                            $typesArray = explode('|', $type);
                            foreach ($typesArray as $ta) {
                                $category = $em->getRepository(Category::class)->findOneBy(['tag' => trim($ta)]);
                                $event->addCategory($category);
                            }
                        }


                        if ($ages != '') {
                            $agesArray = explode('|', $ages);
                            foreach ($agesArray as $aa) {
                                $age = new Age($aa);
                                $age->setEvent($event);
                                $em->persist($age);
                            }
                        }

                        if ($multipleDates != '') {
                            $multipleDatesArray = explode('|', $multipleDates);
                            foreach ($multipleDatesArray as $mda) {
                                $periodic = new Periodic($mda);
                                $periodic->setEvent($event);
                                $em->persist($periodic);
                            }
                        }

                        if ($periodicDates != '') {
                            $multipleDatesArray = explode('|', $periodicDates);
                            foreach ($multipleDatesArray as $mda) {
                                $periodic = new Periodic($mda);
                                $periodic->setEvent($event);
                                $em->persist($periodic);
                            }
                        }

                        $event->setOriginWork(\DateTime::createFromFormat('g:i A', $operationsStart));
                        $event->setFinishWork(\DateTime::createFromFormat('g:i A', $operationsEnd));

                        $event->setOrigin(\DateTime::createFromFormat('g:i A', $eventStart));
                        $event->setFinish(\DateTime::createFromFormat('g:i A', $eventEnd));


                        $event->setFood($foodAvailable ? true : false);
                        $event->setTips($helpfulTips);
                        $event->setRecommend($recommended);

                        if ($eventDateStart && $eventDateEnd) {

                            $event->setEventDate(\DateTime::createFromFormat('Y-m-d', $eventDateStart));
                            $event->setEventDateEnd(\DateTime::createFromFormat('Y-m-d', $eventDateEnd));
                        }

                        $em->persist($event);
                        $em->flush();
                        $imported++;
                    }

                    $index++;
                }catch (\Exception $e) {
                    $this->addFlash('danger',$e->getMessage());
                    $index++;
                }
            }
            $this->addFlash('success','Events Imported: '.$imported);
            return $this->redirectToRoute('admin_event_import');
        }


        return $this->render('@Admin/Event/eventImport.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Annot\Route("/list/{id}", requirements={ "id": "\d+" })
     *
     * @param integer $id Organizer Id.
     *
     * @return Response
     */
    public function eventListUserAction(int $id)
    {
        $organizer = $this->getDoctrine()
            ->getRepository('UserBundle:Organize')
            ->find($id);
        if (! $organizer instanceof Organize) {
            throw $this->createNotFoundException();
        }
        $events = $organizer->getEvent()->toArray();
        return $this->render('AdminBundle:Event:eventListUser.html.twig', ['events' => $events]);
    }

    /**
     * @Annot\Route("/img/delete/{id}")
     *
     * @param string|integer $id A image id.
     *
     * @return Response
     */
    public function eventImgDeleteAction($id)
    {
        $img = $this->getDoctrine()
            ->getRepository('FrontendBundle:ImageEvent')
            ->find($id);
        if (! $img instanceof ImageEvent) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $path = $this->getParameter('event_image_directory');
        @unlink($path . DIRECTORY_SEPARATOR . $img->getImg());
        $event_id = $img->getEvent()->getId();
        $em->remove($img);
        $em->flush();
        return $this->redirectToRoute('admin_event_eventimg', ['id' => $event_id]);
    }

    /**
     * @Annot\Route("/img/{id}", requirements={ "id": "\d+" })
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

        $img = new ImageEvent();
        $form = $this->createForm(ImageEventType::class, $img);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($img->getUrl() !== null) {
                if ($this->checkLink($img->getUrl()) !== true) {
                    return [
                        'event' => $event,
                        'form' => $form->createView(),
                        'linkError' => 'This value is not a valid URL.',
                    ];
                }
                $file = file_get_contents($img->getUrl());
                $fileName = md5(uniqid()) . '.jpg';
                file_put_contents($this->getParameter('event_image_directory') . '/' . $fileName, $file);
                $img->setUrl(null);
            } else {
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $img->getImg();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
            }
            $img->setImg($fileName);
            $img->setEvent($event);
            $em->persist($img);
            $em->flush();
         }
        return [
            'event' => $event,
            'form' => $form->createView(),
        ];
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
        $arr = [ "image/jpeg", "image/png", "image/jpg"];
        $content = get_headers($link, 1);
        return in_array($content['Content-Type'], $arr);
    }

    /**
     * @Annot\Route("/delete/{id}", requirements={ "id": "\d+" })
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
        if (! $event instanceof Event) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var ImageEvent $img */
        foreach ($event->getImageEvent() as $img) {
            $path = $this->getParameter('event_image_directory');
            @unlink($path . DIRECTORY_SEPARATOR . $img->getImg());
        }
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('admin_event_eventlist');
    }

    /**
     * @Annot\Route("/edit/{id}", requirements={ "id": "\d+" })
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
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $event = $repository->get($id);
        if (! $event instanceof Event) {
            throw $this->createNotFoundException();
        }
        $ages = clone $event->getAge();
        $address = $event->getAddress();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $periodicTableArray = $this->getPeriodicArray($event);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($event->getPrice() == false) {
                $event->setPrice(null);
            }
            $em = $this->getDoctrine()->getManager();
            try {
                /** @var AddressResolverInterface $addressResolver */
                $addressResolver = $this->get(AddressResolverInterface::class);
                $event->resolveAddress($addressResolver);
            } catch (AddressResolverException $exception) {
                return [
                    'event' => $event,
                    'form' => $form->createView(),
                    'errorAddress' => $exception->getMessage(),
                    'periodicArr' => $periodicTableArray[0],
                    'multipleDatesArr' => $periodicTableArray[1],
                ];
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
        return [
            'event' => $event,
            'form' => $form->createView(),
            'periodicArr' => $periodicTableArray[0],
            'multipleDatesArr' => $periodicTableArray[1],
        ];
    }
}
