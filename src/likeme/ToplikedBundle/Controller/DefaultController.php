<?php

namespace likeme\ToplikedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeToplikedBundle:Default:index.html.twig', array('name' => $name));
    }
}
