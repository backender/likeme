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
    	
    	// Form names
    	$likeFormName = "likeme_user_like";
    	$nextFormName = "likeme_user_next";
    	
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get UserService
    	$userService = $this->container->get('likeme.user.userservice');
    	
    	// Get strangers from session
    	$session = $this->container->get('session');
    	$strangers = $session->get('strangers');
    	
    	if ($request->getMethod() == 'POST') {
    		if ($request->request->has($likeFormName) || $request->request->has($nextFormName)){
    			// Increase Stranger Limit
    			$user->increaseStrangerlimit();
    			
    			// Remove liked user from strangers array in session
    			if(count($strangers) > 1) {
    				unset($strangers[0]);
    				$strangers = array_splice($strangers,0);
    				$session->set('strangers',$strangers);
    			} else {
    				$session->set('empty', '1');
    			}
    		}
    	}
    	
    	if($session->get('empty') != 1) {
	    	
	    	// Like/Next Entity
	    	$likeEntity = new Like(); // Build like form and check request
	    	$nextEntity = new Next(); // Build next form and check request
	    	
	
			if ($request->getMethod() == 'POST') {
				
				// Handle request form and request stranger
				if ($request->request->has($likeFormName)){
					$formRequest = $request->request->get('likeme_user_like');
				}
				if ($request->request->has($nextFormName)) {
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
			}

		
			$stranger = $this->getDoctrine()
			->getRepository('LikemeSystemBundle:User')
			->find($strangers[0]); //TODO: set session array count
			
			// Get pictures from stranger
			$textExtension = $this->container->get('likeme.twig.extension');
			$strangerPictures = $textExtension->stranger_pictures($stranger);
			
			
			// Generate form with new stranger
			$likeForm = $this->createForm(new LikeFormType(array('stranger' => $stranger, 'user' => $user)), $likeEntity);
			$nextForm = $this->createForm(new NextFormType(array('stranger' => $stranger, 'user' => $user)), $nextEntity);
	    	
	    	// Get user matches    	
	    	$userMatches = $userService->getMatches($user);
	    	
	    	// Return view with form
	    	return array('stranger' => $stranger, 
	    				 'stranger_pictures' => $strangerPictures, 
	    				 'likeForm' => $likeForm->createView(), 
	    				 'nextForm' => $nextForm->createView(),
	    				 'userMatches' => $userMatches
	    				 );

    	} else {
    		return $this->redirect($this->generateUrl('user_home_empty'));
    	}
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
    
}
