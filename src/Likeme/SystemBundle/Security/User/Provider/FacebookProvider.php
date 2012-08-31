<?php

namespace Likeme\SystemBundle\Security\User\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use \BaseFacebook;
use \FacebookApiException;
use FOS\FacebookBundle\Security\Authentication\Provider\FacebookProvider as BaseProvider;

class FacebookProvider extends BaseProvider implements UserProviderInterface
{

  /**
   * @var \Facebook
   */
  protected $facebook;
  protected $userManager;
  protected $validator;
  protected $container;
  
  public function __construct(BaseFacebook $facebook, $userManager, $validator, ContainerInterface $container)
  {
    $this->facebook = $facebook;
    $this->userManager = $userManager;
    $this->validator = $validator;
    $this->container = $container;
    
    // If access token is expired
    $this->setAccessToken();
  }

  public function setAccessToken()
  {
  	$session = $this->container->get('session');
  	$access_token = $session->get('access_token');
  	
  	if ($access_token !== null) {
 	 	$access_token = $session->get('access_token');
  		$this->facebook->setAccessToken($access_token);
  	}
  	return true;
  }
  
  
  public function getFacebookObj()
  {
  	return $this->facebook;
  }
  
  public function supportsClass($class)
  {
    return $this->userManager->supportsClass($class);
  }

  public function findUserByFbId($fbId)
  {
    return $this->userManager->findUserBy(array('facebookID' => $fbId));
  }
  
	public function visited()
	{
		$session = $this->container->get('session');
  		$visited = $session->get('visited', array());
  		if(!in_array('visited', $visited)) {
  			
  			$visited[] = 'visited';
  			$session->set('visited', $visited);
  			return false;
  			
  		} else {
  			return true;
  		}
	}

  public function loadUserByUsername($username)
  {
  	
    $user = $this->findUserByFbId($username);
    try {
      	// TODO: We have to exclude this after login, so it won't load fb data on every page
      	if (self::visited() == false) {
    		$fbdata = $this->facebook->api('/me');
      	}
    } catch (FacebookApiException $e) {
      $fbdata = null;
    }

    if (!empty($fbdata)) {
      if (empty($user)) {
        $user = $this->userManager->createUser();
        $user->setEnabled(true);
        $user->setPassword('');
      }
      
      //Get location for user
      if(!empty($fbdata['location'])) {
	      $location = $this->container->get("likeme.facebook.location")->locationByFacebook($fbdata['location']['name']);
		  if ($location !== false) {
		  	$user->setLocation($location);
		  }
      }
      
      // TODO use http://developers.facebook.com/docs/api/realtime
      $user->setFBData($fbdata);
		
      
      if (count($this->validator->validate($user, 'Facebook'))) {
        // TODO: the user was found obviously, but doesnt match our expectations, do something smart
        throw new UsernameNotFoundException('The facebook user could not be stored');
      }
      $this->userManager->updateUser($user);
    }

    if (empty($user)) {
      throw new UsernameNotFoundException('The user is not authenticated on facebook');
    }

    return $user;
  }

  public function refreshUser(UserInterface $user)
  {
    if (!$this->supportsClass(get_class($user)) || !$user->getFacebookId()) {
      throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
    }

    return $this->loadUserByUsername($user->getFacebookId());
  }

}
