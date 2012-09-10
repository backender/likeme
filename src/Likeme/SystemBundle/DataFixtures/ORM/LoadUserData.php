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
		
		$testUser1 = new User();

		$testUser1->setUsername('TestUser1');
		$testUser1->setFirstname('Numrich');
		$testUser1->setLastname('Connie');
		$testUser1->setEmail('Numrich@Numrich.ch');
		$testUser1->setPassword('1234');
		$testUser1->setBirthday(new \DateTime('1990-01-20'));
		$testUser1->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser1->setLocation($testloc); 
		
		$em->persist($testUser1);
		
		
		$testUser2 = new User();
		
		$testUser2->setUsername('TestUser2');
		$testUser2->setFirstname('Heiko');
		$testUser2->setLastname('Wasser');
		$testUser2->setEmail('Heiko@Numrich.ch');
		$testUser2->setPassword('1234');
		$testUser2->setBirthday(new \DateTime('1960-02-15'));
		$testUser2->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser2->setLocation($testloc);

		$em->persist($testUser2);
		
		$testUser3 = new User();
		
		$testUser3->setUsername('TestUser3');
		$testUser3->setFirstname('Martina');
		$testUser3->setLastname('Hingisoderwieauimmer');
		$testUser3->setEmail('Martina@Numrich.ch');
		$testUser3->setPassword('1234');
		$testUser3->setBirthday(new \DateTime('1984-05-20'));
		$testUser3->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser3->setLocation($testloc);
		
		$em->persist($testUser3);
		
		$testUser4 = new User();
		
		$testUser4->setUsername('TestUser4');
		$testUser4->setFirstname('Sacha');
		$testUser4->setLastname('Blaser');
		$testUser4->setEmail('sacha@blaser.ch');
		$testUser4->setPassword('1234');
		$testUser4->setBirthday(new \DateTime('1994-07-01'));
		$testUser4->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser4->setLocation($testloc);
		
		$em->persist($testUser4);
		
		$testUser5 = new User();
		
		$testUser5->setUsername('TestUser5');
		$testUser5->setFirstname('Minka');
		$testUser5->setLastname('Sulaimanov');
		$testUser5->setEmail('minka@Sulaimanov.ch');
		$testUser5->setPassword('1234');
		$testUser5->setBirthday(new \DateTime('1996-08-13'));
		$testUser5->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser5->setLocation($testloc);
		
		$em->persist($testUser5);
		
		$testUser6 = new User();
		
		$testUser6->setUsername('TestUser6');
		$testUser6->setFirstname('Lola');
		$testUser6->setLastname('Ferrari');
		$testUser6->setEmail('lola@ferrari.ch');
		$testUser6->setPassword('1234');
		$testUser6->setBirthday(new \DateTime('1976-09-19'));
		$testUser6->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser6->setLocation($testloc);
		
		$em->persist($testUser6);
		
		$testUser7 = new User();
		
		$testUser7->setUsername('TestUser7');
		$testUser7->setFirstname('The');
		$testUser7->setLastname('Rock');
		$testUser7->setEmail('therock@thoughguy.ch');
		$testUser7->setPassword('1234');
		$testUser7->setBirthday(new \DateTime('1988-10-26'));
		$testUser7->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser7->setLocation($testloc);
		
		$em->persist($testUser7);
		
		$testUser8 = new User();
		
		$testUser8->setUsername('TestUser8');
		$testUser8->setFirstname('Michael');
		$testUser8->setLastname('Douglas');
		$testUser8->setEmail('michi@thoughguy.ch');
		$testUser8->setPassword('1234');
		$testUser8->setBirthday(new \DateTime('1955-01-28'));
		$testUser8->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser8->setLocation($testloc);
		
		$em->persist($testUser8);
		
		$testUser9 = new User();
		
		$testUser9->setUsername('TestUser9');
		$testUser9->setFirstname('Jennifer');
		$testUser9->setLastname('Lopez');
		$testUser9->setEmail('jenny@redlight.ch');
		$testUser9->setPassword('1234');
		$testUser9->setBirthday(new \DateTime('1979-04-04'));
		$testUser9->setGender('female');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser9->setLocation($testloc);
		
		$em->persist($testUser9);
		
		$testUser10 = new User();
		
		$testUser10->setUsername('TestUser10');
		$testUser10->setFirstname('Arnold');
		$testUser10->setLastname('Schwarzenegger');
		$testUser10->setEmail('arni@muckihaus.ch');
		$testUser10->setPassword('1234');
		$testUser10->setBirthday(new \DateTime('1969-12-04'));
		$testUser10->setGender('male');
		// Schlosswil Bern
		$testloc = $em->getRepository('LikemeSystemBundle:Location')->findOneById(8034);
		$testUser10->setLocation($testloc);
		
		$em->persist($testUser10);
		
		$em->flush();
		
		$this->addReference('TestUser1', $testUser1);
		$this->addReference('TestUser2', $testUser2);
		$this->addReference('TestUser3', $testUser3);
		$this->addReference('TestUser4', $testUser4);
		$this->addReference('TestUser5', $testUser5);
		$this->addReference('TestUser6', $testUser6);
		$this->addReference('TestUser7', $testUser7);
		$this->addReference('TestUser8', $testUser8);
		$this->addReference('TestUser9', $testUser9);
		$this->addReference('TestUser10', $testUser10);
	}

}