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
    	
        return $this->render('LikemeSystemBundle:Home:start.html.twig');
    }
    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/home", name="user_home")
     * @Template()
     */
    public function showAction()
    {
    	/**
    	 * 0. Requirements
    	 */
    	
    	// Get session
    	$session = $this->container->get('session');
    	$userService = $this->container->get('likeme.user.userservice');
    	
    	// Get EntityManager & ...
    	$em = $this->get('doctrine')->getEntityManager();
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	$request = $this->getRequest();
    	$strangers = $session->get('strangers');
    	
    	// Form names
    	$likeFormName = "likeme_user_like";
    	$nextFormName = "likeme_user_next";
    	
    	// Like/Next Entity
    	$likeEntity = new Like(); // Build like form and check request
    	$nextEntity = new Next(); // Build next form and check request
    	
    	
    	/**
    	 * 1. Check for post
    	 */
    	if ($request->getMethod() == 'POST') {
    		 
    		if ($request->request->has($likeFormName) || $request->request->has($nextFormName)) {

    			// Increase daily clicked
    			$user->increaseStrangerlimit();
    	
	    		// Handle request form and request stranger
	    		if ($request->request->has($likeFormName)){
	    			$formRequest = $request->request->get('likeme_user_like');
	    		} elseif ($request->request->has($nextFormName)) {
	    			$formRequest = $request->request->get('likeme_user_next');
	    		}
	    		
	    		if(!empty($formRequest)) {
	    			$request_stranger = $formRequest['stranger'];
	    			$request_stranger = $this->getDoctrine()->getRepository('LikemeSystemBundle:User')->findOneById($request_stranger);
	    		}
	    			
	    		$likeForm = $this->createForm(new LikeFormType(array('stranger' => $request_stranger, 'user' => $user)), $likeEntity);
	    		$nextForm = $this->createForm(new NextFormType(array('stranger' => $request_stranger, 'user' => $user)), $nextEntity);
	    	
	    		// Check form likeme_user_like (Likeme)
	    		if ($request->request->has($likeForm->getName())) {
	    			$likeForm->bindRequest($request);
	    	
	    			$em->persist($likeEntity);
	    			$em->flush();
	    	
	    			// Check if this is a match
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
	    		}
	    		 
	    		// Check form likeme_user_next (Next)
	    		if ($request->request->has($nextForm->getName())) {
	    			$nextForm->bindRequest($request);
	    			$em->persist($nextEntity);
	    			$em->flush();
	    		}
	    		
	    		// Unset the flushed stranger from session
	    		unset($strangers[0]);
	    		$strangers = array_splice($strangers,0);
	    		$session->set('strangers',$strangers);
	    		
	    		// Check if stranger array is empty now
    			$userService->checkStrangerSessionEmpty($strangers);
    		}
    	}
    	
    	
    	
    	/**
    	 * 2. Reached daily limit?
    	 */
    	if($userService->getMaxStranger() == 0) {
    		return $this->redirect($this->generateUrl('user_home_limit'));;
    	}
    	
    	
    	/**
    	 * 3. No strangers for criteria left
    	 */
    	
    	if(empty($strangers) || (!is_array($strangers))){
    		$strangers = $userService->getStranger();
    		if(!empty($strangers)){
    			shuffle($strangers);
    			$userService->setStrangers($strangers);
    		} else {
    			return $this->redirect($this->generateUrl('user_home_empty'));
    		}
    	}
    	
    	
		/**
		 * 4. Render Form
		 */

    	// Get the next stranger
		$stranger = $this->getDoctrine()
			->getRepository('LikemeSystemBundle:User')
			->find($strangers[0]);
			
		// Generate form
		$likeForm = $this->createForm(new LikeFormType(array('stranger' => $stranger, 'user' => $user)), $likeEntity);
		$nextForm = $this->createForm(new NextFormType(array('stranger' => $stranger, 'user' => $user)), $nextEntity);
	    	
	    // Get user matches    	
	    $userMatches = $userService->getMatches($user);
	    
	    // Return view with form
	    return array('stranger' => $stranger, 
	    			 'likeForm' => $likeForm->createView(), 
	    			 'nextForm' => $nextForm->createView(),
	    			 'userMatches' => $userMatches
	    			 );


    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/home/empty", name="user_home_empty")
     * @Template()
     */
    public function showEmptyAction()
    {
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get user matches
    	$userService = $this->container->get('likeme.user.userservice');
    	$userMatches = $userService->getMatches($user);
    	
    	return $this->render('LikemeSystemBundle:Home:empty.html.twig', array('userMatches' => $userMatches));
    }
    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/home/limit", name="user_home_limit")
     * @Template()
     */
    public function showLimitAction()
    {
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	 
    	// Get user matches
    	$userService = $this->container->get('likeme.user.userservice');
    	$userMatches = $userService->getMatches($user);
    	$strangerLimit = $userService->getDailyLikeCount();
    	 
    	return $this->render('LikemeSystemBundle:Home:limit.html.twig', array('userMatches' => $userMatches, 'strangerLimit' => $strangerLimit));
    }
    
}
