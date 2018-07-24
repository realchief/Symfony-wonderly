<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\UserType;
use Component\TableProcessor\TableProcessorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\UserBundle\Model\UserManager;
use FrontendBundle\Entity\Event;
use FrontendBundle\Entity\ImageEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Organize;
use UserBundle\Entity\User;

/**
 * Class UserController
 * @package AdminBundle\Controller
 *
 * @Annot\Route("/user")
 */
class UserController extends Controller
{

    /**
     * @Annot\Route("/register")
     * @Annot\Template
     *
     * @param Request $request A request instance.
     *
     * @return array|Response
     */
    public function userRegisterAction(Request $request)
    {
        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var User $user */
        $user = $userManager->createUser();

        $form = $this->createForm(UserType::class, $user, [
            'validation_groups' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled(true);

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getOrganize()->getImg();
            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
                $user->getOrganize()->setImg($fileName);
            }
            $user->getOrganize()->setUser($user);
            $user->setRoles(['ROLE_ORGANIZER']);

            try {
                $userManager->updateUser($user, true);
            } catch (UniqueConstraintViolationException $e) {
                return [
                    'form' => $form->createView(),
                    'errorEmail' => 'This email already exist',
                ];
            }

            return $this->redirectToRoute('admin_event_eventcreateuser', array('id' => $user->getOrganize()->getId()));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Annot\Route("/organizers/list")
     * @Annot\Template
     *
     *@param Request $request A HTTP Request instance.

     * @return mixed
     */
    public function userOrganizersListAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var TableProcessorInterface $tableProcessor */
            $tableProcessor = $this->get(TableProcessorInterface::class);

            return $tableProcessor->processTableRequest($request, Organize::class);
        }
        return [];
    }

    /**
     * @Annot\Route("/user/list")
     * @Annot\Template
     *
     *@param Request $request A HTTP Request instance.

     * @return mixed
     */
    public function userUserListAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            /** @var TableProcessorInterface $tableProcessor */
            $tableProcessor = $this->get(TableProcessorInterface::class);
            return $tableProcessor->processTableRequest($request, User::class);
        }
        return [];
    }


    /**
     * @Annot\Route("/organizers/edit/{id}", requirements={ "id": "\d+" })
     * @Annot\Template
     *
     * @param string  $id      A organiser id.
     * @param Request $request A request instance.
     *
     * @return array
     */
    public function userOrganizersEditAction(string $id, Request $request)
    {
        $organizer = $this->getDoctrine()->getRepository('UserBundle:Organize')->find($id);

        if (! $organizer instanceof Organize) {
            throw $this->createNotFoundException();
        }

        $fileName = $organizer->getImg();

        $user = $organizer->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getOrganize()->getImg();
            if ($file !== null) {
                $path = $this->getParameter('event_image_directory');
                @unlink($path . DIRECTORY_SEPARATOR . $fileName);
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('event_image_directory'),
                    $fileName
                );
            }
            $user->getOrganize()->setImg($fileName);
            $em->persist($user);
            $em->flush();
        }

        return [
            'organizer' => $organizer,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Annot\Route("/organizers/img/delete/{id}", requirements={ "id": "\d+" })
     *
     * @param string $id A organiser id.
     *
     * @return mixed
     */
    public function userOrganizersImgDeleteAction(string $id)
    {
        $organizer = $this->getDoctrine()->getRepository('UserBundle:Organize')->find($id);

        if (! $organizer instanceof Organize) {
            throw $this->createNotFoundException();
        }

        $fileName = $organizer->getImg();

        $organizer->setImg(null);
        $path = $this->getParameter('event_image_directory');
        @unlink($path . DIRECTORY_SEPARATOR . $fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($organizer);
        $em->flush();

        return $this->redirectToRoute('admin_user_userorganizersedit', ['id' => $organizer->getId()]);
    }

    /**
     * @Annot\Route("/organizers/delete/{id}", requirements={ "id": "\d+" })
     *
     * @param string $id A organiser id.
     *
     * @return mixed
     */
    public function userOrganizersDeleteAction(string $id)
    {
        $organizer = $this->getDoctrine()->getRepository('UserBundle:Organize')->find($id);

        if (! $organizer instanceof Organize) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $path = $this->getParameter('event_image_directory');

        if ($organizer->getImg() !== null) {
            @unlink($path . DIRECTORY_SEPARATOR . $organizer->getImg());
        }

        /** @var Event $event */
        foreach ($organizer->getEvent() as $event) {
            /** @var ImageEvent $image */
            foreach ($event->getImageEvent() as $image) {
                @unlink($path . DIRECTORY_SEPARATOR . $image->getImg());
            }
        }

        $em->remove($organizer);
        $em->flush();

        return $this->redirectToRoute('admin_user_userorganizerslist');
    }

    /**
     * @Annot\Route("/delete/{id}", requirements={ "id": "\d+" })
     *
     * @param string $id A organiser id.
     *
     * @return mixed
     */
    public function userDeleteAction(string $id)
    {
        $organizer = $this->getDoctrine()->getRepository('UserBundle:Organize')->find($id);

        if (! $organizer instanceof Organize) {
            throw $this->createNotFoundException();
        }

        $user = $organizer->getUser();
        $em = $this->getDoctrine()->getManager();
        $path = $this->getParameter('event_image_directory');

        if ($organizer->getImg() !== null) {
            @unlink($path . DIRECTORY_SEPARATOR . $organizer->getImg());
        }

        /** @var Event $event */
        foreach ($organizer->getEvent() as $event) {
            /** @var ImageEvent $image */
            foreach ($event->getImageEvent() as $image) {
                $path = $this->getParameter('event_image_directory');
                @unlink($path . DIRECTORY_SEPARATOR . $image->getImg());
            }
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_user_userorganizerslist');
    }
}
