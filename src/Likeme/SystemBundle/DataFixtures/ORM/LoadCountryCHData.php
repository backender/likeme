<?php
namespace Likeme\SystemBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Likeme\SystemBundle\Entity\Location;

class LoadLocationData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

	public function getOrder() {
		return 1;
	}
	
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function load(ObjectManager $manager) {
		ini_set('memory_limit', '-1');
		$kernel = $this->container->get('kernel');
		$path = $kernel->locateResource('@LikemeSystemBundle/Resources/public/country/CH/CH.csv');

		$fp = fopen($path, "r");

		while (!feof($fp)) {
			$row = fgetcsv($fp, 4096, ",");

			$location = new Location();

			$location->setCountrycode($row[0]);
			$location->setPostalcode($row[1]);
			$location->setPlacename($row[2]);
			$location->setState($row[3]);
			$location->setStatecode($row[4]);
			$location->setProvince($row[5]);
			$location->setProvincecode($row[6]);
			$location->setCommunity($row[7]);
			$location->setCommunitycode($row[8]);
			$location->setLat($row[9]);
			$location->setLon($row[10]);
			$location->setAccuracy($row[11]);

			$manager->persist($location);

		}
		fclose($fp);
		$manager->persist($location);
		$manager->flush();

	}

}