<?php

namespace GalleryBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonControllerTest extends WebTestCase
{
    public function testAlbumsindex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/json/albums');
    }
}
