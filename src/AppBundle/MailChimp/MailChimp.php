<?php

namespace AppBundle\MailChimp;

use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\Father;
use UserBundle\Entity\Organize;
use UserBundle\Entity\User;
use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;

/**
 * Class MailChimp
 *
 * @package AppBundle\MailChimp
 */
class MailChimp
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * MailChimp constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param User            $user
     * @param Father|Organize $entity
     * @return void
     */
    public function newUser(User $user, $entity)
    {
        $email = $user->getEmail();
        $mergeFields = [
            'FNAME' => $user->getFirstname(),
            'LNAME' => $user->getLastname(),
        ];
        $mergeFields['ADDRESS'] = [
            'addr1' => $entity->getAddress(),
            'addr2' => '-',
            'city' => $entity->getLocation(),
            'state' => '-',
            'zip' => '-',
            'country' => '-',
        ];
        $subscriber = new Subscriber(
            $email,
            $mergeFields
        );

        try {
            $this->container->get('event_dispatcher')->dispatch(
                SubscriberEvent::EVENT_SUBSCRIBE,
                new SubscriberEvent($this->container->getParameter('mailchimp_api_list'), $subscriber)
            );
        } catch (\Exception $exception) {
            new \LogicException('MailChimp return' . $exception->getMessage());
        }
    }
}
