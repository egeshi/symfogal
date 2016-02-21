<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default controller for gallery
 */
class DefaultController extends Controller
{

    /**
     * Gallery html index
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        $conn = $this->get('database_connection');
        $albums = $conn->fetchAll("SELECT * FROM albums");

        return $this->render('GalleryBundle:Default:gallery.html.twig', ['albums' => $albums]);
    }
}
