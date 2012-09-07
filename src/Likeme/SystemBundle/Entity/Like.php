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
	 * @ORM\JoinColumn(name="user", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
	 * @ORM\JoinColumn(name="stranger", referencedColumnName="id")
	 */
	private $stranger;

}