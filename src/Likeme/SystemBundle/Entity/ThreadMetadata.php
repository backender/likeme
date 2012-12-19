<?php
namespace Likeme\SystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ornicar\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_thread_metadata")
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\Thread", inversedBy="metadata")
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Likeme\SystemBundle\Entity\User")
     */
    protected $participant;

}