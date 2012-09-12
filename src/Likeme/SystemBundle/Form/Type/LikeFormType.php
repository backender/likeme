<?php

namespace Likeme\SystemBundle\Form\Type;

use Likeme\SystemBundle\Form\DataTransformer\LocationToIdTransformer;

use Symfony\Component\Security\Core\SecurityContext;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LikeFormType extends AbstractType 
{

    public function getName()
    {
        return 'likeme_user_like';
    }

	public function buildForm(FormBuilder $builder, array $options)
	{
        $builder
		->add('user')
		->add('stranger')
        ;
    }
}
