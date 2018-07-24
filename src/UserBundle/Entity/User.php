<?php

namespace UserBundle\Entity;

use Component\EventFilter\Model\EventFilters;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FrontendBundle\Entity\Event;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email is already used."
 * )
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Firstname.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $firstname;

    /**
     * Lastname.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * Association mapping with Father.
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Father", mappedBy="user", cascade={"persist", "remove"})
     */
    private $father;

    /**
     * Association mapping with Organizer.
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Organize", mappedBy="user", cascade={"persist", "remove"})
     */
    private $organize;

    /**
     * Association mapping with Event.
     * @ORM\ManyToMany(targetEntity="FrontendBundle\Entity\Event", mappedBy="likedUser")
     */
    protected $favoriteEvent;

    /**
     * Association mapping with Social.
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Social", mappedBy="user", cascade={"persist", "remove"})
     */
    private $social;

    /**
     * @var EventFilters
     *
     * @ORM\Column(type="object", nullable=true)
     */
    private $eventFilters;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->social = new \Doctrine\Common\Collections\ArrayCollection();
        $this->favoriteEvent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->username = $email;

        return $this;
    }

    /**
     * Set email canonical.
     *
     * @param string $emailCanonical
     *
     * @return User
     */
    public function setEmailCanonical($emailCanonical){
        $this->emailCanonical = $emailCanonical;
        $this->usernameCanonical = $emailCanonical;

        return $this;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password){
        $this->setPlainPassword($password);
        $this->password = $password;
        return $this;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set father
     *
     * @param \UserBundle\Entity\Father $father
     *
     * @return User
     */
    public function setFather(Father $father = null)
    {
        $father->setUser($this);
        $this->father = $father;

        return $this;
    }

    /**
     * Get father
     *
     * @return \UserBundle\Entity\Father
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * Set organize
     *
     * @param \UserBundle\Entity\Organize $organize
     *
     * @return User
     */
    public function setOrganize(Organize $organize = null)
    {
        $organize->setUser($this);
        $this->organize = $organize;

        return $this;
    }

    /**
     * Get organize
     *
     * @return \UserBundle\Entity\Organize
     */
    public function getOrganize()
    {
        return $this->organize;
    }

    /**
     * Set eventFilters
     *
     * @param EventFilters $eventFilters A user event filters.
     *
     * @return User
     */
    public function setEventFilters(EventFilters $eventFilters = null)
    {
        // TODO When user got his location point we should call EventFilters::setDestination here.
        $this->eventFilters = $eventFilters;

        return $this;
    }

    /**
     * Get eventFilters
     *
     * @return EventFilters|null
     */
    public function getEventFilters()
    {
        // TODO When user got his location point we should call EventFilters::setDestination here.
        return $this->eventFilters;
    }

    /**
     * Add social
     *
     * @param Social $social
     *
     * @return User
     */
    public function setSocial(Social $social)
    {
        $social->setUser($this);
        $this->social[] = $social;
        return $this;
    }

    /**
     * Get social
     *
     * @return Collection
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * Add social
     *
     * @param \UserBundle\Entity\Social $social
     *
     * @return User
     */
    public function addSocial(Social $social)
    {
        $social->setUser($this);
        $this->social[] = $social;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \UserBundle\Entity\Social $social
     */
    public function removeChild(Social $social)
    {
        $this->social->removeElement($social);
    }

    /**
     * Get Event
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteEvent()
    {
        return $this->favoriteEvent;
    }

    /**
     * Add Event.
     *
     * @param Event $event A Event.
     *
     * @return User
     */
    public function setFavoriteEvent(Event $event)
    {
        $event->addLikedUser($this);
        $this->favoriteEvent[] = $event;
        return $this;
    }

    /**
     * Remove child
     *
     * @param \FrontendBundle\Entity\Event $event
     */
    public function removeFavoriteEvent(Event $event)
    {
        $event->removeLikedUser($this);
//        $this->favoriteEvent->removeElement($event);
    }
}
