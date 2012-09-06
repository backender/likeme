<?php
namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_like")
 */
class Like
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="user_like", referencedColumnName="id")
	 */
	private $user_like;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="stranger_like", referencedColumnName="id")
	 */
	private $stranger_like;

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
     * Set user_like
     *
     * @param Likeme\SystemBundle\Entity\User $userLike
     */
    public function setUserLike(\Likeme\SystemBundle\Entity\User $userLike)
    {
        $this->user_like = $userLike;
    }

    /**
     * Get user_like
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getUserLike()
    {
        return $this->user_like;
    }

    /**
     * Set stranger_like
     *
     * @param Likeme\SystemBundle\Entity\User $strangerLike
     */
    public function setStrangerLike(\Likeme\SystemBundle\Entity\User $strangerLike)
    {
        $this->stranger_like = $strangerLike;
    }

    /**
     * Get stranger_like
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getStrangerLike()
    {
        return $this->stranger_like;
    }
}