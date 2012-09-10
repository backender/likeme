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

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

	public function getOrder() {
		return 1;
	}
	
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function load(ObjectManager $manager) {
		
		
		$em = $this->container->get("doctrine")->getEntityManager();
		
		$testUser = new User();

		$testUser->setUsername('TestUser1');
		$testUser->setFirstname('Numrich');
		$testUser->setLastname('Connie');
		$testUser->setEmail('Connie@Numrich.ch');
		$testUser->setPassword('1234');
		$testUser->setBirthday(new \DateTime('1990-01-20'));
		$testUser->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser->setLocation($testloc); 

		
		$em->persist($testUser);
		
		$em->flush();
		
		$this->addReference('TestUser1', $testUser);

	}

}