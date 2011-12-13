<?php

namespace likeme\SpamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * likeme\SpamBundle\Entity\Spam
 */
class Spam
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
     * @var integer $profileIdTo
     */
    private $profileIdTo;

    /**
     * @var integer $profileIdFrom
     */
    private $profileIdFrom;

    /**
     * @var datetime $timestamp
     */
    private $timestamp;

    /**
     * @var text $reason
     */
    private $reason;


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
     * Set profileIdTo
     *
     * @param integer $profileIdTo
     */
    public function setProfileIdTo($profileIdTo)
    {
        $this->profileIdTo = $profileIdTo;
    }

    /**
     * Get profileIdTo
     *
     * @return integer 
     */
    public function getProfileIdTo()
    {
        return $this->profileIdTo;
    }

    /**
     * Set profileIdFrom
     *
     * @param integer $profileIdFrom
     */
    public function setProfileIdFrom($profileIdFrom)
    {
        $this->profileIdFrom = $profileIdFrom;
    }

    /**
     * Get profileIdFrom
     *
     * @return integer 
     */
    public function getProfileIdFrom()
    {
        return $this->profileIdFrom;
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

    /**
     * Set reason
     *
     * @param text $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Get reason
     *
     * @return text 
     */
    public function getReason()
    {
        return $this->reason;
    }
}