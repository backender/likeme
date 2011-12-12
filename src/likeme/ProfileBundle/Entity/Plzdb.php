<?php

namespace likeme\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * likeme\ProfileBundle\Entity\Plzdb
 */
class Plzdb
{
    /**
     * @var integer $id
     */
    private $id;


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
     * @var string $country
     */
    private $country;

    /**
     * @var string $language
     */
    private $language;

    /**
     * @var string $iso2
     */
    private $iso2;

    /**
     * @var string $region1
     */
    private $region1;

    /**
     * @var string $region2
     */
    private $region2;

    /**
     * @var string $region3
     */
    private $region3;

    /**
     * @var string $region4
     */
    private $region4;

    /**
     * @var string $zip
     */
    private $zip;

    /**
     * @var string $city
     */
    private $city;

    /**
     * @var string $area1
     */
    private $area1;

    /**
     * @var string $area2
     */
    private $area2;

    /**
     * @var float $lat
     */
    private $lat;

    /**
     * @var float $lng
     */
    private $lng;

    /**
     * @var string $tz
     */
    private $tz;

    /**
     * @var string $utc
     */
    private $utc;

    /**
     * @var string $dst
     */
    private $dst;


    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set iso2
     *
     * @param string $iso2
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
    }

    /**
     * Get iso2
     *
     * @return string 
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set region1
     *
     * @param string $region1
     */
    public function setRegion1($region1)
    {
        $this->region1 = $region1;
    }

    /**
     * Get region1
     *
     * @return string 
     */
    public function getRegion1()
    {
        return $this->region1;
    }

    /**
     * Set region2
     *
     * @param string $region2
     */
    public function setRegion2($region2)
    {
        $this->region2 = $region2;
    }

    /**
     * Get region2
     *
     * @return string 
     */
    public function getRegion2()
    {
        return $this->region2;
    }

    /**
     * Set region3
     *
     * @param string $region3
     */
    public function setRegion3($region3)
    {
        $this->region3 = $region3;
    }

    /**
     * Get region3
     *
     * @return string 
     */
    public function getRegion3()
    {
        return $this->region3;
    }

    /**
     * Set region4
     *
     * @param string $region4
     */
    public function setRegion4($region4)
    {
        $this->region4 = $region4;
    }

    /**
     * Get region4
     *
     * @return string 
     */
    public function getRegion4()
    {
        return $this->region4;
    }

    /**
     * Set zip
     *
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set area1
     *
     * @param string $area1
     */
    public function setArea1($area1)
    {
        $this->area1 = $area1;
    }

    /**
     * Get area1
     *
     * @return string 
     */
    public function getArea1()
    {
        return $this->area1;
    }

    /**
     * Set area2
     *
     * @param string $area2
     */
    public function setArea2($area2)
    {
        $this->area2 = $area2;
    }

    /**
     * Get area2
     *
     * @return string 
     */
    public function getArea2()
    {
        return $this->area2;
    }

    /**
     * Set lat
     *
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set tz
     *
     * @param string $tz
     */
    public function setTz($tz)
    {
        $this->tz = $tz;
    }

    /**
     * Get tz
     *
     * @return string 
     */
    public function getTz()
    {
        return $this->tz;
    }

    /**
     * Set utc
     *
     * @param string $utc
     */
    public function setUtc($utc)
    {
        $this->utc = $utc;
    }

    /**
     * Get utc
     *
     * @return string 
     */
    public function getUtc()
    {
        return $this->utc;
    }

    /**
     * Set dst
     *
     * @param string $dst
     */
    public function setDst($dst)
    {
        $this->dst = $dst;
    }

    /**
     * Get dst
     *
     * @return string 
     */
    public function getDst()
    {
        return $this->dst;
    }
}