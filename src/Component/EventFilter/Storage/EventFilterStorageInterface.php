<?php

namespace Component\EventFilter\Storage;

use Component\EventFilter\Model\EventFilters;
use UserBundle\Entity\User;

/**
 * Interface EventFilterStorageInterface
 *
 * @package Component\EventFilter\Storage
 */
interface EventFilterStorageInterface
{

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
    public function saveEventFilter(User $user, EventFilters $eventFilters);

    /**
     * Get event filters for specified user.
     *
     * @param User $user Event filters owner.
     *
     * @return EventFilters If filters not found return defaults.
     */
    public function getEventFilters(User $user);
}
