<?php
namespace Likeme\SystemBundle\Form\Type;

use Likeme\SystemBundle\Form\DataTransformer\LocationToIdTransformer;

use Symfony\Component\Security\Core\SecurityContext;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class NextFormType extends AbstractType 
{

    public function getName()
    {
        return 'likeme_user_next';
    }
    
    public function __construct($options) {
    	$this->options = $options;
    }

	public function buildForm(FormBuilder $builder, array $options)
	{
		$user = $this->options['user'];
		$stranger = $this->options['stranger'];
		
        $builder
		->add('user', 'entity', array('class' => 'LikemeSystemBundle:User',
									  'query_builder' => function(EntityRepository $er) use ($user) {
									  return $er
									  ->createQueryBuilder('i')
 									  ->where("i.id = :user_id")
 									  ->setParameter('user_id', $user->getId())
 									  ;
									  }, 
									  'attr'=> array('style'=>'display:none')						  
		))
		->add('stranger', 'entity', array('class' => 'LikemeSystemBundle:User',
									  'query_builder' => function(EntityRepository $er) use ($stranger) {
									  return $er
									  ->createQueryBuilder('i')
 									  ->where("i.id = :stranger_id")
 									  ->setParameter('stranger_id', $stranger->getId())
 									  ;
									  }, 
									  'attr'=> array('style'=>'display:none')
		))
        ;
    }
}
