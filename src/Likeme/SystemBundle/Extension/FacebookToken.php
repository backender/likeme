<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FacebookToken { 
	
	private $container;

	public function __construct($container) {
		$this->container = $container;
	}
	
	public function update ($my_url, $code = null) {
		// Get FOSFacebookService
		$fosfbservice = $this->container->get('my.facebook.user');
		// Create our Application instance (replace this with your appId and secret).
		$facebook = $fosfbservice->getFacebookObj();
		
		// If we requested a new access token from Facebbok we get a code
		if($code !== null) {
			// If we get a code, it means that we have re-authed the user
			//and can get a valid access_token.
			if (isset($code)) {
				$token_url="https://graph.facebook.com/oauth/access_token?client_id="
				. $facebook->getAppId() . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $facebook->getApiSecret()
				. "&code=" . $code . "&display=popup";
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				$access_token = $params['access_token'];
				
				// Save access token in session for FacebookProvider.php
				$session = $this->container->get('session');
				$session->set('access_token', $access_token);

			}
		} else {
			// Retrieving a valid access token.
			$dialog_url= "https://www.facebook.com/dialog/oauth?"
			. "client_id=". $facebook->getAppId()
			. "&redirect_uri=" . urlencode($my_url);
			echo("<script> top.location.href='" . $dialog_url
					. "'</script>");
		}
		
	}

}
