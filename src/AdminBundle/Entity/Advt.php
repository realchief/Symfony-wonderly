<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advt
 *
 * @ORM\Table(name="advt")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\AdvtRepository")
 *
 */
class Advt
{

    /**
     * @var array
     */
    private $placeMap = [ 0 => 'banner', 1 => 'upcoming events'];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Place on web site.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $place;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * Date Create of the Event
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * Advt constructor.
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return string
     */
    public function getPlaceString()
    {
        return $this->placeMap[$this->place];
    }

    /**
     * @param integer $place
     */
    public function setPlace(int $place)
    {
        $this->place = $place;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}
