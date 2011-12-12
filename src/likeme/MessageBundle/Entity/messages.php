<?php

namespace likeme\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * likeme\MessageBundle\Entity\messages
 */
class messages
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
     * @var text $message
     */
    private $message;


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
     * Set message
     *
     * @param text $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
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
}