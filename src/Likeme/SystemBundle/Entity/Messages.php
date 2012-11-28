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

    /**
     * Set user_send
     *
     * @param Likeme\SystemBundle\Entity\User $userSend
     * @return Messages
     */
    public function setUserSend(\Likeme\SystemBundle\Entity\User $userSend = null)
    {
        $this->user_send = $userSend;
        return $this;
    }

    /**
     * Get user_send
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getUserSend()
    {
        return $this->user_send;
    }

    /**
     * Set user_receive
     *
     * @param Likeme\SystemBundle\Entity\User $userReceive
     * @return Messages
     */
    public function setUserReceive(\Likeme\SystemBundle\Entity\User $userReceive = null)
    {
        $this->user_receive = $userReceive;
        return $this;
    }

    /**
     * Get user_receive
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getUserReceive()
    {
        return $this->user_receive;
    }
}