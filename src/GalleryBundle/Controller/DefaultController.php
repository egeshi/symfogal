<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller for gallery
 */
class DefaultController extends Controller
{

    public function indexAction()
    {
        
        $conn = $this->get('database_connection');
        $albums = $conn->fetchAll("SELECT * FROM albums");
        
        return $this->render('GalleryBundle:Default:gallery.html.twig', ['albums'=>$albums]);
    }


    public function albumAction(Request $request)
    {
        die(__FILE__.":".__LINE__);
    }
}
