<?php

namespace Likeme\SystemBundle\Form\Type;

use Likeme\SystemBundle\Form\DataTransformer\LocationToIdTransformer;

use Symfony\Component\Security\Core\SecurityContext;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MessageFormType extends AbstractType
{

    public function getName()
    {
        return 'likeme_chat_profile';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
            ->add('message', 'textarea', array('label' => 'Chat'))
        ;
    }
}