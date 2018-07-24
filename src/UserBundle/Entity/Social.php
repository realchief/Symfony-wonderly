<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Social
 *
 * @ORM\Table(name="social")
 * @ORM\Entity()
 */
class Social
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="social")
     */
    private $user;

    /**
     * Social Name.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $socialName;

    /**
     * Social ID.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $socialId;

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getSocialName()
    {
        return $this->socialName;
    }

    /**
     * @param string $socialName
     */
    public function setSocialName($socialName)
    {
        $this->socialName = $socialName;
    }

    /**
     * @return string
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * @param string $socialId
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;
    }
}
