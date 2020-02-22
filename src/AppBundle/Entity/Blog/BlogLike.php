<?php

namespace AppBundle\Entity\Blog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class BlogLike
 * @package AppBundle\Entity\Blog
 */
class BlogLike
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Blog\Post")
   * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
   */
  private $post;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  public function getId()
  {
    return $this->id;
  }

  public function setPost(\AppBundle\Entity\Blog\Post $post = null)
  {
    $this->post = $post;

    return $this;
  }

  public function getPost()
  {
    return $this->post;
  }

  public function setUser(\AppBundle\Entity\User $user = null)
  {
    $this->user = $user;

    return $this;
  }

  public function getUser()
  {
    return $this->user;
  }
}
