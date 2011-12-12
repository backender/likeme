<?php

namespace likeme\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeMessageBundle:Default:index.html.twig', array('name' => $name));
    }
}
