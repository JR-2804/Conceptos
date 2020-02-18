<?php

namespace AppBundle\DTO;


use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class CommentDTO
{
  private $id;
  private $name;
  private $email;
  private $text;

  /**
   * @Recaptcha\IsTrue
   */
  public $recaptcha;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getText()
  {
    return $this->text;
  }

  public function setText($text)
  {
    $this->text = $text;
  }
}
