<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

class JsonController extends Controller
{

    /**
     *
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [ new JsonEncoder()]);
    }


    /**
     * Album list
     * @return Response
     */
    public function allAlbumsAction()
    {
        $albums = $this->getDoctrine()->getRepository('GalleryBundle:Album')
                ->createQueryBuilder('e')
                ->select('e')
                ->getQuery()
                ->getArrayResult();

        $response = new Response($this->serializer->serialize($albums, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
