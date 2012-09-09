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
	 * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="stranger", referencedColumnName="id")
	 */
	private $stranger;

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

    /**
     * Set stranger
     *
     * @param Likeme\SystemBundle\Entity\User $stranger
     */
    public function setStranger(\Likeme\SystemBundle\Entity\User $stranger)
    {
        $this->stranger = $stranger;
    }

    /**
     * Get stranger
     *
     * @return Likeme\SystemBundle\Entity\User 
     */
    public function getStranger()
    {
        return $this->stranger;
    }
}