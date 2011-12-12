<?php

namespace likeme\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * likeme\ProfileBundle\Entity\Pictures
 */
class Pictures
{
    /**
     * @var integer $id
     */
    private $id;


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
     * @var integer $profileId
     */
    private $profileId;

    /**
     * @var string $src
     */
    private $src;

    /**
     * @var integer $isProfile
     */
    private $isProfile;

    /**
     * @var datetime $timestamp
     */
    private $timestamp;


    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set profileId
     *
     * @param integer $profileId
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    /**
     * Get profileId
     *
     * @return integer 
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * Set src
     *
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->src = $src;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set isProfile
     *
     * @param integer $isProfile
     */
    public function setIsProfile($isProfile)
    {
        $this->isProfile = $isProfile;
    }

    /**
     * Get isProfile
     *
     * @return integer 
     */
    public function getIsProfile()
    {
        return $this->isProfile;
    }

    /**
     * Set timestamp
     *
     * @param datetime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get timestamp
     *
     * @return datetime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}