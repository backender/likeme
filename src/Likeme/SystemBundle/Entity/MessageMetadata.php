<?php

namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ornicar\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

/**
* @ORM\Entity
* @ORM\Table(name="message_message_metadata")
*/
class MessageMetadata extends BaseMessageMetadata
{
/**
* @ORM\Id
* @ORM\Column(type="integer")
* @ORM\generatedValue(strategy="AUTO")
*/
protected $id;

/**
* @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\Message", inversedBy="metadata")
*/
protected $message;

/**
* @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\User")
*/
protected $participant;
}