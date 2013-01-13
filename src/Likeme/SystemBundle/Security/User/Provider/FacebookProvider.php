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

  public function loadUserByUsername($username)
  {
    $user = $this->findUserByFbId($username);
    try {
  		$fbdata = $this->facebook->api('/me');

    } catch (FacebookApiException $e) {
      $fbdata = null;
    }

    if (!empty($fbdata)) {
      if (empty($user)) {
        $user = $this->userManager->createUser();
        $user->setEnabled(true);
        $user->setPassword('');
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
