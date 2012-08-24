<?php

namespace Likeme\SystemBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{

    public function getName()
    {
        return 'likeme_user_profile';
    }

    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        $builder
        ->add('location')
		->add('aboutme')
        ;
    }
}