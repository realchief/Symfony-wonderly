<?php

namespace UserBundle\Entity;

use Component\Locator\AddressResolverInterface;
use Component\Locator\FailedResolveRequestException;
use Component\Locator\UnknownAddressException;
use Doctrine\ORM\Mapping as ORM;
use FrontendBundle\Entity\CommentEvent;
use Doctrine\Common\Collections\Collection;
use FrontendBundle\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraint as AcmeAssert;
use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * Father
 *
 * @ORM\Table(name="father")
 * @ORM\Entity
 */
class Father
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
     * Comments for Event.
     *
     * @ORM\OneToMany(targetEntity="FrontendBundle\Entity\CommentEvent", mappedBy="father", cascade={"remove"})
     */
    private $commentEvent;

    /**
     * Kids.
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Child", mappedBy="father", cascade={"persist", "remove"})
     */
    private $child;

    /**
     * Association mapping with User.
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User", inversedBy="father")
     */
    private $user;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="FrontendBundle\Entity\Category", inversedBy="father")
     * @ORM\JoinTable(name="fatherIntermediate")
     *
     * @Assert\Count(min=1)
     */
    protected $category;

    /**
     * Age.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * Address.
     *
     * @var string
     *
     * @AcmeAssert\Address()
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
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     */
    private $img;

    /**
     * @ORM\Column(type="point", nullable=true)
     *
     * @var Point
     */
    private $point;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->child = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add comment
     *
     * @param CommentEvent $comment
     *
     * @return Father
     */
    public function setCommentEvent(CommentEvent $comment)
    {
        $this->commentEvent[] = $comment;
        return $this;
    }

    /**
     * Get comments
     *
     * @return Collection
     */
    public function getCommentEvent()
    {
        return $this->commentEvent;
    }

    /**
     * Add commentEvent
     *
     * @param \FrontendBundle\Entity\CommentEvent $commentEvent
     *
     * @return Father
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
     * Add child
     *
     * @param Child $child
     *
     * @return Father
     */
    public function setChild(Child $child)
    {
        $child->setFather($this);
        $this->child[] = $child;
        return $this;
    }

    /**
     * Get child
     *
     * @return Collection
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Add child
     *
     * @param \UserBundle\Entity\Child $child
     *
     * @return Father
     */
    public function addChild(Child $child)
    {
        $child->setFather($this);
        $this->child[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \UserBundle\Entity\Child $child
     */
    public function removeChild(Child $child)
    {
        $this->child->removeElement($child);
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Father
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
     * Set img
     *
     * @param string $img
     *
     * @return Father
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
     * @return Father
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
     * @return Father
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
     * @return Father
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
     * Add category
     *
     * @param \FrontendBundle\Entity\Category $category
     *
     * @return Father
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
     * Set Point.
     *
     * @param Point $point A Object Point.
     *
     * @return Father
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
     * @return Father
     *
     * @throws FailedResolveRequestException Can't resolve address due to external
     *                                       api error.
     * @throws UnknownAddressException Api can't resolve specified address.
     */
    public function resolveAddress(AddressResolverInterface $addressResolver)
    {

        $geo = $addressResolver->resolveAddress($this->address);
        $this->point = $geo[0];

        return $this;
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
}
