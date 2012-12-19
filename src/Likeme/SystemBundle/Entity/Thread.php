<?php

namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ornicar\MessageBundle\Entity\Thread as BaseThread;

/**
* @ORM\Entity
* @ORM\Table(name="message_thread")
*/
class Thread extends BaseThread
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\generatedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\User")
    */
    protected $createdBy;

    /**
    * @ORM\OneToMany(targetEntity="Likeme\SystemBundle\Entity\Message", mappedBy="thread")
    */
    protected $messages;

    /**
    * @ORM\OneToMany(targetEntity="Likeme\SystemBundle\Entity\ThreadMetadata", mappedBy="thread", cascade={"all"})
    */
    protected $metadata;

    public function __construct()
    {
        parent::__construct();

        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }


}