<?php

namespace Component\EventFilter\Storage;

use Component\EventFilter\Model\EventFilters;
use Doctrine\ORM\EntityManagerInterface;
use UserBundle\Entity\User;

/**
 * Class ORMEventFilterStorage
 *
 * @package Component\EventFilter\Storage
 */
class ORMEventFilterStorage implements EventFilterStorageInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ORMEventFilterStorage constructor.
     *
     * @param EntityManagerInterface $em A EntityManagerInterface instance.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Store event filters for specified user but only if it requested.
     *
     * @param User         $user         For whom we save filters.
     * @param EventFilters $eventFilters Saved event filters.
     *
     * @return void
     *
     * @see EventFilters::isRemember()
     */
    public function saveEventFilter(User $user, EventFilters $eventFilters)
    {
        if ($eventFilters->isRemember()) {
            $user->setEventFilters($eventFilters);
            $this->em->persist($user);
            $this->em->flush();
        } else {
            if ($user->getEventFilters() !== null) {
                $user->setEventFilters(null);
                $this->em->persist($user);
                $this->em->flush();
            }
        }
    }

    /**
     * Get event filters for specified user.
     *
     * @param User $user Event filters owner.
     *
     * @return EventFilters If filters not found return defaults.
     */
    public function getEventFilters(User $user)
    {
        return $user->getEventFilters() ?: new EventFilters();
    }
}
