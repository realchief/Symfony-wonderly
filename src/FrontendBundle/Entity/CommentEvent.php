<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Father;
use FrontendBundle\Entity\Event;

/**
 * CommentEvent
 *
 * @ORM\Table(name="commentEvent")
 * @ORM\Entity
 */
class CommentEvent
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
     * Organize.
     *
     * @var Father
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Father", inversedBy="commentEvent")
     */
    private $father;

    /**
     * Event.
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="commentEvent")
     */
    private $event;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $content;

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
     * Set parent
     *
     * @param Father $father
     *
     * @return CommentEvent
     */
    public function setFather(Father $father = null)
    {
        $this->father = $father;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Father
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * Set Event
     *
     * @param Event $event A Event.
     *
     * @return CommentEvent
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
     * Set comment
     *
     * @param string $content A comment content.
     *
     * @return CommentEvent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
