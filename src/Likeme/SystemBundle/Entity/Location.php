<?php

namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likeme\SystemBundle\Entity\Location
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Likeme\SystemBundle\Entity\LocationRepository")
 */
class Location
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
     * @var string $countrycode
     *
     * @ORM\Column(name="countrycode", type="string", length=2, nullable=true)
     */
    private $countrycode;

    /**
     * @var string $postalcode
     *
     * @ORM\Column(name="postalcode", type="string", length=20, nullable=true)
     */
    private $postalcode;

    /**
     * @var string $placename
     *
     * @ORM\Column(name="placename", type="string", length=180, nullable=true)
     */
    private $placename;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=true)
     */
    private $state;

    /**
     * @var string $statecode
     *
     * @ORM\Column(name="statecode", type="string", length=20, nullable=true)
     */
    private $statecode;

    /**
     * @var string $province
     *
     * @ORM\Column(name="province", type="string", length=100, nullable=true)
     */
    private $province;

    /**
     * @var string $provincecode
     *
     * @ORM\Column(name="provincecode", type="string", length=20, nullable=true)
     */
    private $provincecode;

    /**
     * @var string $community
     *
     * @ORM\Column(name="community", type="string", length=100, nullable=true)
     */
    private $community;

    /**
     * @var string $communitycode
     *
     * @ORM\Column(name="communitycode", type="string", length=20, nullable=true)
     */
    private $communitycode;

    /**
     * @var decimal $lat
     *
     * @ORM\Column(name="lat", type="string", length=20, nullable=true)
     */
    private $lat;

    /**
     * @var decimal $lon
     *
     * @ORM\Column(name="lon", type="string", length=20, nullable=true)
     */
    private $lon;

    /**
     * @var integer $accuracy
     *
     * @ORM\Column(name="accuracy", type="integer", nullable=true)
     */
    private $accuracy;


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
     * Set countrycode
     *
     * @param string $countrycode
     */
    public function setCountrycode($countrycode)
    {
        $this->countrycode = $countrycode;
    }

    /**
     * Get countrycode
     *
     * @return string 
     */
    public function getCountrycode()
    {
        return $this->countrycode;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    }

    /**
     * Get postalcode
     *
     * @return string 
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set placename
     *
     * @param string $placename
     */
    public function setPlacename($placename)
    {
        $this->placename = $placename;
    }

    /**
     * Get placename
     *
     * @return string 
     */
    public function getPlacename()
    {
        return $this->placename;
    }

    /**
     * Set state
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set statecode
     *
     * @param string $statecode
     */
    public function setStatecode($statecode)
    {
        $this->statecode = $statecode;
    }

    /**
     * Get statecode
     *
     * @return string 
     */
    public function getStatecode()
    {
        return $this->statecode;
    }

    /**
     * Set province
     *
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set provincecode
     *
     * @param string $provincecode
     */
    public function setProvincecode($provincecode)
    {
        $this->provincecode = $provincecode;
    }

    /**
     * Get provincecode
     *
     * @return string 
     */
    public function getProvincecode()
    {
        return $this->provincecode;
    }

    /**
     * Set community
     *
     * @param string $community
     */
    public function setCommunity($community)
    {
        $this->community = $community;
    }

    /**
     * Get community
     *
     * @return string 
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * Set communitycode
     *
     * @param string $communitycode
     */
    public function setCommunitycode($communitycode)
    {
        $this->communitycode = $communitycode;
    }

    /**
     * Get communitycode
     *
     * @return string 
     */
    public function getCommunitycode()
    {
        return $this->communitycode;
    }

    /**
     * Set lat
     *
     * @param decimal $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return decimal 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param decimal $lon
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
    }

    /**
     * Get lon
     *
     * @return decimal 
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set accuracy
     *
     * @param integer $accuracy
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;
    }

    /**
     * Get accuracy
     *
     * @return integer 
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }
}