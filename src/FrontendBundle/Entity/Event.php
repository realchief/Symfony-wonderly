<?php

namespace FrontendBundle\Entity;

use Component\Locator\AddressResolverInterface;
use Component\Locator\FailedResolveRequestException;
use Component\Locator\UnknownAddressException;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="FrontendBundle\Repository\EventRepository")
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Event
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
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="event")
     * @ORM\JoinTable(name="eventIntermediate")
     *
     * @Assert\Count(min=1)
     */
    protected $category;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", inversedBy="favoriteEvent")
     * @ORM\JoinTable(name="favoriteEvent")
     */
    private $likedUser;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="CommentEvent", mappedBy="event", cascade={"remove"})
     */
    private $commentEvent;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Organize", inversedBy="event")
     */
    private $organize;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="\FrontendBundle\Entity\ImageEvent", mappedBy="event", cascade={"persist", "remove"})
     */
    private $imageEvent;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Age", mappedBy="event", cascade={"persist", "remove"})
     */
    private $age;

    /**
    * Name of Event.
    *
    * @var string
    *
    * @ORM\Column(type="string", nullable=true)
    */
    private $name;

    /**
    * Address.
    *
    * @var string
    *
    * @ORM\Column(type="string")
    */
    private $address;

    /**
    * Zip Code.
    *
    * @var string
    *
    * @ORM\Column(type="string")
    */
    private $zip;

    /**
    *
    * @var string
    *
    * @ORM\Column(type="string")
    */
    private $site;

    /**
    * Phonenumber Event.
    *
    * @var string
    *
    * @ORM\Column(type="string", nullable=true)
    */
    private $phonenumber;

    /**
    * Email Event.
    *
    * @var string
    *
    * @ORM\Column(type="string", nullable=true)
    */
    private $email;

    /**
    * Date Create of the Event
    *
    * @var \DateTime
    *
    * @ORM\Column(type="datetime")
    */
    private $createDate;

    /**
     * @ORM\Column(type="point", nullable=true)
     *
     * @var Point
     */
    private $point;

    /**
     * Indoor or Outdoor.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $outdoor;

    /**
     * What doing.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * Price.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $price;

    /**
     * Time start event.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $origin;

    /**
     * Time end event.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $finish;

    /**
     * Time start event.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $originWork;

    /**
     * Time end event.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $finishWork;

    /**
     * Date of the Event
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $eventDate;

    /**
     * Date End of the Event
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $eventDateEnd;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Periodic", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $periodic;

    /**
     * Food available for purchase.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $food = false;

    /**
     * Helpful Tips.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $tips;

    /**
     * Short Address.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $shortAddress;

    /**
     * By recommend.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $recommend = false;

    /**
     * Rating of the Event.
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    public $liked = false;

    /**
    * Event constructor.
    */
    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->likedUser = new ArrayCollection();
        $this->imageEvent = new ArrayCollection();
        $this->commentEvent = new ArrayCollection();
        $this->age = new ArrayCollection();
        $this->periodic = new ArrayCollection();
        $this->createDate = new \DateTime();
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
    * Get name.
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set name.
    *
    * @param string $name A name.
    *
    * @return Event
    */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
    * Get Address.
    *
    * @return string
    */
    public function getAddress()
    {
        return $this->address;
    }

    /**
    * Set Address.
    *
    * @param string $address A address.
    *
    * @return Event
    */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
    * Get ZIP code.
    *
    * @return string
    */
    public function getZip()
    {
        return $this->zip;
    }

    /**
    * Set ZIP code.
    *
    * @param string $zip A ZIP code.
    *
    * @return Event
    */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
    * Get Web Site.
    *
    * @return string
    */
    public function getSite()
    {
        return $this->site;
    }

    /**
    * Set Site.
    *
    * @param string $site A site.
    *
    * @return Event
    */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
    * Get phonenumber.
    *
    * @return string
    */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
    * Set phonenumber.
    *
    * @param string $phonenumber A phonenumber.
    *
    * @return Event
    */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }

    /**
    * Get email.
    *
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
    * Set email.
    *
    * @param string $email Email.
    *
    * @return Event
    */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    /**
    * Get Created Date.
    *
    * @return object
    */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set Point.
     *
     * @param Point $point A Object Point.
     *
     * @return Event
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @param AddressResolverInterface $addressResolver A AddressResolverInterface
     *                                                  instance.
     *
     * @return Event
     *
     * @throws FailedResolveRequestException Can't resolve address due to external
     *                                       api error.
     * @throws UnknownAddressException Api can't resolve specified address.
     */
    public function resolveAddress(AddressResolverInterface $addressResolver)
    {
        $geo = $addressResolver->resolveAddress($this->address);
        $this->point = $geo[0];
        $this->shortAddress = $geo[1];
        return $this;
    }

    /**
     * Get Short Address.
     *
     * @return string
     */
    public function getShortAddress()
    {
        return $this->shortAddress;
    }

    /**
     * Get Point.
     *
     * @return Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Get Indoor or Outdoor.
     *
     * @return string
     */
    public function getOutdoor()
    {
        return $this->outdoor;
    }

    /**
     * Set Indoor or Outdoor.
     *
     * @param string $outdoor Outdoor or Indoor or Match.
     *
     * @return Event
     */
    public function setOutdoor($outdoor)
    {
        $this->outdoor = $outdoor;

        return $this;
    }


    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description A description.
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param string $price A price.
     *
     * @return Event
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }


    /**
     * Get origin.
     *
     * @return \DateTime|null
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set Origin.
     *
     * @param \DateTime $origin Event start time. Year, month, day is ignored.
     *
     * @return Event
     */
    public function setOrigin(\DateTime $origin = null)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get finish.
     *
     * @return \DateTime|null
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * Set finish.
     *
     * @param \DateTime $finish Event end time. Year, month, day is ignored.
     *
     * @return Event
     */
    public function setFinish(\DateTime $finish = null)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Get origin Work.
     *
     * @return \DateTime|null
     */
    public function getOriginWork()
    {
        return $this->originWork;
    }

    /**
     * Set origin Work.
     *
     * @param \DateTime $originWork Event start time. Year, month, day is ignored.
     *
     * @return Event
     */
    public function setOriginWork(\DateTime $originWork = null)
    {
        $this->originWork = $originWork;

        return $this;
    }

    /**
     * Get finish Work.
     *
     * @return \DateTime|null
     */
    public function getFinishWork()
    {
        return $this->finishWork;
    }

    /**
     * Set finish Work.
     *
     * @param \DateTime $finishWork Event end time. Year, month, day is ignored.
     *
     * @return Event
     */
    public function setFinishWork(\DateTime $finishWork = null)
    {
        $this->finishWork = $finishWork;

        return $this;
    }

    /**
     * Get food.
     *
     * @return boolean
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * Set food.
     *
     * @param boolean $food Flag, true if isset food.
     *
     * @return Event
     */
    public function setFood($food)
    {
        $this->food = $food;

        return $this;
    }


    /**
     * Get tips.
     *
     * @return string
     */
    public function getTips()
    {
        return $this->tips;
    }

    /**
     * Set tips.
     *
     * @param string|null $tips A helpful tips.
     *
     * @return Event
     */
    public function setTips($tips)
    {
        $this->tips = $tips;
        return $this;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Event
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return Event
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Get Recommend.
     *
     * @return boolean
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Set Recommend.
     *
     * @param boolean $recommend
     *
     * @return Event
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;

        return $this;
    }


    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Event
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Add category
     *
     * @param \FrontendBundle\Entity\Category $category
     *
     * @return Event
     */
    public function addCategory(\FrontendBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \FrontendBundle\Entity\Category $category
     */
    public function removeCategory(\FrontendBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add liked user
     *
     * @param User $user
     *
     * @return Event
     */
    public function addLikedUser(User $user)
    {
        $this->likedUser[] = $user;

        return $this;
    }

    /**
     * Remove liked user
     *
     * @param User $user
     */
    public function removeLikedUser(User $user)
    {
        $this->likedUser->removeElement($user);
    }

    /**
     * Get Licked Users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikedUser()
    {
        return $this->likedUser;
    }

    /**
     * Add commentEvent
     *
     * @param \FrontendBundle\Entity\CommentEvent $commentEvent
     *
     * @return Event
     */
    public function addCommentEvent(\FrontendBundle\Entity\CommentEvent $commentEvent)
    {
        $this->commentEvent[] = $commentEvent;

        return $this;
    }

    /**
     * Remove commentEvent
     *
     * @param \FrontendBundle\Entity\CommentEvent $commentEvent
     */
    public function removeCommentEvent(\FrontendBundle\Entity\CommentEvent $commentEvent)
    {
        $this->commentEvent->removeElement($commentEvent);
    }

    /**
     * Get commentEvent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentEvent()
    {
        return $this->commentEvent;
    }

    /**
     * Set organize
     *
     * @param \UserBundle\Entity\Organize $organize
     *
     * @return Event
     */
    public function setOrganize(\UserBundle\Entity\Organize $organize = null)
    {
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
     * Add imageEvent
     *
     * @param \FrontendBundle\Entity\ImageEvent $imageEvent
     *
     * @return Event
     */
    public function addImageEvent(\FrontendBundle\Entity\ImageEvent $imageEvent)
    {
        $this->imageEvent[] = $imageEvent;

        return $this;
    }

    /**
     * Remove imageEvent
     *
     * @param \FrontendBundle\Entity\ImageEvent $imageEvent
     */
    public function removeImageEvent(\FrontendBundle\Entity\ImageEvent $imageEvent)
    {
        $this->imageEvent->removeElement($imageEvent);
    }

    /**
     * Get imageEvent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImageEvent()
    {
        return $this->imageEvent;
    }

    /**
     * Add age
     *
     * @param \FrontendBundle\Entity\Age $age
     *
     * @return Event
     */
    public function addAge(\FrontendBundle\Entity\Age $age)
    {
        $this->age[] = $age;
        $age->setEvent($this);

        return $this;
    }

    /**
     * Remove age
     *
     * @param \FrontendBundle\Entity\Age $age
     */
    public function removeAge(\FrontendBundle\Entity\Age $age)
    {
        $this->age->removeElement($age);
        $age->setEvent(null);
    }

    /**
     * Get age
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return \DateTime
     */
    public function getEventDateEnd()
    {
        return $this->eventDateEnd;
    }

    /**
     * @param \DateTime $eventDateEnd
     *
     * @return Event
     */
    public function setEventDateEnd($eventDateEnd)
    {
        $this->eventDateEnd = $eventDateEnd;

        return $this;
    }

    /**
     * Add periodic
     *
     * @param \FrontendBundle\Entity\Periodic $periodic
     *
     * @return Event
     */
    public function addPeriodic($periodic)
    {
        $this->periodic[] = $periodic;
        $periodic->setEvent($this);

        return $this;
    }

    /**
     * Remove periodic
     *
     * @param \FrontendBundle\Entity\Periodic $periodic
     */
    public function removePeriodic(\FrontendBundle\Entity\Periodic $periodic)
    {
        $this->periodic->removeElement($periodic);
        $periodic->setEvent(null);
    }

    /**
     * Get Periodic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodic()
    {
        return $this->periodic;
    }

    public function jsonSerialize()
    {
        $imgArray = $this->getImageEvent();
        $img = '';
        if (!empty($imgArray->toArray())) {
            $img = ($imgArray[0]->getImg() !== null) ? $imgArray[0]->getImg() : $imgArray[0]->getUrl();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'img' => $img,
        ];
    }
}
