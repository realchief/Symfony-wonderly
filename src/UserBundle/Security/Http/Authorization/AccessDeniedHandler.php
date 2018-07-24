<?php

namespace UserBundle\Security\Http\Authorization;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Class AccessDeniedHandler
 *
 * @package UserBundle\Security\Http\Authorization
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * AccessDeniedHandler constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator A UrlGeneratorInterface instance.
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Handles an access denied failure.
     *
     * @param Request               $request               A http request.
     * @param AccessDeniedException $accessDeniedException Occurred exception.
     *
     * @return RedirectResponse|null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $requiredRole = $accessDeniedException->getAttributes();

        if (count(array_intersect($requiredRole, [ 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN' ])) !== 0) {
            $session = $request->getSession();

            if ($session !== null) {
                $session->set(Security::AUTHENTICATION_ERROR, new CustomUserMessageAuthenticationException('You don\'t have required permissions'));
            }

            return new RedirectResponse($this->urlGenerator->generate('fos_user_security_login'));
        }

        return null;
    }
}
