<?php

namespace GalleryBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager;
use GalleryBundle\Entity\Image;
use Faker;
use Cocur\Slugify\Slugify;

/**
 * @author Antony Repin <egeshi@gmail.com>
 */
class LoadImageData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * All possible categories
     * @var array
     */
    protected $categories = [
        'abstract',
        'animals',
        'business',
        'cats',
        'city',
        'food',
        'nightlife',
        'fashion',
        'people',
        'nature',
        'sports',
        'technics',
        'transport'
    ];

    /**
     * Selected categories
     * @var array
     */
    protected $selectedCategories = [];

    /**
     * Create fixtures for `images` table
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();
        $slugify = new Slugify();

        foreach (range(1, 5) as $aIdx) {

            $album = $this->getReference('album-'.$aIdx);
            $max = $album->getMax();

            if (count($this->selectedCategories) == count($this->categories)) {
                $this->selectedCategories = [];
            }

            foreach (range(1, $max) as $iIdx) {
                $image = new Image();
                $title = $faker->sentence(5);
                $image->setTitle($title);
                $slug = $slugify->slugify($title);
                $image->setSlug($slug);
                $image->setDescription($faker->paragraph());
                $image->setUrl($faker->imageUrl(640, 480, $this->categories[rand(0, count($this->categories) - 1)], true));
                $image->setAlbum($album);

                $manager->persist($image);
                $manager->flush();
            }
        }
    }


    /**
     *
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
