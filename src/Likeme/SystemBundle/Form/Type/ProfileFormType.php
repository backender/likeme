<?php

namespace Likeme\SystemBundle\Form\Type;

use Likeme\SystemBundle\Form\DataTransformer\LocationToIdTransformer;

use Symfony\Component\Security\Core\SecurityContext;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileFormType extends AbstractType 
{

    public function getName()
    {
        return 'likeme_user_profile';
    }
    
    public function __construct($options) {
    	$this->options = $options;
    }

	public function buildForm(FormBuilder $builder, array $options)
	{
		$entityManager = $this->options['em'];
		$transformer = new LocationToIdTransformer($entityManager);
		
		$gender_choices = array(
				0 => 'Beide',
				1 => 'Männer',
				2 => 'Frauen'
		);
		
		//get users location
		$locationData = $options['data']->getLocation();
		if (!empty($locationData)) {
			$location = $locationData->getId();
		} else {
			$location = null;
		}
		
        $builder
		->add($builder->create('location', 'text')->prependNormTransformer($transformer))
		->add('aboutme', 'textarea', array('label' => 'Über Mich'))
		->add('pref_gender', 'choice', array('choices' => $gender_choices))
		->add('pref_age_range', 'hidden')
        ;
    }
}
