<?php
namespace Likeme\SystemBundle\Entity;

use Symfony\Tests\Component\Translation\String;

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
	 * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
	 */
	private $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="stranger", referencedColumnName="id", onDelete="cascade")
	 */
	private $stranger;
	
	/**
	 * @var datetime $created_at
	 *
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	private $created_at;
	
	/**
	 * @var datetime $matched_at
	 *
	 * @ORM\Column(name="matched_at", type="datetime", nullable=true)
	 */
	private $matched_at;
	
	public function __construct()
	{
		$this->created_at = new \DateTime();
	}
	
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

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set matched_at
     *
     * @param datetime $matchedAt
     * @return Like
     */
    public function setMatchedAt($matchedAt)
    {
        $this->matched_at = $matchedAt;
        return $this;
    }

    /**
     * Get matched_at
     *
     * @return datetime 
     */
    public function getMatchedAt()
    {
        return $this->matched_at;
    }
}