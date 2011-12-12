<?php

namespace likeme\RandomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeRandomBundle:Default:index.html.twig', array('name' => $name));
    }
}
