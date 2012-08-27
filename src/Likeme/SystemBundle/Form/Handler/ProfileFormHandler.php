<?php

namespace Likeme\SystemBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ProfileFormHandler as BaseHandler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class ProfileFormHandler extends BaseHandler
{
	
 	public function process(UserInterface $user)
    {
    	
    	$request = $this->request;
    	$this->form->setData($user);
    		
    	if ($this->request->getMethod() == 'POST')
    	{
    		$this->form->bindRequest($this->request);
    		
    		if ($this->form->isValid())
    		{
    	 
    			$this->onSuccess($user);
    			return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));

    		}
    		
        	// Reloads the user to reset its username. This is needed when the
        	// username or password have been changed to avoid issues with the
        	// security layer.
        	$this->userManager->reloadUser($user);

    	}

        return false;
    }
    
    protected function onSuccess(UserInterface $user)
    {
    	$this->userManager->updateUser($user);
    }
}
