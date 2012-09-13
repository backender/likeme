<?php

namespace Likeme\SystemBundle\Controller;

use Likeme\SystemBundle\Form\Type\NextFormType;

use Likeme\SystemBundle\Form\Type\LikeFormType;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Likeme\SystemBundle\Entity\Like;
use Likeme\SystemBundle\Entity\Next;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		$response = new RedirectResponse($this->container->get('router')->generate('user_home'));
    		return $response;
    	}
    	
        return array();
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/home", name="user_home")
     * @Template()
     */
    public function showAction()
    {
    	// Get EntityManager
    	$em = $this->get('doctrine')->getEntityManager();
    	
    	//Get Request
    	$request = $this->getRequest();
    	
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get UserService
    	$userService = $this->container->get('likeme.user.userservice');
    	
    	// Get strangers from session
    	$session = $this->container->get('session');
    	$strangers = $session->get('strangers');
    	$stranger = $this->getDoctrine()
    	->getRepository('LikemeSystemBundle:User')
    	->find($strangers[0]); //TODO: set session array count

    	// Get pictures from stranger
    	$textExtension = $this->container->get('likeme.twig.extension');
    	$strangerPictures = $textExtension->stranger_pictures($stranger);
    	
    	// Build like form and check request
    	$likeEntity = new Like();
    	
    	$likeForm = $this->createForm(new LikeFormType(array('stranger' => $stranger, 'user' => $user)), $likeEntity);
    	$likeForm->bindRequest($request);
    	
    	if ($likeForm->isValid()) {
    		$em->persist($likeEntity);
    		$em->flush();
    		
    		$request_stranger = $likeForm->getData()->getStranger();
    		$matched = $userService->is_matched($user, $request_stranger);
    		
    		if ($matched == true) {
    			
    			$userLike = $this->getDoctrine()->getRepository('LikemeSystemBundle:Like')->findOneBy(array('user' => $user->getId(), 'stranger' => $request_stranger->getId()));
    			$strangerLike = $this->getDoctrine()->getRepository('LikemeSystemBundle:Like')->findOneBy(array('user' => $request_stranger->getId(), 'stranger' => $user->getId()));

    			$now = new \DateTime();
    			$userLike->setMatchedAt($now);
    			$strangerLike->setMatchedAt($now);
    			$em->persist($userLike);
    			$em->persist($strangerLike);
    			$em->flush();
    		}
    		
    		return $this->redirect($this->generateUrl('after_login'));
    	}
    	
    	// Build next form and check request
    	$nextEntity = new Next();
    	 
    	$nextForm = $this->createForm(new NextFormType(array('stranger' => $stranger, 'user' => $user)), $nextEntity);
    	$nextForm->bindRequest($request);
    	
    	if ($nextForm->isValid()) {
    		$em->persist($nextEntity);
    		$em->flush();
    		 
    		return $this->redirect($this->generateUrl('after_login'));
    	}
    	
    	// Get user matches    	
    	$userMatches = $userService->getMatches($user);
    	
    	// Return view with form
    	return array('stranger' => $stranger, 
    				 'stranger_pictures' => $strangerPictures, 
    				 'likeForm' => $likeForm->createView(), 
    				 'nextForm' => $nextForm->createView(),
    				 'userMatches' => $userMatches
    				 );
    }
    
}
