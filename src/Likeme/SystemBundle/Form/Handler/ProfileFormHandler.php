<?php

namespace Likeme\SystemBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ProfileFormHandler as BaseHandler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Form\Model\CheckPassword;

class ProfileFormHandler extends BaseHandler
{
	
 	public function process(UserInterface $user)
    {
    	//TODO: This is just FOS standard, make as we want to have
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user);

                return true;
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
