<?php

namespace Likeme\SystemBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileFormType extends AbstractType
{

    public function getName()
    {
        return 'likeme_user_profile';
    }

	public function buildForm(FormBuilder $builder, array $options)
	{
        $builder
        ->add('location', 'text', array('label' => false))
		->add('aboutme')
        ;
    }
}
