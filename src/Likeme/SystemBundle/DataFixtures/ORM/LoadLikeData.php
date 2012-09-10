<?php
namespace Likeme\SystemBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Likeme\SystemBundle\Entity\Like;

class LoadLikeData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

	public function getOrder() {
		return 2;
	}
	
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function load(ObjectManager $manager) {
		
		$userManager = $this->container->get('fos_user.user_manager');
		$user1 = $userManager->findUserByUsername('1440552145');
		$user2 = $userManager->findUserByUsername('1473555091');
		
		$like1 = new Like();
		$like1->setUser($user1);
		$like1->setStranger($user2);
		$like1->setCreatedAt(new \DateTime());
		
		$manager->persist($like1);
		
		$manager->flush();
		
		$this->addReference('like1', $like1);

	}

}
