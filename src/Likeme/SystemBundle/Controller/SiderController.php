<?php
namespace Likeme\SystemBundle\Controller;

use Likeme\SystemBundle\Form\Type\MessageFormType;
use Likeme\SystemBundle\Entity\Messages;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class SiderController extends Controller
{
	/**
	 * @Template()
	 */
	public function strangerLimitAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		$strangerLimit = $user->getStrangerlimit();
		
		$userService = $this->container->get('likeme.user.userservice');
		$dailyCount = $userService->getDailyLikeCount();
		
		return array('strangerLimit' => $dailyCount - $strangerLimit);
	}
	
	/**
	 * @Template()
	 */
	public function userMatchesAction()
	{
		// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get user matches
    	$userService = $this->container->get('likeme.user.userservice');
    	$userMatches = $userService->getMatches($user);
	
		return array('userMatches' => $userMatches);
	}
	
	/**
	 * @Template()
	 */
	public function newMessageAction($stranger, $user) 
	{	
		$em = $this->get('doctrine')->getEntityManager();
		
		$message = new Messages();
		$message->setUserSend($user);
		$message->setUserReceive($stranger);
		$messageForm = $this->createForm(new MessageFormType(array('em' => $em,)), $message);
	
		return array(
				'messageForm' => $messageForm->createView(),
				'user' => $user,
				'stranger' => $stranger,
				);
	}
}