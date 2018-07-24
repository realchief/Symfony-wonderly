<?php

namespace FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Father;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="FrontendBundle\Repository\CategoryRepository")
 */
class Category
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
     * Tag.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tag;

    /**
     * Event.
     *
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="category")
     */
    private $event;

    /**
     * Father.
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Father", mappedBy="category")
     */
    private $father;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->father = new ArrayCollection();
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
     * Add Event.
     *
     * @param Event $event A Event.
     *
     * @return Category
     */
    public function setEvent(Event $event)
    {
        $this->event[] = $event;

        return $this;
    }

    /**
     * Get Event
     *
     * @return Collection
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add event
     *
     * @param Event $event
     *
     * @return Category
     */
    public function addEvent(Event $event)
    {
        $this->event[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     *
     * @return Category
     */
    public function removeEvent(Event $event)
    {
        $this->event->removeElement($event);

        return $this;
    }

    /**
     * Add Father.
     *
     * @param Father $father A Father.
     *
     * @return Category
     */
    public function setFather(Father $father)
    {
        $this->father[] = $father;

        return $this;
    }

    /**
     * Get Father
     *
     * @return Collection
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * Add Father
     *
     * @param Father $father
     *
     * @return Category
     */
    public function addFather(Father $father)
    {
        $this->father[] = $father;

        return $this;
    }

    /**
     * Remove Father
     *
     * @param Father $father
     *
     * @return Category
     */
    public function removeFather(Father $father)
    {
        $this->father->removeElement($father);

        return $this;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Category
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tag;
    }
}
