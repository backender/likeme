<?php

namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likeme\SystemBundle\Entity\Messages
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Messages
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
     * @var integer $isread
     *
     * @ORM\Column(name="isread", type="integer")
     */
    private $isread;

    /**
     * @var text $message
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var datetime $time
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="user_send", referencedColumnName="id", onDelete="cascade")
	 */
	private $user_send;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
     * @ORM\JoinColumn(name="user_receive", referencedColumnName="id", onDelete="cascade")
     */
    private $user_receive;
    
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
     * Set isread
     *
     * @param integer $isread
     * @return Messages
     */
    public function setIsread($isread)
    {
        $this->isread = $isread;
        return $this;
    }

    /**
     * Get isread
     *
     * @return integer 
     */
    public function getIsread()
    {
        return $this->isread;
    }

    /**
     * Set message
     *
     * @param text $message
     * @return Messages
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
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
     * Set time
     *
     * @param datetime $time
     * @return Messages
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return datetime 
     */
    public function getTime()
    {
        return $this->time;
    }
}