<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegistrationController
 * @package UserBundle\Controller
 */
class RegistrationController extends BaseController
{

    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param Request $request A http request.
     * @param string  $token   Confirmation token.
     *
     * @return Response
     */
    public function confirmAction(Request $request, $token)
    {
        parent::confirmAction($request, $token);

        return $this->redirectToRoute('frontend_user_createparent');
    }
}
