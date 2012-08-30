<?php

namespace Likeme\SystemBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Likeme\SystemBundle\DependencyInjection\Compiler\RegisterStreamsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LikemeSystemBundle extends Bundle
{
	
	public function boot()
	{
		if ($this->container->has('likeme.gaufrette.stream_registry')) {
			/* @var $registry \Project\SiteBundle\Gaufrette\StreamRegistry */
			$registry = $this->container->get('likeme.gaufrette.stream_registry');
			$registry->register();
		}
	}
	
	/**
	 * @see Symfony\Component\HttpKernel\Bundle.Bundle::build()
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
	
		$container->addCompilerPass(new RegisterStreamsPass());
	}
	
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
