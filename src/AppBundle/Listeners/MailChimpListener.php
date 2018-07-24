<?php

namespace AppBundle\Listeners;

use AppBundle\MailChimp\MailChimp;
use Doctrine\ORM\Event\LifecycleEventArgs;
use UserBundle\Entity\Father;
use UserBundle\Entity\Organize;

/**
 * Class MailChimpListener
 * @package AppBundle\Listeners
 */
class MailChimpListener
{
    /** @var  MailChimp */
    private $mailChimp;

    /**
     * MailChimpListener constructor.
     * @param MailChimp $mailChimp
     */
    public function __construct(MailChimp $mailChimp)
    {
        $this->mailChimp = $mailChimp;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Father) {
            if (!$entity instanceof Organize) {
                return;
            }
        }
        $this->mailChimp->newUser($entity->getUser(), $entity);
    }
}
