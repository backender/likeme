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
use Likeme\SystemBundle\Entity\User;

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
		$em = $this->container->get("doctrine")->getEntityManager();
		
		$like1 = new Like();
		$like1->setUser($this->getReference('TestUser1'));
		$like1->setStranger($this->getReference('TestUser1'));
		$like1->setCreatedAt(new \DateTime());
		
		$manager->persist($like1);
		
		$manager->flush();
		
		$this->addReference('like1', $like1);

	}

}
