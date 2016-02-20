<?php

namespace GalleryBundle\Entity;

/**
 * @author Antony Repin <egeshi@gmail.com>
 */
class Album
{

    protected $title;
    protected $slug;
    protected $max;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Album
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }


    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set max
     *
     * @param integer $max
     *
     * @return Album
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }


    /**
     * Get max
     *
     * @return integer
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * Add image
     *
     * @param \GalleryBundle\Entity\Image $image
     *
     * @return Album
     */
    public function addImage(\GalleryBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }


    /**
     * Remove image
     *
     * @param \GalleryBundle\Entity\Image $image
     */
    public function removeImage(\GalleryBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }


    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
