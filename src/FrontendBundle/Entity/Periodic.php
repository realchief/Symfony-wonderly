<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periodic
 *
 * @ORM\Table(name="periodic")
 * @ORM\Entity
 */
class Periodic
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Event.
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="periodic")
     */
    private $event;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $day;

    /**
     * Periodic constructor.
     *
     * @param string $day A day.
     */
    public function __construct($day)
    {
        $this->day = $day;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Event
     *
     * @param Event $event A Event.
     *
     * @return Periodic
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get Event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get periodic
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }
}
