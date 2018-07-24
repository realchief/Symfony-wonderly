<?php

namespace FrontendBundle\Entity;

use Component\Locator\AddressResolverInterface;
use Component\Locator\FailedResolveRequestException;
use Component\Locator\UnknownAddressException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FrontendBundle\Entity\Event;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\Organize;
use AppBundle\Validator\Constraint as AcmeAssert;

/**
 * MediumOrgEvent
 *
 */
class MediumOrgEvent
{

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg" })
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
    private $ageOrganizer;

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
    private $addressEvent;

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
     * Hours of Operation.
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
     * Hours of Operation.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $duration;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortAddress;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Periodic", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $periodic;


    /**
     * Set img
     *
     * @param string $img
     *
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
     */
    public function setAgeOrganizer($age)
    {
        $this->ageOrganizer = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAgeOrganizer()
    {
        return $this->ageOrganizer;
    }

    /**
     * Set profession
     *
     * @param string $profession
     *
     * @return MediumOrgEvent
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
     * Event constructor.
     */
    public function __construct()
    {
        $this->age = new ArrayCollection();
        $this->periodic = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->createDate = new \DateTime();
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
     * @return MediumOrgEvent
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
    public function getAddressEvent()
    {
        return $this->addressEvent;
    }

    /**
     * Set AddressEvent.
     *
     * @param string $addressEvent A address.
     *
     * @return MediumOrgEvent
     */
    public function setAddressEvent($addressEvent)
    {
        $this->addressEvent = $addressEvent;
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * Set duration.
     *
     * @param \DateTime $origin Event start time. Year, month, day is ignored.
     *
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
     */
    public function setFinishWork(\DateTime $finishWork = null)
    {
        $this->finishWork = $finishWork;

        return $this;
    }

    /**
     * Get duration.
     *
     * @return string|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set duration.
     *
     * @param string $duration A Hours of Operation.
     *
     * @return MediumOrgEvent
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
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
     * @return MediumOrgEvent
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
     * @param string $tips A helpful tips.
     *
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
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
     * @return \DateTime
     */
    public function getEventDateEnd()
    {
        return $this->eventDateEnd;
    }

    /**
     * @param \DateTime $eventDateEnd
     *
     * @return MediumOrgEvent
     */
    public function setEventDateEnd($eventDateEnd)
    {
        $this->eventDateEnd = $eventDateEnd;

        return $this;
    }

    /**
     * Add category
     *
     * @param \FrontendBundle\Entity\Category $category
     *
     * @return MediumOrgEvent
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
     * Add imageEvent
     *
     * @param \FrontendBundle\Entity\ImageEvent $imageEvent
     *
     * @return MediumOrgEvent
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
     * @return MediumOrgEvent
     */
    public function addAge(\FrontendBundle\Entity\Age $age)
    {
        $this->age[] = $age;

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
     * Add periodic
     *
     * @param \FrontendBundle\Entity\Periodic $periodic
     *
     * @return MediumOrgEvent
     */
    public function addPeriodic($periodic)
    {
        $this->periodic[] = $periodic;

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

    /**
     * Create Organizer
     *
     * @return Organize
     */
    public function createOrganizer()
    {
        $organizer = new Organize();
        $organizer->setImg($this->getImg());
        $organizer->setAge($this->getAgeOrganizer());
        $organizer->setLocation($this->getLocation());
        $organizer->setAddress($this->getAddress());
        $organizer->setProfession($this->getProfession());

        return $organizer;
    }

    /**
     * Create Event
     *
     * @return Event
     */
    public function createEvent()
    {
        $event = new Event();
        $event->setName($this->getName());
        foreach ($this->getCategory() as $category) {
            $event->addCategory($category);
        }
        $event->setAddress($this->getAddressEvent());
        $event->setZip($this->getZip());
        $event->setSite($this->getSite());
        $event->setPhonenumber($this->getPhonenumber());
        $event->setEmail($this->getEmail());
        $event->setOutdoor($this->getOutdoor());
        $event->setDescription($this->getDescription());
        foreach ($this->age as $age) {
            $event->addAge($age);
        }
        $event->setEventDate($this->getEventDate());
        $event->setEventDateEnd($this->getEventDateEnd());
        foreach ($this->periodic as $periodic) {
            $event->addPeriodic($periodic);
        }
        $event->setOrigin($this->getOrigin());
        $event->setFinish($this->getFinish());
        $event->setOriginWork($this->getOriginWork());
        $event->setFinishWork($this->getFinishWork());
        $event->setFood($this->getFood());
        $event->setTips($this->getTips());
        $event->setPrice($this->getPrice());

        return $event;
    }
}
