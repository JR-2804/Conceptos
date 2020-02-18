<?php

namespace AppBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * Class Comment
 * @package AppBundle\Entity\Blog
 */
class Comment
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
   * @ORM\Column(type="string")
   */
  private $email;
  /**
   * @ORM\Column(type="string")
   */
  private $text;
  /**
   * @ORM\Column(type="date")
   */
  private $date;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Blog\Post", inversedBy="comments")
   * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
   */
  private $post;

  public function getId()
  {
    return $this->id;
  }

  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setText($text)
  {
    $this->text = $text;

    return $this;
  }

  public function getText()
  {
    return $this->text;
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

  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }

  public function getDate()
  {
    return $this->date;
  }
}
