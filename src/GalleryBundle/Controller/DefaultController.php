<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{

    /**
     * Pages index
     * @return type
     */
    public function indexAction()
    {
        return $this->render('GalleryBundle:Default:index.html.twig');
    }


}

