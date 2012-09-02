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
		$gender_choices = array(
				0 => 'Beide',
				1 => 'Männlich',
				2 => 'Weiblich'
		);
		
		$age_range_choices = array(
				'0'		=> 'Beliebiges Alter',
				'0-15'	=> '0-15',
				'16-20' => '16-20',
				'21-30' => '21-30',
				'30-45' => '30-45',
				'46+'	=> '46+'
		);
		
        $builder
        ->add('location', 'text', array('label' => 'Wohnort'))
		->add('aboutme', 'textarea', array('label' => 'Über Mich'))
		->add('pref_gender', 'choice', array('choices' => $gender_choices))
		->add('pref_age_range', 'choice', array('choices' => $age_range_choices))
        ;
    }
}
