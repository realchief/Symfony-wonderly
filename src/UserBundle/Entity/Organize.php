<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FrontendBundle\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Organize
 *
 * @ORM\Table(name="organize")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\OrganizeRepository")
 */
class Organize
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
     * Events.
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="FrontendBundle\Entity\Event", mappedBy="organize", cascade={"remove"})
     */
    private $event;

    /**
     * Association mapping with User.
     *
     * @var User
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User", inversedBy="organize")
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     */
    private $img;

    /**
     * Profession.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $profession;

    /**
     * Address.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * Location.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $location;

    /**
     * Age.
     *
     * @var integer
     *
     *
     * @ORM\Column(type="integer")
     */
    private $age;

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
     * Add Event
     *
     * @param Event $event
     *
     * @return Organize
     */
    public function setEvent(Event $event)
    {
        $this->event[] = $event;
        return $this;
    }

    /**
     * Get Events
     *
     * @return Collection
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->user->getEmail();
    }

    /**
     * Add event
     *
     * @param \FrontendBundle\Entity\Event $event
     *
     * @return Organize
     */
    public function addEvent(\FrontendBundle\Entity\Event $event)
    {
        $this->event[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \FrontendBundle\Entity\Event $event
     */
    public function removeEvent(\FrontendBundle\Entity\Event $event)
    {
        $this->event->removeElement($event);
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Organize
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get user firstname
     *
     * @return string
     */
    public function getUserFirstname()
    {
        return $this->user->getFirstname();
    }

    /**
     * Get user lastname
     *
     * @return string
     */
    public function getUserLastname()
    {
        return $this->user->getLastname();
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Organize
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Organize
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Organize
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Organize
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set profession
     *
     * @param string $profession
     *
     * @return Organize
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        $email = $this->getUser()->getEmail();

        return $email;
    }
}
