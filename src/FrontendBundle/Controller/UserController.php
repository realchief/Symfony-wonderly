<?php

namespace FrontendBundle\Controller;

use AppBundle\Mailer\Mailer;
use Component\Locator\AddressResolverInterface;
use FOS\UserBundle\Model\UserManager;
use FrontendBundle\Entity\ContactMessage;
use FrontendBundle\Entity\MediumOrgEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\Child;
use UserBundle\Entity\Father;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Organize;
use UserBundle\Entity\Social;
use UserBundle\Entity\User;

/**
 * Class UserController
 * @package FrontendBundle\Controller
 *
 * @Annot\Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Annot\Route("/create/parent")
     *
     * @param Request $request A Request instance.
     *
     * @return Response
     */
    public function createParentAction(Request $request)
    {
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        if ($user->getFather() !== null) {
            return $this->redirectToRoute('frontend_default_index');
        }
        $father = new Father();
        $father->addChild(new Child());
        $fatherForm = $this->createForm('FrontendBundle\Form\FatherType', $father);
        $fatherForm->handleRequest($request);
        if ($fatherForm->isSubmitted() && $fatherForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $father->getImg();
            //$file = $form->get('organize')->get('img')->getData();
            /** @var AddressResolverInterface $addressResolver */
            $addressResolver = $this->get('Component\Locator\AddressResolverInterface');
            $father->resolveAddress($addressResolver);

            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
                $father->setImg($fileName);
            }
            $user->setFather($father);
            $user->setRoles(array('ROLE_FATHER'));
            try {
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('frontend_default_index');
            } catch (\Exception $e) {
                die();
            }
        }

        return $this->render('@Frontend/User/createParent.html.twig', array(
            'fatherForm' => $fatherForm->createView(),
        ));
    }

    /**
     * @Annot\Route("/create/organizer")
     *
     * @return Response
     */
    public function createOrganizerAction()
    {
        /** @var \UserBundle\Entity\User $user */
        $user = $this->getUser();
        if ($user->getOrganize() !== null) {
            return $this->redirectToRoute('frontend_default_index');
        }

        $mediumOrgEvent = new MediumOrgEvent();

        $flow = $this->get('app.form.flow.createVehicle');
        $flow->bind($mediumOrgEvent);
        // form of the current step
        $eventForm = $flow->createForm();
        if ($flow->isValid($eventForm)) {
            $flow->saveCurrentStepData($eventForm);
            if ($flow->nextStep()) {
                $eventForm = $flow->createForm();
            } else {
                /** @var @var \UserBundle\Entity\Organize $organizer */
                $organizer = $mediumOrgEvent->createOrganizer();
                $organizer->setUser($user);
                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $organizer->getImg();
                //$file = $form->get('organize')->get('img')->getData();
                if ($file !== null) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move(
                        $this->getParameter('event_image_directory'),
                        $fileName
                    );
                    $organizer->setImg($fileName);
                }
                /** @var \FrontendBundle\Entity\Event $event */
                $event = $mediumOrgEvent->createEvent();
                /** @var AddressResolverInterface $addressResolver */
                $addressResolver = $this->get('Component\Locator\AddressResolverInterface');
                $event->resolveAddress($addressResolver);
                if ($event->getPrice() == false) {
                    $event->setPrice(null);
                }
                $event->setOrganize($organizer);
                $em = $this->getDoctrine()->getManager();
                $em->persist($organizer);
                $em->persist($event);
                $em->flush();
                $flow->reset();
                return $this->redirectToRoute('frontend_event_eventimg', array('id' => $event->getId())); // redirect when done
            }
        }
        return $this->render('@Frontend/User/createOrganizer.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'flow' => $flow,
        ));
    }

    /**
     * @Annot\Route("/social/login")
     *
     * @return Response
     */
    public function socialLoginAction()
    {
        return $this->render('@Frontend/User/socialLogin.html.twig');
    }

    /**
     * @Annot\Route("/api/social")
     *
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function apiSocialAction(Request $request)
    {
        $profile = $request->request->get('profile');
        $social = $this->getDoctrine()
            ->getRepository('UserBundle:Social')
            ->findOneBy(array('socialName' => $profile['social'], 'socialId' => $profile['id']));
        if ($social !== null) {
            $user = $social->getUser();
        } else {
            $rand = substr(uniqid('', true), -10);
            /** @var UserManager $userManager */
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $this->getDoctrine()
                ->getRepository('UserBundle:User')
                ->findOneBy(array('email' => $profile['email']));
            if ($user === null) {
                /** @var User $user */
                $user = $userManager->createUser();
                $user
                    ->setUsername($profile['email'])
                    ->setEmail($profile['email'])
                    ->setFirstname($profile['firstname'])
                    ->setLastname($profile['lastname'])
                    ->setPlainPassword($rand)
                    ->setEnabled(true)
                    ->setRoles(array('ROLE_USER'));
            }
            $social = new Social();
            $social->setSocialId($profile['id']);
            $social->setSocialName($profile['social']);
            $user->setSocial($social);
            $userManager->updateUser($user);
        }
        ///login automatic user///
        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get("security.token_storage")->setToken($token);

        if ($this->getUser() instanceof UserInterface) {
            return new Response('success');
        }

        return new Response('error');
    }

    /**
     * @Annot\Route("/organizer/{id}")
     *
     * @param integer $id      A organizer id.
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function organizerAction(int $id, Request $request)
    {
        $organizer = $this->getDoctrine()
            ->getRepository('UserBundle:Organize')
            ->findOneBy(array('id' => $id));
        if ($organizer === null) {
            return new Response('This page does not exist');
        }
        /** @var User $user */
        /** @var Organize $organizer */
        $user = $organizer->getUser();
        $messageSent = false;
        $contactMessage = new ContactMessage();
        $contactForm = $this->createForm('FrontendBundle\Form\ContactMessageForm', $contactMessage);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            /** @var Mailer $mailer */
            $mailer = $this->get('app.mailer');
            $mailer->sendMessageOrganizator($contactMessage);
            $messageSent = true;
        }
        $categories = $this->getDoctrine()
            ->getRepository('FrontendBundle:Category')
            ->getSpecificTag($organizer->getId());
        $events = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->findBy(['organize' => $organizer], ['id' => 'DESC'], 100);
        return $this->render('@Frontend/User/organizer.html.twig', [
            'organizer' => $organizer,
            'user' => $user,
            'categories' => $categories,
            'contactForm' => $contactForm->createView(),
            'events' => $events,
            'messageSent' => $messageSent,
            ]);
    }

    /**
     * @Annot\Route("/my-account")
     *
     *
     * @return mixed
     */
    public function userAction()
    {
        $user['user'] = $this->getUser();
        if ($user['user']->getOrganize() !== null) {
            $user['organizer'] = $user['user']->getOrganize();
        }
        if ($user['user']->getFather() !== null) {
            $user['father'] = $user['user']->getFather();
        }
        return $this->render('@Frontend/User/user.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @Annot\Route("/my-account/edit/user")
     *
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function userEditAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm('FrontendBundle\Form\UserEditType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \FOS\UserBundle\Doctrine\UserManager $fosManager */
            $fosManager = $this->get('fos_user.user_manager');
            $fosManager->updateUser($user);
            return $this->redirectToRoute('frontend_user_user');
        }
        return $this->render('@Frontend/User/editUser.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @Annot\Route("/my-account/edit/organizer")
     *
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function organizerEditAction(Request $request)
    {
        /** @var Organize $organizer */
        $organizer = $this->getUser()->getOrganize();
        $fileName = $organizer->getImg();
        $organizerForm = $this->createForm('AdminBundle\Form\OrganizeType', $organizer);
        $organizerForm->handleRequest($request);
        if ($organizerForm->isSubmitted() && $organizerForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $organizer->getImg();
            if ($file !== null) {
                $path = $this->getParameter('event_image_directory');
                @unlink($path . DIRECTORY_SEPARATOR . $fileName);
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
            }
            $organizer->setImg($fileName);
            try {
                $em->persist($organizer);
                $em->flush();
                return $this->redirectToRoute('frontend_user_user');
            } catch (\Exception $e) {
                die();
            }
        }
        return $this->render('@Frontend/User/editOrganizer.html.twig', array(
            'organizerForm' => $organizerForm->createView(),
            'organizer' => $organizer,
        ));
    }

    /**
     * @Annot\Route("/my-account/edit/parent")
     *
     * @param Request $request A Request instance.
     *
     * @return mixed
     */
    public function parentEditAction(Request $request)
    {
        /** @var Father $father */
        $father = $this->getUser()->getFather();
        $fileName = $father->getImg();

        $fatherForm = $this->createForm('FrontendBundle\Form\FatherType', $father);

        $fatherForm->handleRequest($request);
        if ($fatherForm->isSubmitted() && $fatherForm->isValid()) {
            $kids = $father->getChild()->toArray();
            $arr = [];
            foreach ($kids as $child) {
                /** @var Child $child */
                $arr[] = $child->getId();
            }
            $kidsForRemove = $this->getDoctrine()
                ->getRepository('UserBundle:Child')
                ->kidsForRemove($arr, $father->getId());
            $em = $this->getDoctrine()->getManager();
            if (!empty($kidsForRemove)) {
                foreach ($kidsForRemove as $child) {
                    $em->remove($child);
                }
            }
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $father->getImg();
            /** @var AddressResolverInterface $addressResolver */
            $addressResolver = $this->get('Component\Locator\AddressResolverInterface');
            $father->resolveAddress($addressResolver);

            if ($file !== null) {
                $path = $this->getParameter('event_image_directory');
                @unlink($path . DIRECTORY_SEPARATOR . $fileName);
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
            }
            $father->setImg($fileName);
            try {
                $em->persist($father);
                $em->flush();
                return $this->redirectToRoute('frontend_user_user');
            } catch (\Exception $e) {
                die();
            }
        }
        return $this->render('@Frontend/User/editParent.html.twig', array(
            'fatherForm' => $fatherForm->createView(),
            'father' => $father,
        ));
    }
}
