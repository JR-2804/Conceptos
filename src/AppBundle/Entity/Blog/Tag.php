<?php

namespace AppBundle\Entity\Blog;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Tag
 * @package AppBundle\Entity\Blog
 */
class Tag
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
    private $value;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Blog\Post", mappedBy="tags")
     * @ORM\JoinTable(name="post_tag")
     */
    private $posts;

    /**
     * Tag constructor.
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
     * Set value
     *
     * @param string $value
     *
     * @return Tag
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Blog\Post $post
     *
     * @return Tag
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
        return $this->value;
    }


}
