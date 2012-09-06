<?php
namespace Likeme\SystemBundle\Form\DataTransformer;
use Likeme\SystemBundle\Entity\Location;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

class LocationToIdTransformer implements DataTransformerInterface {
	/**
	 * @var ObjectManager
	 */
	private $om;

	/**
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om) {
		$this->om = $om;
	}

	/**
	 * Transform an object (location) to a string (id)
	 * 
	 * @param Location|null $location
	 * @return $string
	 */
	public function transform($location) {
		if (null === $location) {
			return "";
		}

		return $location->getId();
	}

	/**
	 * Transform a string (id) to an object (location)
	 * 
	 * @param string $id
	 * @return Location|null
	 * @throws TransformationFailedException if object (location) is not found.
	 */
	public function reverseTransform($id) {
		if (!$id) {
			return null;
		}

		$id = $this->om->getRepository('LikemeSystemBundle:Location')
				->find($id)
		//->findOneBy(array('id' => $id))
		;

		if (null === $id) {
			throw new TransformationFailedException(
					sprintf('Ortschaft mit ID "%s" existiert nicht!', $id));
		}

		return $id;
	}

}
