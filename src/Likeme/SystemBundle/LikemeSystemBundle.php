<?php

namespace Likeme\SystemBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LikemeSystemBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
