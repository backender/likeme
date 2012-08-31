<?php

namespace Likeme\SystemBundle\Gaufrette;

use \AmazonS3 as Base;

class AmazoneWithoutSSL extends Base
{
	public function setSSL(){
		$this->ssl_verification = false;
	}
}