<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

include __DIR__.'/../Utils/simple_html_dom.php';
use AppBundle\utils\DOM;

/**
 * PromotionEmail
 *
 * @ORM\Table(name="promotion_email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromotionEmailRepository")
 * @Vich\Uploadable()
 */
class PromotionEmail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="emails", type="text")
     */
    private $emails;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryPicture", type="string", length=255)
     */
    private $primaryPicture;

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="primaryPicture")
     */
    private $primaryPictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="secundaryPicture1", type="string", length=255)
     */
    private $secundaryPicture1;

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="secundaryPicture1")
     */
    private $secundaryPictureFile1;

    /**
     * @var string
     *
     * @ORM\Column(name="secundaryPicture2", type="string", length=255)
     */
    private $secundaryPicture2;

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="secundaryPicture2")
     */
    private $secundaryPictureFile2;

    /**
     * @var string
     *
     * @ORM\Column(name="secundaryPicture3", type="string", length=255)
     */
    private $secundaryPicture3;

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="secundaryPicture3")
     */
    private $secundaryPictureFile3;

    /**
     * @var string
     *
     * @ORM\Column(name="tercearyPicture", type="string", length=255)
     */
    private $tercearyPicture;

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="tercearyPicture")
     */
    private $tercearyPictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryContentTitle",  type="string", length=255, nullable=true)
     */
    private $primaryContentTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryContent", type="text", nullable=true)
     */
    private $primaryContent;

    /**
     * @var string
     *
     * @ORM\Column(name="secundaryContentTitle",  type="string", length=255, nullable=true)
     */
    private $secundaryContentTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="secundaryContent", type="text", nullable=true)
     */
    private $secundaryContent;

    /**
     * @var string
     *
     * @ORM\Column(name="tercearyContentTitle",  type="string", length=255, nullable=true)
     */
    private $tercearyContentTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="tercearyContent", type="text", nullable=true)
     */
    private $tercearyContent;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return int
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
     * @return PromotionEmail
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
     * Set emails
     *
     * @param string $emails
     *
     * @return PromotionEmail
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * Get emails
     *
     * @return string
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Set primaryPicture
     *
     * @param string $primaryPicture
     *
     * @return PromotionEmail
     */
    public function setPrimaryPicture($primaryPicture)
    {
        $this->primaryPicture = $primaryPicture;

        return $this;
    }

    /**
     * Get primaryPicture
     *
     * @return string
     */
    public function getPrimaryPicture()
    {
        return $this->primaryPicture;
    }

    /**
     * Get primaryPicture
     *
     * @return string
     */
    public function getSecundaryPicture1()
    {
        return $this->secundaryPicture1;
    }

    /**
     * Get secundaryPicture
     *
     * @return string
     */
    public function getSecundaryPicture2()
    {
        return $this->secundaryPicture2;
    }

    /**
     * Get secundaryPicture
     *
     * @return string
     */
    public function getSecundaryPicture3()
    {
        return $this->secundaryPicture3;
    }

    /**
     * Set secundaryPicture
     *
     * @param string $secundaryPicture
     *
     * @return PromotionEmail
     */
    public function setSecundaryPicture1($secundaryPicture1)
    {
        $this->secundaryPicture1 = $secundaryPicture1;

        return $this;
    }

    /**
     * Set secundaryPicture
     *
     * @param string $secundaryPicture
     *
     * @return PromotionEmail
     */
    public function setSecundaryPicture2($secundaryPicture2)
    {
        $this->secundaryPicture2 = $secundaryPicture2;

        return $this;
    }
    /**
     * Set secundaryPicture
     *
     * @param string $secundaryPicture
     *
     * @return PromotionEmail
     */
    public function setSecundaryPicture3($secundaryPicture3)
    {
        $this->secundaryPicture3 = $secundaryPicture3;

        return $this;
    }

    /**
     * Set tercearyPicture
     *
     * @param string $tercearyPicture
     *
     * @return PromotionEmail
     */
    public function setTercearyPicture($tercearyPicture)
    {
        $this->tercearyPicture = $tercearyPicture;

        return $this;
    }

    /**
     * Get tercearyPicture
     *
     * @return string
     */
    public function getTercearyPicture()
    {
        return $this->tercearyPicture;
    }

    /**
     * Get primaryContentTitle
     *
     * @return string
     */
    public function getPrimaryContentTitle()
    {
        return $this->primaryContentTitle;
    }

    /**
     * Set primaryContentTitle
     *
     * @param string $primaryContent
     *
     * @return PromotionEmail
     */
    public function setPrimaryContentTitle($primaryContentTitle)
    {
        $this->primaryContentTitle = $primaryContentTitle;

        return $this;
    }

    /**
     * Set primaryContent
     *
     * @param string $primaryContent
     *
     * @return PromotionEmail
     */
    public function setPrimaryContent($primaryContent)
    {
        $this->primaryContent = $primaryContent;

        return $this;
    }


    public function getPrimaryContentReal(){
        $html =  DOM\str_get_html($this->primaryContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product)
            $product->outertext = '';
        return $html;
    }

    public function getSecundaryContentReal(){
        $html =  DOM\str_get_html($this->secundaryContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product)
            $product->outertext = '';
        return $html;
    }

    public function getTercearyContentReal(){
        $html =  DOM\str_get_html($this->tercearyContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product)
            $product->outertext = '';
        return $html;
    }

    public function getAllProducts(){
        $products = [];

        $html =  DOM\str_get_html($this->primaryContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product){
            $products[] = $product->getAttribute('data-product');
        }
        $html =  DOM\str_get_html($this->secundaryContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product){
            $products[] = $product->getAttribute('data-product');
        }
        $html =  DOM\str_get_html($this->tercearyContent);
        $productsHTML =  $html->find('.ProductMarker');
        foreach($productsHTML as $product){
            $products[] = $product->getAttribute('data-product');
        }
        return $products;
    }


    /**
     * Get primaryContent
     *
     * @return string
     */
    public function getPrimaryContent()
    {
        return $this->primaryContent;
    }

    /**
     * Get secundaryContentTitle
     *
     * @return string
     */
    public function getSecundaryContentTitle()
    {
        return $this->secundaryContentTitle;
    }

    /**
     * Set primaryContentTitle
     *
     * @param string $primaryContent
     *
     * @return PromotionEmail
     */
    public function setSecundaryContentTitle($secundaryContentTitle)
    {
        $this->secundaryContentTitle = $secundaryContentTitle;

        return $this;
    }

    /**
     * Set secundaryContent
     *
     * @param string $secundaryContent
     *
     * @return PromotionEmail
     */
    public function setSecundaryContent($secundaryContent)
    {
        $this->secundaryContent = $secundaryContent;

        return $this;
    }

    /**
     * Get secundaryContent
     *
     * @return string
     */
    public function getSecundaryContent()
    {
        return $this->secundaryContent;
    }


    /**
     * Get tercearyContentTitle
     *
     * @return string
     */
    public function getTercearyContentTitle()
    {
        return $this->tercearyContentTitle;
    }

    /**
     * Set tercearyContentTitle
     *
     * @param string $primaryContent
     *
     * @return PromotionEmail
     */
    public function setTercearyContentTitle($tercearyContentTitle)
    {
        $this->tercearyContentTitle = $tercearyContentTitle;

        return $this;
    }

    /**
     * Set tercearyContent
     *
     * @param string $tercearyContent
     *
     * @return PromotionEmail
     */
    public function setTercearyContent($tercearyContent)
    {
        $this->tercearyContent = $tercearyContent;

        return $this;
    }

    /**
     * Get tercearyContent
     *
     * @return string
     */
    public function getTercearyContent()
    {
        return $this->tercearyContent;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return PromotionEmail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPictureFile()
    {
        return $this->primaryPictureFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setPrimaryPictureFile($primaryPictureFile)
    {
        $this->primaryPictureFile = $primaryPictureFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($primaryPictureFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getSecundaryPictureFile1()
    {
        return $this->secundaryPictureFile1;
    }

    /**
     * @return mixed
     */
    public function getSecundaryPictureFile2()
    {
        return $this->secundaryPictureFile2;
    }

    /**
     * @return mixed
     */
    public function getSecundaryPictureFile3()
    {
        return $this->secundaryPictureFile3;
    }

    /**
     * @param mixed $imageFile
     */
    public function setSecundaryPictureFile1($secundaryPictureFile1)
    {
        $this->secundaryPictureFile1 = $secundaryPictureFile1;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($secundaryPictureFile1) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @param mixed $imageFile
     */
    public function setSecundaryPictureFile2($secundaryPictureFile2)
    {
        $this->secundaryPictureFile2 = $secundaryPictureFile2;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($secundaryPictureFile2) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @param mixed $imageFile
     */
    public function setSecundaryPictureFile3($secundaryPictureFile3)
    {
        $this->secundaryPictureFile3 = $secundaryPictureFile3;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($secundaryPictureFile3) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getTercearyPictureFile()
    {
        return $this->tercearyPictureFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setTercearyPictureFile($tercearyPictureFile)
    {
        $this->tercearyPictureFile = $tercearyPictureFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($tercearyPictureFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

}

