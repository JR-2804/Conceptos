<?php

namespace AppBundle\Entity\Blog;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @Vich\Uploadable()
 * Class Post
 * @package AppBundle\Entity\Blog
 */
class Post
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\Column(type="date")
   */
  private $createdDate;
  /**
   * @ORM\Column(type="string")
   */
  private $title;
  /**
   * @ORM\Column(type="text")
   */
  private $summary;
  /**
   * @ORM\Column(type="text")
   */
  private $body;
  /**
   * @ORM\Column(type="integer")
   */
  private $visitCount;
  /**
   * @ORM\Column(type="string")
   */
  private $path;
  /**
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Blog\Tag", inversedBy="posts")
   */
  private $tags;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Blog\Category", inversedBy="posts")
   * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
   */
  private $category;
  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @ORM\Column(type="string", length=255)
   */
  private $image;
  /**
   * @Vich\UploadableField(mapping="blog_images", fileNameProperty="image")
   */
  private $imageFile;
  /**
   * @ORM\Column(type="datetime")
   */
  private $updatedAt;
  /**
   * @ORM\Column(type="boolean")
   */
  private $promoted;
  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Blog\Comment", mappedBy="post")
   */
  private $comments;
  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Blog\BlogLike", mappedBy="post")
   */
  private $likes;

  public function __construct()
  {
    $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->likes = new ArrayCollection();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setCreatedDate($createdDate)
  {
    $this->createdDate = $createdDate;

    return $this;
  }

  public function getCreatedDate()
  {
    return $this->createdDate;
  }

  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setSummary($summary)
  {
    $this->summary = $summary;

    return $this;
  }

  public function getSummary()
  {
    return $this->summary;
  }

  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function setVisitCount($visitCount)
  {
    $this->visitCount = $visitCount;

    return $this;
  }

  public function getVisitCount()
  {
    return $this->visitCount;
  }

  public function setPath($path)
  {
    $this->path = $path;

    return $this;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function addTag(\AppBundle\Entity\Blog\Tag $tag)
  {
    $this->tags[] = $tag;

    return $this;
  }

  public function removeTag(\AppBundle\Entity\Blog\Tag $tag)
  {
    $this->tags->removeElement($tag);
  }

  public function getTags()
  {
    return $this->tags;
  }

  public function setCategory(\AppBundle\Entity\Blog\Category $category = null)
  {
    $this->category = $category;

    return $this;
  }

  public function getCategory()
  {
    return $this->category;
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

  public function setImage($image)
  {
    $this->image = $image;

    return $this;
  }

  public function getImage()
  {
    return $this->image;
  }

  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  public function getImageFile()
  {
    return $this->imageFile;
  }

  public function setImageFile($imageFile)
  {
    $this->imageFile = $imageFile;

    // VERY IMPORTANT:
    // It is required that at least one field changes if you are using Doctrine,
    // otherwise the event listeners won't be called and the file is lost
    if ($imageFile) {
      // if 'updatedAt' is not defined in your entity, use another property
      $this->updatedAt = new \DateTime('now');
    }
  }

  public function setPromoted($promoted)
  {
    $this->promoted = $promoted;

    return $this;
  }

  public function getPromoted()
  {
    return $this->promoted;
  }

  public function addComment(\AppBundle\Entity\Blog\Comment $comment)
  {
    $this->comments[] = $comment;

    return $this;
  }

  public function removeComment(\AppBundle\Entity\Blog\Comment $comment)
  {
    $this->comments->removeElement($comment);
  }

  public function getComments()
  {
    return $this->comments;
  }

  public function addLike(\AppBundle\Entity\Blog\BlogLike $like)
  {
    $this->likes[] = $like;

    return $this;
  }

  public function removeLike(\AppBundle\Entity\Blog\BlogLike $like)
  {
    $this->likes->removeElement($like);
  }

  public function getLikes()
  {
    return $this->likes;
  }

  function __toString()
  {
    return $this->title;
  }
}
