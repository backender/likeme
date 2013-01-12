<?php

namespace Likeme\SystemBundle\Controller;

use Likeme\SystemBundle\Form\Type\MessageFormType;

use Likeme\SystemBundle\Entity\Messages;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Doctrine\Tests\Common\Annotations\Fixtures\Annotation\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/messages")
 * @Method("post")
 */
class MessageController extends Controller {

    /**
     * @Route("/create", name="message_create")
     */
    public function createAction()
    {
        $em = $this->get('doctrine')->getEntityManager();

        $entity  = new Messages();
        $request = $this->getRequest();
        $form 	 = $this->createForm(new MessageFormType(), $entity);
        $form->bindRequest($request);

        if($form->isValid()) {
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('home'));
    }

}