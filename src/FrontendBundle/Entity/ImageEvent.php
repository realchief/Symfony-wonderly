<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="imageEvent")
 * @ORM\HasLifecycleCallbacks
 */
class ImageEvent
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     */
    private $img;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="imageEvent")
     */
    protected $event;

    /**
     * Image URL.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set object
     *
     * @param Event $event
     *
     * @return object
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get object
     *
     * @return object
     */
    public function getEvent()
    {
        return $this->event;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    public function addImg($img)
    {
        $this->img[] = $img;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function addUrl($url)
    {
        $this->url[] = $url;

        return $this;
    }

    public function getPicture() {
        $picture = ($this->img === null) ? $this->url : '../uploads/image/'.$this->img;
        return $picture;
    }
}
