<?php

namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likeme\SystemBundle\Entity\Preference
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Preference
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $gender
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;

    /**
     * @var string $age_range
     *
     * @ORM\Column(name="age_range", type="string", length=255)
     */
    private $age_range;

    /**
     * @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\User", inversedBy="Pictures")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $user;

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
     * Set gender
     *
     * @param integer $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set age_range
     *
     * @param string $ageRange
     */
    public function setAgeRange($ageRange)
    {
        $this->age_range = $ageRange;
    }

    /**
     * Get age_range
     *
     * @return string 
     */
    public function getAgeRange()
    {
        return $this->age_range;
    }

    /**
     * Set user
     *
     * @param Likeme\SystemBundle\Entity\User $user
     */
    public function setUser(\Likeme\SystemBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}