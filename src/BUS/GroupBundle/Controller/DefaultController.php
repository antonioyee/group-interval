<?php

namespace BUS\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    public function indexAction(){
        return $this->render('BUSGroupBundle:Default:index.html.twig');
    }

}
