<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Annot;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AdminBundle\Controller
 */
class DefaultController extends Controller
{

    /**
     * @Annot\Route("/")
     * @Annot\Template
     *
     * @return array
     */
    public function dashboardAction()
    {
        return [];
    }
}
