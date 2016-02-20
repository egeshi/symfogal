<?php

namespace GalleryBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use GalleryBundle\Entity\Album;
use Cocur\Slugify\Slugify;

/**
 * @author Antony Repin <egeshi@gmail.com>
 */
class LoadAlbumData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Maximum amount of images in album
     *
     * @var int
     */
    protected $max = 5;

    /**
     * Create fixtures for `albums` table
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $slugify = new Slugify();

        foreach (range(1, 5) as $idx) {

            $album = new Album();
            $album->setTitle("Album ".$idx);
            $slug = $slugify->slugify($album->getTitle());
            $album->setSlug($slug);

            if ($idx > 1) {
                $this->max = 10;
            }

            $album->setMax($this->max);
            
            $this->addReference($slug, $album);

            $manager->persist($album);
            $manager->flush();
        }
    }


    public function getOrder()
    {
        return 1;
    }
}
