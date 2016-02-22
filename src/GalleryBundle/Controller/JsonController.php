<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data controller
 */
class JsonController extends Controller
{

    /**
     *
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $this->serializer = new Serializer([new ObjectNormalizer()], [ new JsonEncoder()]);
    }


    /**
     * Album list
     * @return Response
     */
    public function allAlbumsAction()
    {
        $albums = $this->getDoctrine()->getRepository('GalleryBundle:Album')
                ->createQueryBuilder('a')
                ->select('a')
                ->getQuery()
                ->getArrayResult();

        $response = new Response($this->serializer->serialize($albums, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * Get images for specified page
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param int                                      $albumId
     * @param int                                      $pageId
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function albumPageAction(Request $request, $albumId, $pageId = null)
    {

        $query = $this->getDoctrine()->getRepository('GalleryBundle:Image')
                ->createQueryBuilder('i')
                ->select('i')
                ->where('i.album = '.(int) $albumId);

        $data = [
            'album' => null,
            'currentPage' => null,
            'imagesTotal' => null,
            'pagesTotal' => null,
            'images' => null,
            'itemsPerPage' => 5,
        ];
        
        if (!is_null($pageId) && is_numeric($pageId)) {
            $pageId = (int) $pageId;
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query->getQuery(),
                $pageId,
                $data['itemsPerPage']
            );
            $images = $pagination->getItems();

            foreach ($images as $k => $item) {
                $images[$k] = [
                    'url' => $item->getUrl(),
                    'title' => $item->getTitle(),
                    'slug' => $item->getSlug(),
                    'description' => $item->getDescription(),
                ];
            }

            $total = count($query->getQuery()->getResult());
            $perPage = $pagination->getItemNumberPerPage();
            $currentPage = $pagination->getCurrentPageNumber();

            $data['album'] = $albumId;
            $data['currentPage'] = $currentPage;
            $data['imagesTotal'] = $total;
            $data['pagesTotal'] = $total / $perPage;
            $data['images'] = $images;
                        
        } else {

            $data['album'] = $albumId;
            $data['currentPage'] = 1;
            $data['imagesTotal'] = count($query->getQuery()->getResult());
            $data['pagesTotal'] = $data['imagesTotal']/$data['itemsPerPage'];
            $data['images'] = $query->setMaxResults(5)->getQuery()->getArrayResult();
        }

        $response = new Response($this->serializer->serialize([$data], 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
