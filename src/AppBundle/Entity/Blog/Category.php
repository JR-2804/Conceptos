<?php

namespace AppBundle\Entity\Blog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="blog_category")
 * Class Category
 * @package AppBundle\Entity\Blog
 */
class Category
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Blog\Post", mappedBy="category")
     */
    private $posts;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Blog\Post $post
     *
     * @return Category
     */
    public function addPost(\AppBundle\Entity\Blog\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Blog\Post $post
     */
    public function removePost(\AppBundle\Entity\Blog\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    function __toString()
    {
        return $this->name;
    }


}
