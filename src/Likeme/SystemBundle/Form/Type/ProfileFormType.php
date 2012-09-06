<?php

namespace Likeme\SystemBundle\Form\Type;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileFormType extends AbstractType implements ContainerAwareInterface
{

    public function getName()
    {
        return 'likeme_user_profile';
    }
    
    private $container;
    
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;

	}

	public function buildForm(FormBuilder $builder, array $options)
	{
		$gender_choices = array(
				0 => 'Beide',
				1 => 'Männer',
				2 => 'Frauen'
		);
		
		//get users location
		$user = $this->container->get('security.context')->getToken()->getUser();
		$location = $user->getLocation();
		
        $builder
        //->add('location', 'hidden', array('label' => 'Wohnort'))
        ->add('location', 'entity', array('class' => 'LikemeSystemBundle:Location', 
        								  'property' => 'id', 
        								  'query_builder' => function(EntityRepository $er) {
        														return $er->createQueryBuilder('u')
        																  ->where('u.id = :id')
        																  ->setParameter('id', $location->getId()); 
       														 },
		))
		->add('aboutme', 'textarea', array('label' => 'Über Mich'))
		->add('pref_gender', 'choice', array('choices' => $gender_choices))
		->add('pref_age_range', 'hidden')
        ;
    }
}
