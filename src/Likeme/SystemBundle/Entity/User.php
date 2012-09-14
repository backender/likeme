<?php
namespace Likeme\SystemBundle\Entity;

use Likeme\SystemBundle\Controller\LocationController;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="Likeme\SystemBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\OneToMany(targetEntity="Like", mappedBy="user")
	 * @ORM\OneToMany(targetEntity="Like", mappedBy="stranger")
	 * @ORM\OneToMany(targetEntity="Next", mappedBy="user")
	 * @ORM\OneToMany(targetEntity="Next", mappedBy="stranger")
	 */
	protected $id;
	
	/**
	 * @var integer $active
	 *
	 * @ORM\Column(type="integer", nullable=true)
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
	 * @var string $gender
	 *
	 * @ORM\Column(name="gender", type="string", nullable=true)
	 */
	protected $gender;
	
	/**
	 * @var date $birthday
	 *
	 * @ORM\Column(name="birthday", type="datetime", nullable=true)
	 */
	protected $birthday;
	
	
	/**
	 * @var integer $location
	 *
	 * @Assert\NotBlank(message="Please enter location.")
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="id")
	 * @ORM\JoinColumn(name="location", referencedColumnName="id")
	 *
	 */
	protected $location;
	
	/**
	 * @var integer $pictures
	 *
	 * @ORM\OneToMany(targetEntity="Pictures", mappedBy="user")
	 */
	protected $pictures;
	
	/**
	 * @var text $aboutme
	 *
	 * @ORM\Column(name="aboutme", type="text", nullable=true)
	 * @Assert\NotBlank(message="Bitte ausfüllen!")
	 */
	protected $aboutme;
	
	/**
	 * @var integer $pref_gender
	 *
	 * @ORM\Column(name="pref_gender", type="integer", nullable=false)
	 */
	protected $pref_gender;
	
	/**
	 * @var string $pref_age_range
	 *
	 * @ORM\Column(name="pref_age_range", type="string", length=255, nullable=false)
	 */
	protected $pref_age_range;
	
	/**
	 * @var integer $strangerlimit
	 *
	 * @ORM\Column(name="strangerlimit", type="integer", nullable=true)
	 */
	protected $strangerlimit;
	
	/**
	 * @var date $strangerlimit_time
	 *
	 * @ORM\Column(name="strangerlimit_time", type="date", nullable=true)
	 */
	protected $strangerlimit_time;


	public function __construct()
	{
		parent::__construct();
		
		if ($this->getPrefAgeRange() == NULL) {
			$this->setPrefAgeRange("1-100");
		}
		$this->user_who_likes = new \Doctrine\Common\Collections\ArrayCollection();
		$this->liked_user = new \Doctrine\Common\Collections\ArrayCollection();
		$this->strangerlimit_time = new \DateTime();

	}
	
	public function __toString()
	{
		return $this->firstname;
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
	 * Increase StrangerLimit +1 (when user likes/nexts)
	 */
	public function increaseStrangerlimit()
	{
		$now = new \DateTime();
		if ($this->getStrangerlimitTime()->format("d-m-Y") !== $now->format("d-m-Y")) {
			$this->setStrangerlimitTime($now);
			$this->setStrangerlimit(0);
		}
	
		$this->setStrangerlimit($this->getStrangerlimit()+1);
	
		return true;
	}
	
	/**
	 * GetStrangerLimit and reset to 0 if StrangerLimitTime is not today
	 *
	 * @return integer
	 */
	public function getStrangerLimitExact()
	{
		$now = new \DateTime();
		if ($this->strangerlimit_time->format("d-m-Y") !== $now->format("d-m-Y")) {
			$this->setStrangerlimit(0);
			$this->setStrangerlimitTime($now);
		}
	
		return $this->getStrangerlimit();
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
    public function setFBData($fbdata = null)
    {
    	$this->active = 1;
    	
    	if($this->getUsername() == NULL) {
	    	if (isset($fbdata['id'])) {
	    		$this->setFacebookID($fbdata['id']);
	    		$this->addRole('ROLE_FACEBOOK');
	    	}
    	}
    	if($this->getFirstname() == NULL) {
	    	if (isset($fbdata['first_name'])) {
	    		$this->setFirstname($fbdata['first_name']);
	    	}
    	}
    	if($this->getLastname() == NULL) {
	    	if (isset($fbdata['last_name'])) {
	    		$this->setLastname($fbdata['last_name']);
	    	}
    	}
    	if($this->getEmail() == NULL) {
	    	if (isset($fbdata['email'])) {
	    		$this->setEmail($fbdata['email']);
	    	}
    	}
    	if($this->getBirthday() == NULL) {
	    	if (isset($fbdata['birthday'])) {
	    		$this->setBirthday(new \DateTime(date("Y-m-d", strtotime($fbdata['birthday']))));
	    	}
    	}
    	//facebook data could be empty
    	if($this->getLocation() == NULL) {
    		//already checked on facebook user provider
	    	$this->active = 0;
    	}
    	
    	//facebook data could be empty
	    if($this->getAboutme() == '') {	
	    	if (isset($fbdata['bio'])) {
	    		$this->setAboutme($fbdata['bio']);
	    	} else {
	    		$this->active = 0;
	    	}
	    }
		if($this->getGender() == NULL) {    
	    	if (isset($fbdata['gender'])) {
	    		$this->setGender($fbdata['gender']);
	    	}
		}
		
		//Set default values for users preferences
		if ($this->getPrefGender() == null) {
			$this->setPrefGender(0);
			if($this->getGender() == 'male') {
				$this->setPrefGender(2);
			}
			if($this->getGender() == 'female') {
				$this->setPrefGender(1);
			}
		}

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


    /**
     * Set pref_gender
     *
     * @param integer $prefGender
     */
    public function setPrefGender($prefGender)
    {
        $this->pref_gender = $prefGender;
    }

    /**
     * Get pref_gender
     *
     * @return integer 
     */
    public function getPrefGender()
    {
        return $this->pref_gender;
    }

    /**
     * Set pref_age_range
     *
     * @param string $prefAgeRange
     */
    public function setPrefAgeRange($prefAgeRange)
    {
        $this->pref_age_range = $prefAgeRange;
    }

    /**
     * Get pref_age_range
     *
     * @return string 
     */
    public function getPrefAgeRange()
    {
        return $this->pref_age_range;
    }

    /**
     * Set birthday
     *
     * @param datetime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return datetime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set strangerlimit_time
     *
     * @param date $strangerlimitTime
     */
    public function setStrangerlimitTime($strangerlimitTime)
    {
        $this->strangerlimit_time = $strangerlimitTime;
    }

    /**
     * Get strangerlimit_time
     *
     * @return date 
     */
    public function getStrangerlimitTime()
    {
        return $this->strangerlimit_time;
    }

    /**
     * Set strangerlimit
     *
     * @param integer $strangerlimit
     */
    public function setStrangerlimit($strangerlimit)
    {
        $this->strangerlimit = $strangerlimit;
    }

    /**
     * Get strangerlimit
     *
     * @return integer 
     */
    public function getStrangerlimit()
    {
        return $this->strangerlimit;
    }

    /**
     * Set pictures
     *
     * @param Likeme\SystemBundle\Entity\Pictures $pictures
     * @return User
     */
    public function setPictures(\Likeme\SystemBundle\Entity\Pictures $pictures = null)
    {
        $this->pictures = $pictures;
        return $this;
    }

    /**
     * Get pictures
     *
     * @return Likeme\SystemBundle\Entity\Pictures 
     */
    public function getPictures()
    {
        return $this->pictures;
    }
}