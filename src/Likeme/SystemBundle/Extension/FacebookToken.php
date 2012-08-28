<?php
namespace Likeme\SystemBundle\Extension;



class FacebookToken { 

	public function update ($my_url, $code = null) {
		//Get User Facebook Profile Pictures
		$app_id = '100806270069332';
		$app_secret = '98c29a3bd105813b3308e24a404d23f1';
		
		// Retrieving a valid access token.
		$dialog_url= "https://www.facebook.com/dialog/oauth?"
		. "client_id=". $app_id
		. "&redirect_uri=" . urlencode($my_url);
		echo("<script> top.location.href='" . $dialog_url
				. "'</script>");
		
		// If we requested a new access token from Facebbok we get a code
		if($code !== null) {
				
			// If we get a code, it means that we have re-authed the user
			//and can get a valid access_token.
			if (isset($code)) {
				$token_url="https://graph.facebook.com/oauth/access_token?client_id="
				. $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $app_secret
				. "&code=" . $code . "&display=popup";
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				$access_token = $params['access_token'];
				
				// Create our Application instance (replace this with your appId and secret).
				$facebook = new \Facebook(array(
						'appId'  => $app_id,
						'secret' => $app_secret
				));
				
				$facebook->setAccessToken($access_token);
			}
		}
		
	}

}
