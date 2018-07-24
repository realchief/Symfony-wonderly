<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Child
 *
 * @ORM\Table(name="child")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\ChildRepository")
 */
class Child
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
     * Father.
     *
     * @var Father
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Father", inversedBy="child")
     */
    private $father;

    /**
     * Birthday
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * First name.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $firstname;

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
     * @return Child
     */
    public function setFather(Father $father = null)
    {
        $this->father = $father;

        return $this;
    }

    /**
     * Set parent
     *
     * @param Father $father
     *
     * @return Child
     */
    public function addFather(Father $father = null)
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Child
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Child
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
}
