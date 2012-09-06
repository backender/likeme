<?php
namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_next")
 */
class Next
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="user_next", referencedColumnName="id")
	 */
	private $user_next;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="stranger_next", referencedColumnName="id")
	 */
	private $stranger_next;

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
     * Set user_next
     *
     * @param Likeme\SystemBundle\Entity\User $userNext
     */
    public function setUserNext(\Likeme\SystemBundle\Entity\User $userNext)
    {
        $this->user_next = $userNext;
    }

    /**
     * Get user_next
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getUserNext()
    {
        return $this->user_next;
    }

    /**
     * Set stranger_next
     *
     * @param Likeme\SystemBundle\Entity\User $strangerNext
     */
    public function setStrangerNext(\Likeme\SystemBundle\Entity\User $strangerNext)
    {
        $this->stranger_next = $strangerNext;
    }

    /**
     * Get stranger_next
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getStrangerNext()
    {
        return $this->stranger_next;
    }
}