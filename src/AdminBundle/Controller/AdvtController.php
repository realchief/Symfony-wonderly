<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Advt;
use AdminBundle\Form\AdvtType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdvtController
 * @package AdminBundle\Controller
 *
 * @Annot\Route("/advt")
 */
class AdvtController extends Controller
{

    /**
     * @Annot\Route("/create")
     * @Annot\Template
     *
     * @param Request $request A Request instance.
     *
     * @return array|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advt = new Advt();
        $form = $this->createForm(AdvtType::class, $advt);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($advt);
            $em->flush();

            return $this->redirectToRoute('admin_advt_list');
        }
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Annot\Route("/list")
     * @Annot\Template
     *
     * @param Request $request A Request instance.
     *
     * @return array|Response
     */
    public function listAction(Request $request)
    {
        $query = $this->getDoctrine()
            ->getRepository('AdminBundle:Advt')
            ->getSimpleQuery();

        /** @var Paginator $paginator */
        $paginator  = $this->get('knp_paginator');
        $advts = $paginator->paginate($query, $request->query->getInt('page', 1), 200);

        return [
            'advts' => $advts,
        ];
    }

    /**
     * @Annot\Route("/delete/{id}")
     *
     * @param integer $id A Id Advt.
     *
     * @return Response
     */
    public function deleteAction(int $id)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $advt = $em->getRepository('AdminBundle:Advt')
            ->find($id);
        if ($advt !== null) {
            $em->remove($advt);
            $em->flush();
        }

        return $this->redirectToRoute('admin_advt_list');
    }
}
