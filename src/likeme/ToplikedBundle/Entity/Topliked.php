<?php

namespace likeme\ToplikedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * likeme\ToplikedBundle\Entity\Topliked
 */
class Topliked
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
}