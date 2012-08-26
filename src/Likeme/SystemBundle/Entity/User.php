<?php
namespace Likeme\SystemBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var boolean $active
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $active;
	
	/**
	 * @var string $firstname
	 *
	 * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
	 */
	protected $firstname;
	
	/**
	 * @var string $lastname
	 *
	 * @ORM\Column(name="lastname", type="string", length=45, nullable=true)
	 */
	protected $lastname;
	
  	/**
  	 * @var string
  	 * @ORM\Column(type="string", nullable=true)
  	 */
  	protected $facebookID;
	
	/**
	 * @var integer $gender
	 *
	 * @ORM\Column(name="gender", type="integer", nullable=true)
	 */
	private $gender;
	
	/**
	 * @var string $birthday
	 *
	 * @ORM\Column(name="birthday", type="string", nullable=true)
	 */
	private $birthday;
	
	/**
	 * @var string $location
	 *
	 * @ORM\Column(name="location", type="string", nullable=false)
	 * @Assert\NotBlank(message="Please enter location.")
	 */
	private $location;
	
	/**
	 * @var text $aboutme
	 *
	 * @ORM\Column(name="aboutme", type="text", nullable=true)
	 */
	private $aboutme;
	

	public function __construct()
	{
		parent::__construct();

	}
	
	public function serialize()
	{
		return serialize(array($this->facebookID, parent::serialize()));
	}
	
	public function unserialize($data)
	{
		list($this->facebookID, $parentData) = unserialize($data);
		parent::unserialize($parentData);
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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set aboutme
     *
     * @param text $aboutme
     */
    public function setAboutme($aboutme)
    {
        $this->aboutme = $aboutme;
    }

    /**
     * Get aboutme
     *
     * @return text 
     */
    public function getAboutme()
    {
        return $this->aboutme;
    }
    
    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
    	return $this->getFirstName() . ' ' . $this->getLastname();
    }
    
    /**
     * @param string $facebookID
     * @return void
     */
    public function setFacebookID($facebookID)
    {
    	$this->facebookID = $facebookID;
    	$this->setUsername($facebookID);
    	$this->salt = '';
    }
    
    /**
     * @return string
     */
    public function getFacebookID()
    {
    	return $this->facebookID;
    }
    
    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
    	$this->active = true;
    	
    	if (isset($fbdata['id'])) {
    		$this->setFacebookID($fbdata['id']);
    		$this->addRole('ROLE_FACEBOOK');
    	}
    	if (isset($fbdata['first_name'])) {
    		$this->setFirstname($fbdata['first_name']);
    	}
    	if (isset($fbdata['last_name'])) {
    		$this->setLastname($fbdata['last_name']);
    	}
    	if (isset($fbdata['email'])) {
    		$this->setEmail($fbdata['email']);
    	}
    	if($this->getBirthday() == NULL) {
	    	if (isset($fbdata['birthday'])) {
	    		$this->setBirthday($fbdata['birthday']);
	    	} else {
	    		$this->active = false;
	    	}
    	}
    	if($this->getLocation() == NULL) {
	    	if (isset($fbdata['location'])) {
	    		$this->setLocation($fbdata['location']['name']);
	    	} else {
	    		$this->active = false;
	    	}
    	}
	    if($this->getAboutme() == NULL) {	
	    	if (isset($fbdata['bio'])) {
	    		$this->setAboutme($fbdata['bio']);
	    	} else {
	    		$this->active = false;
	    	}
	    }
		if($this->getGender() == NULL) {    
	    	if (isset($fbdata['gender'])) {
	    		$this->setGender($fbdata['gender']);
	    	} else {
	    		$this->active = false;
	    	}
		}
    }
    

    /**
     * Set birthday
     *
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return string 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set location
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set active
     *
     * @param integer $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
    
    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }
}