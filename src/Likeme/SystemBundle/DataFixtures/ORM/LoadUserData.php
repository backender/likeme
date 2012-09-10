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
			
		
		$testUser11 = new User();
		
		$testUser11->setUsername('TestUser11');
		$testUser11->setFirstname('Ernst');
		$testUser11->setLastname('Marti');
		$testUser11->setEmail('Ernst@Marty.ch');
		$testUser11->setPassword('1234');
		$testUser11->setBirthday(new \DateTime('1985-04-05'));
		$testUser11->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser11->setLocation($testloc);
	
		$em->persist($testUser11);
		
		
		
		$testUser12 = new User();
		
		$testUser12->setUsername('TestUser12');
		$testUser12->setFirstname('Erna');
		$testUser12->setLastname('Oberholzer');
		$testUser12->setEmail('Erna@Oberholzer.ch');
		$testUser12->setPassword('1234');
		$testUser12->setBirthday(new \DateTime('1992-12-24'));
		$testUser12->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser12->setLocation($testloc);
		
		$em->persist($testUser12);
		
		
		
		$testUser13 = new User();
		
		$testUser13->setUsername('TestUser13');
		$testUser13->setFirstname('Ivana');
		$testUser13->setLastname('Lahdislochov');
		$testUser13->setEmail('Ivana@Lahdislochov.ch');
		$testUser13->setPassword('1234');
		$testUser13->setBirthday(new \DateTime('1991-06-13'));
		$testUser13->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser13->setLocation($testloc);
		
		$em->persist($testUser13);
		
		
		
		$testUser14 = new User();
		
		$testUser14->setUsername('TestUser14');
		$testUser14->setFirstname('Alain');
		$testUser14->setLastname('Sutter');
		$testUser14->setEmail('Alain@Sutter.ch');
		$testUser14->setPassword('1234');
		$testUser14->setBirthday(new \DateTime('1982-01-17'));
		$testUser14->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser14->setLocation($testloc);
		
		$em->persist($testUser14);
		
		
		
		$testUser15 = new User();
		
		$testUser15->setUsername('TestUser15');
		$testUser15->setFirstname('Ariella');
		$testUser15->setLastname('Käslin');
		$testUser15->setEmail('Ariella@Käslin.ch');
		$testUser15->setPassword('1234');
		$testUser15->setBirthday(new \DateTime('1987-05-03'));
		$testUser15->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser15->setLocation($testloc);
		
		
		
		$testUser16 = new User();
		
		$testUser16->setUsername('TestUser16');
		$testUser16->setFirstname('Rainer');
		$testUser16->setLastname('Calmund');
		$testUser16->setEmail('Rainer@Calmund.ch');
		$testUser16->setPassword('1234');
		$testUser16->setBirthday(new \DateTime('1994-08-23'));
		$testUser16->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser16->setLocation($testloc);
		
		
		
		$testUser17 = new User();
		
		$testUser17->setUsername('TestUser17');
		$testUser17->setFirstname('Daniela');
		$testUser17->setLastname('Hammerhart');
		$testUser17->setEmail('Daniela@Hammerhart.ch');
		$testUser17->setPassword('1234');
		$testUser17->setBirthday(new \DateTime('1993-04-29'));
		$testUser17->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser17->setLocation($testloc);
		
		$em->persist($testUser17);
		
		
		
		$testUser18 = new User();
		
		$testUser18->setUsername('TestUser18');
		$testUser18->setFirstname('Fiona');
		$testUser18->setLastname('Hefti');
		$testUser18->setEmail('Fiona@Hefti.ch');
		$testUser18->setPassword('1234');
		$testUser18->setBirthday(new \DateTime('1989-10-14'));
		$testUser18->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser18->setLocation($testloc);
		
		$em->persist($testUser18);
		
		
		
		$testUser19 = new User();
		
		$testUser19->setUsername('TestUser19');
		$testUser19->setFirstname('Ueli');
		$testUser19->setLastname('Steck');
		$testUser19->setEmail('Ueli@Steck.ch');
		$testUser19->setPassword('1234');
		$testUser19->setBirthday(new \DateTime('1990-02-14'));
		$testUser19->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser19->setLocation($testloc);
		
		$em->persist($testUser19);
		
		
		
		$testUser20 = new User();
		
		$testUser20->setUsername('TestUser20');
		$testUser20->setFirstname('Amanda');
		$testUser20->setLastname('Ammann');
		$testUser20->setEmail('Amanda@Ammann.ch');
		$testUser20->setPassword('1234');
		$testUser20->setBirthday(new \DateTime('1988-11-07'));
		$testUser20->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser20->setLocation($testloc);
		
		$em->persist($testUser20);
		
		$em->flush();
		
			$this->addReference('TestUser11', $testUser11);
			$this->addReference('TestUser12', $testUser12);
			$this->addReference('TestUser13', $testUser13);
			$this->addReference('TestUser14', $testUser14);
			$this->addReference('TestUser15', $testUser15);
			$this->addReference('TestUser16', $testUser16);
			$this->addReference('TestUser17', $testUser17);
			$this->addReference('TestUser18', $testUser18);
			$this->addReference('TestUser19', $testUser19);
			$this->addReference('TestUser20', $testUser20);

	}

}