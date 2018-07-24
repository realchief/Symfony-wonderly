<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Age
 *
 * @ORM\Table(name="age")
 * @ORM\Entity
 */
class Age
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
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="age")
     */
    private $event;

    /**
     * Content.
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * Age constructor.
     *
     * @param integer $year A year.
     */
    public function __construct($year)
    {
        $this->year = $year;
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
     * @return Age
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
     * Get comment
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }
}
