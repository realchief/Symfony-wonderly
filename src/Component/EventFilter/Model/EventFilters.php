<?php

namespace Component\EventFilter\Model;

use CrEOF\Spatial\PHP\Types\Geometry\Point;

use function \app\a\validateSequenceIsInstanceOf;

/**
 * Class EventFilters
 *
 * @package Component\EventFilter\Model
 */
class EventFilters implements \Serializable
{

    /**
     * Show only free events.
     *
     * @var boolean
     */
    private $onlyFree = false;

    /**
     * Remember filters.
     *
     * @var boolean
     */
    private $remember = false;

    /**
     * Requested ages.
     *
     * @var ComparableValue[]
     */
    private $ages = [];

    /**
     * User address which we use for distance filter.
     *
     * @var string
     */
    private $address = '';

    /**
     * Search distance in miles.
     *
     * @var ComparableValue[]
     */
    private $distances = [];

    /**
     * Destination point from which we should compute distances.
     *
     * @var Point|null
     */
    private $destination;

    /**
     * Search hour intervals.
     *
     * @var HourInterval[]
     */
    private $hours = [];

    /**
     * Search dateEvent interval.
     *
     * @var \DateTime
     */
    private $dateEvent = null;

    /**
     * Array of required categories ids.
     *
     * @var integer[]
     */
    private $categories = [];

    /**
     * @return boolean
     */
    public function isOnlyFree()
    {
        return $this->onlyFree;
    }

    /**
     * @param boolean $onlyFree Show only free events or not.
     *
     * @return EventFilters
     */
    public function setOnlyFree(bool $onlyFree = true)
    {
        $this->onlyFree = $onlyFree;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRemember()
    {
        return $this->remember;
    }

    /**
     * @param boolean $remember Should remember this filters or not.
     *
     * @return EventFilters
     */
    public function setRemember(bool $remember)
    {
        $this->remember = $remember;

        return $this;
    }

    /**
     * @return ComparableValue[]
     */
    public function getAges()
    {
        return $this->ages;
    }

    /**
     * @param ComparableValue[] $ages Required children ages.
     *
     * @return EventFilters
     */
    public function setAges(array $ages)
    {
        validateSequenceIsInstanceOf($ages, ComparableValue::class);
        $this->ages = $ages;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address A user address.
     *
     * @return EventFilters
     */
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return ComparableValue[]
     */
    public function getDistances()
    {
        return $this->distances;
    }

    /**
     * @param ComparableValue[] $distances Array of search distances.
     *
     * @return EventFilters
     */
    public function setDistances(array $distances)
    {
        validateSequenceIsInstanceOf($distances, ComparableValue::class);
        $this->distances = $distances;

        return $this;
    }

    /**
     * @return Point|null
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param array $destination
     *
     * @return EventFilters
     */
    public function setDestination(array $destination)
    {

        $this->destination = $destination[0];

        return $this;
    }

    /**
     * @return HourInterval[]
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param HourInterval[] $hours Array of search hours interval.
     *
     * @return EventFilters
     */
    public function setHours(array $hours)
    {
        validateSequenceIsInstanceOf($hours, HourInterval::class);
        $this->hours = $hours;

        return $this;
    }

    /**
     * @return integer[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param integer[] $categories Array of requested event categories.
     *
     * @return EventFilters
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $dateEvent
     *
     * @return EventFilters
     */
    public function setDateEvent(\DateTime $dateEvent)
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getDateEvent()
    {
        return $this->dateEvent;
    }

    /**
     * String representation of object.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->onlyFree,
            $this->remember,
            $this->ages,
            $this->address,
            $this->distances,
            $this->destination,
            $this->hours,
            $this->categories,
            $this->dateEvent,
        ]);
    }

    /**
     * Constructs the object.
     *
     * @param string $serialized The string representation of the object.
     *
     * @return void
     */
    // @codingStandardsIgnoreStart
    public function unserialize($serialized)
    {
        // @codingStandardsIgnoreEnd
        list (
            $this->onlyFree,
            $this->remember,
            $this->ages,
            $this->address,
            $this->distances,
            $this->destination,
            $this->hours,
            $this->categories,
            $this->dateEvent,
        ) = unserialize($serialized);
    }
}
