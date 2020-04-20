<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use PhpOption\Tests\Repository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

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
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="tag_user", type="text")
     */
    private $tagUser;

    /**
     * Set tagUser
     *
     * @param string $tagUser
     *
     * @return PromotionEmail
     */
    public function setTagUser($tagUser)
    {
        $this->tagUser = $tagUser;

        return $this;
    }

    /**
     * Get tagUser
     *
     * @return string
     */
    public function getTagUser()
    {
        return $this->tagUser;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="emails", type="text", nullable=true)
     */
    private $emails;

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
     * @var string
     *
     * @ORM\Column(name="primaryPicture", type="string", length=255)
     */
    private $primaryPicture;

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
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="primaryPicture")
     */
    private $primaryPictureFile;

    /**
     * @return mixed
     */
    public function getPrimaryPictureFile()
    {
        return $this->primaryPictureFile;
    }

    /**
     * @param mixed $primaryPictureFile
     * @throws \Exception
     */
    public function setPrimaryPictureFile($primaryPictureFile)
    {
        $this->primaryPictureFile = $primaryPictureFile;

        if ($primaryPictureFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="primaryTitle", type="string",  nullable=true)
     */
    private $primaryTitle;

    /**
     * Set tagUser
     *
     * @param string $primaryTitle
     *
     * @return PromotionEmail
     */
    public function setPrimaryTitle($primaryTitle)
    {
        $this->primaryTitle = $primaryTitle;

        return $this;
    }

    /**
     * Get PrimaryTitle
     *
     * @return string
     */
    public function getPrimaryTitle()
    {
        return $this->primaryTitle;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="introPicture1", type="string", length=255, nullable=true)
     */
    private $introPicture1;

    /**
     * Set introPicture1
     *
     * @param string $introPicture1
     *
     * @return PromotionEmail
     */
    public function setIntroPicture1($introPicture1)
    {
        $this->introPicture1 = $introPicture1;

        return $this;
    }

    /**
     * Get IntroPicture1
     *
     * @return string
     */
    public function getIntroPicture1()
    {
        return $this->introPicture1;
    }

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="introPicture1", nullable=true)
     */
    private $introPicture1File;

    /**
     * @return mixed
     */
    public function getIntroPicture1File()
    {
        return $this->introPicture1File;
    }

    /**
     * @param mixed $introPicture1File
     * @throws \Exception
     */
    public function setIntroPicture1File($introPicture1File)
    {
        $this->introPicture1File = $introPicture1File;

        if ($introPicture1File) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introTitle1",  type="string", length=255, nullable=true)
     */
    private $introTitle1;

    /**
     * Set introTitle1
     *
     * @param string $introTitle1
     *
     * @return PromotionEmail
     */
    public function setIntroTitle1($introTitle1)
    {
        $this->introTitle1 = $introTitle1;

        return $this;
    }

    /**
     * Get introTitle1
     *
     * @return string
     */
    public function getIntroTitle1()
    {
        return $this->introTitle1;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introContent1", type="text", nullable=true)
     */
    private $introContent1;

    /**
     * Set introContent1
     *
     * @param string $introContent1
     *
     * @return PromotionEmail
     */
    public function setIntroContent1($introContent1)
    {
        $this->introContent1 = $introContent1;

        return $this;
    }

    /**
     * Get introContent1
     *
     * @return string
     */
    public function getIntroContent1()
    {
        return $this->introContent1;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introLink1",  type="string", length=255, nullable=true)
     */
    private $introLink1;

    /**
     * Set introLink1
     *
     * @param string $introLink1
     *
     * @return PromotionEmail
     */
    public function setIntroLink1($introLink1)
    {
        $this->introLink1 = $introLink1;

        return $this;
    }

    /**
     * Get introLink1
     *
     * @return string
     */
    public function getIntroLink1()
    {
        return $this->introLink1;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="introPicture2", type="string", length=255)
     */
    private $introPicture2;

    /**
     * Set introPicture2
     *
     * @param string $introPicture2
     *
     * @return PromotionEmail
     */
    public function setIntroPicture2($introPicture2)
    {
        $this->introPicture2 = $introPicture2;

        return $this;
    }

    /**
     * Get IntroPicture2
     *
     * @return string
     */
    public function getIntroPicture2()
    {
        return $this->introPicture2;
    }

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="introPicture2",  nullable=true)
     */
    private $introPicture2File;

    /**
     * @return mixed
     */
    public function getIntroPicture2File()
    {
        return $this->introPicture2File;
    }

    /**
     * @param mixed $introPicture2File
     * @throws \Exception
     */
    public function setIntroPicture2File($introPicture2File)
    {
        $this->introPicture2File = $introPicture2File;

        if ($introPicture2File) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introTitle2",  type="string", length=255, nullable=true)
     */
    private $introTitle2;

    /**
     * Set introTitle2
     *
     * @param string $introTitle2
     *
     * @return PromotionEmail
     */
    public function setIntroTitle2($introTitle2)
    {
        $this->introTitle2 = $introTitle2;

        return $this;
    }

    /**
     * Get introTitle2
     *
     * @return string
     */
    public function getIntroTitle2()
    {
        return $this->introTitle2;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introContent2", type="text", nullable=true)
     */
    private $introContent2;

    /**
     * Set introContent2
     *
     * @param string $introContent2
     *
     * @return PromotionEmail
     */
    public function setIntroContent2($introContent2)
    {
        $this->introContent2 = $introContent2;

        return $this;
    }

    /**
     * Get introContent2
     *
     * @return string
     */
    public function getIntroContent2()
    {
        return $this->introContent2;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introLink2",  type="string", length=255, nullable=true)
     */
    private $introLink2;

    /**
     * Set introLink2
     *
     * @param string $introLink2
     *
     * @return PromotionEmail
     */
    public function setIntroLink2($introLink2)
    {
        $this->introLink2 = $introLink2;

        return $this;
    }

    /**
     * Get introLink2
     *
     * @return string
     */
    public function getIntroLink2()
    {
        return $this->introLink2;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="introPicture3", type="string", length=255)
     */
    private $introPicture3;

    /**
     * Set introPicture3
     *
     * @param string $introPicture3
     *
     * @return PromotionEmail
     */
    public function setIntroPicture3($introPicture3)
    {
        $this->introPicture3 = $introPicture3;

        return $this;
    }

    /**
     * Get IntroPicture3
     *
     * @return string
     */
    public function getIntroPicture3()
    {
        return $this->introPicture3;
    }

    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="introPicture3",  nullable=true)
     */
    private $introPicture3File;

    /**
     * @return mixed
     */
    public function getIntroPicture3File()
    {
        return $this->introPicture3File;
    }

    /**
     * @param mixed $introPicture3File
     * @throws \Exception
     */
    public function setIntroPicture3File($introPicture3File)
    {
        $this->introPicture3File = $introPicture3File;

        if ($introPicture3File) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="introTitle3",  type="string", length=255, nullable=true)
     */
    private $introTitle3;

    /**
     * Set introTitle3
     *
     * @param string $introTitle3
     *
     * @return PromotionEmail
     */
    public function setIntroTitle3($introTitle3)
    {
        $this->introTitle3 = $introTitle3;

        return $this;
    }

    /**
     * Get introTitle3
     *
     * @return string
     */
    public function getIntroTitle3()
    {
        return $this->introTitle3;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="introContent3", type="text", nullable=true)
     */
    private $introContent3;

    /**
     * Set introContent3
     *
     * @param string $introContent3
     *
     * @return PromotionEmail
     */
    public function setIntroContent3($introContent3)
    {
        $this->introContent3 = $introContent3;

        return $this;
    }

    /**
     * Get introContent3
     *
     * @return string
     */
    public function getIntroContent3()
    {
        return $this->introContent3;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="introLink3",  type="string", length=255, nullable=true)
     */
    private $introLink3;

    /**
     * Set introLink3
     *
     * @param string $introLink3
     *
     * @return PromotionEmail
     */
    public function setIntroLink3($introLink3)
    {
        $this->introLink3 = $introLink3;

        return $this;
    }

    /**
     * Get introLink3
     *
     * @return string
     */
    public function getIntroLink3()
    {
        return $this->introLink3;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="offersTitle",  type="string", length=255, nullable=true)
     */
    private $offersTitle;

    /**
     * Set introTitle1
     *
     * @param string $offersTitle
     *
     * @return PromotionEmail
     */
    public function setOffersTitle($offersTitle)
    {
        $this->offersTitle = $offersTitle;

        return $this;
    }

    /**
     * Get offersTitle
     *
     * @return string
     */
    public function getOffersTitle()
    {
        return $this->offersTitle;
    }




    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Offer")
     */
    private $offers;

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $offers
     *
     * @return Offer
     */
    public function addOffers(Offer $offers)
    {
        $this->offers[] = $offers;

        return $this;
    }

    /**
     * Remove offers.
     *
     * @param \AppBundle\Entity\Offer $offers
     */
    public function removeOffers(Product $offers)
    {
        $this->offers->removeElement($offers);
    }

    /**
     * Get offers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffers()
    {
        return $this->offers;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="productsTitle",  type="string", length=255, nullable=true)
     */
    private $productsTitle;

    /**
     * Set introTitle1
     *
     * @param string $productsTitle
     *
     * @return PromotionEmail
     */
    public function setProductsTitle($productsTitle)
    {
        $this->productsTitle = $productsTitle;

        return $this;
    }

    /**
     * Get productsTitle
     *
     * @return string
     */
    public function getProductsTitle()
    {
        return $this->productsTitle;
    }

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product")
     */
    private $products;

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Offer
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="promotionPicture", type="string", length=255)
     */
    private $promotionPicture;

    /**
     * Set promotionPicture
     *
     * @param string $promotionPicture
     *
     * @return PromotionEmail
     */
    public function setPromotionPicture($promotionPicture)
    {
        $this->promotionPicture = $promotionPicture;

        return $this;
    }

    /**
     * Get promotionPicture
     *
     * @return string
     */
    public function getPromotionPicture()
    {
        return $this->promotionPicture;
    }


    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="promotionPicture")
     */
    private $promotionPictureFile;

    /**
     * @return mixed
     */
    public function getPromotionPictureFile()
    {
        return $this->promotionPictureFile;
    }

    /**
     * @param mixed $promotionPictureFile
     * @throws \Exception
     */
    public function setPromotionPictureFile($promotionPictureFile)
    {
        $this->promotionPictureFile = $promotionPictureFile;

        if ($promotionPictureFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="promotionTitle",  type="string", length=255, nullable=true)
     */
    private $promotionTitle;

    /**
     * Set promotionTitle
     *
     * @param string $promotionTitle
     *
     * @return PromotionEmail
     */
    public function setPromotionTitle($promotionTitle)
    {
        $this->promotionTitle = $promotionTitle;

        return $this;
    }

    /**
     * Get promotionTitle
     *
     * @return string
     */
    public function getPromotionTitle()
    {
        return $this->promotionTitle;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="promotionContent", type="text", nullable=true)
     */
    private $promotionContent;

    /**
     * Set promotionContent
     *
     * @param string $promotionContent
     *
     * @return PromotionEmail
     */
    public function setPromotionContent($promotionContent)
    {
        $this->promotionContent = $promotionContent;

        return $this;
    }

    /**
     * Get promotionContent
     *
     * @return string
     */
    public function getPromotionContent()
    {
        return $this->promotionContent;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="promotionLink",  type="string", length=255, nullable=true)
     */
    private $promotionLink;

    /**
     * Set promotionLink
     *
     * @param string $promotionLink
     *
     * @return PromotionEmail
     */
    public function setPromotionLink($promotionLink)
    {
        $this->promotionLink = $promotionLink;

        return $this;
    }

    /**
     * Get promotionLink
     *
     * @return string
     */
    public function getPromotionLink()
    {
        return $this->promotionLink;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="blogTitle",  type="string", length=255, nullable=true)
     */
    private $blogTitle;

    /**
     * Set blogTitle
     *
     * @param string $blogTitle
     *
     * @return PromotionEmail
     */
    public function setBlogTitle($blogTitle)
    {
        $this->blogTitle = $blogTitle;

        return $this;
    }

    /**
     * Get blogTitle
     *
     * @return string
     */
    public function getBlogTitle()
    {
        return $this->blogTitle;
    }


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Blog\Post")
     */
    private $blogs;

    /**
     * Add blog.
     *
     * @param \AppBundle\Entity\Blog\Post $blog
     *
     * @return PromotionEmail
     */
    public function addBlog(Blog\Post $blog)
    {
        $this->blogs[] = $blog;

        return $this;
    }

    /**
     * Remove blog.
     *
     * @param \AppBundle\Entity\Blog\Post $blog
     */
    public function removeBlog(Product $blog)
    {
        $this->blogs->removeElement($blog);
    }

    /**
     * Get blogs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogs()
    {
        return $this->blogs;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="servicesTitle",  type="string", length=255, nullable=true)
     */
    private $servicesTitle;

    /**
     * Set servicesTitle
     *
     * @param string $servicesTitle
     *
     * @return PromotionEmail
     */
    public function setServicesTitle($servicesTitle)
    {
        $this->servicesTitle = $servicesTitle;

        return $this;
    }

    /**
     * Get blogTitle
     *
     * @return string
     */
    public function getServicesTitle()
    {
        return $this->servicesTitle;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="servicePicture1", type="string", length=255)
     */
    private $servicePicture1;

    /*
     * Set servicePicture1
     *
     * @param string $servicePicture1
     *
     * @return PromotionEmail
     */
    public function setServicePicture1($servicePicture1)
    {
        $this->servicePicture1 = $servicePicture1;

        return $this;
    }

    /**
     * Get servicesPicture1
     *
     * @return string
     */
    public function getServicePicture1()
    {
        return $this->servicePicture1;
    }


    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="servicePicture1",  nullable=true)
     */
    private $servicePicture1File;

    /**
     * @return mixed
     */
    public function getServicePicture1File()
    {
        return $this->servicePicture1File;
    }

    /**
     * @param mixed $servicePicture1File
     * @throws \Exception
     */
    public function setServicePicture1File($servicePicture1File)
    {
        $this->servicePicture1File = $servicePicture1File;

        if ($servicePicture1File) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="serviceTitle1",  type="string", length=255, nullable=true)
     */
    private $serviceTitle1;

    /**
     * Set serviceTitle1
     *
     * @param string $serviceTitle1
     *
     * @return PromotionEmail
     */
    public function setServiceTitle1($serviceTitle1)
    {
        $this->serviceTitle1 = $serviceTitle1;

        return $this;
    }

    /**
     * Get serviceTitle1
     *
     * @return string
     */
    public function getServiceTitle1()
    {
        return $this->serviceTitle1;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="serviceContent1", type="text", nullable=true)
     */
    private $serviceContent1;

    /**
     * Set serviceContent1
     *
     * @param string $serviceContent1
     *
     * @return PromotionEmail
     */
    public function setServiceContent1($serviceContent1)
    {
        $this->serviceContent1 = $serviceContent1;

        return $this;
    }

    /**
     * Get serviceContent1
     *
     * @return string
     */
    public function getServiceContent1()
    {
        return $this->serviceContent1;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="serviceLink1",  type="string", length=255, nullable=true)
     */
    private $serviceLink1;

    /**
     * Set serviceLink1
     *
     * @param string $serviceLink1
     *
     * @return PromotionEmail
     */
    public function setServiceLink1($serviceLink1)
    {
        $this->serviceLink1 = $serviceLink1;

        return $this;
    }

    /**
     * Get serviceLink1
     *
     * @return string
     */
    public function getServiceLink1()
    {
        return $this->serviceLink1;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="servicePicture2", type="string", length=255)
     */
    private $servicePicture2;

    /**
     * Set servicePicture2
     *
     * @param string $servicePicture2
     *
     * @return PromotionEmail
     */
    public function setServicePicture2($servicePicture2)
    {
        $this->servicePicture2 = $servicePicture2;

        return $this;
    }

    /**
     * Get servicePicture2
     *
     * @return string
     */
    public function getServicePicture2()
    {
        return $this->servicePicture2;
    }


    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="servicePicture2",  nullable=true)
     */
    private $servicePicture2File;

    /**
     * @return mixed
     */
    public function getServicePicture2File()
    {
        return $this->servicePicture2File;
    }

    /**
     * @param mixed $servicePicture2File
     * @throws \Exception
     */
    public function setServicePicture2File($servicePicture2File)
    {
        $this->servicePicture2File = $servicePicture2File;

        if ($servicePicture2File) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="serviceTitle2",  type="string", length=255, nullable=true)
     */
    private $serviceTitle2;

    /**
     * Set serviceTitle2
     *
     * @param string $serviceTitle2
     *
     * @return PromotionEmail
     */
    public function setServiceTitle2($serviceTitle2)
    {
        $this->serviceTitle2 = $serviceTitle2;

        return $this;
    }

    /**
     * Get serviceTitle2
     *
     * @return string
     */
    public function getServiceTitle2()
    {
        return $this->serviceTitle2;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="serviceLink2",  type="string", length=255, nullable=true)
     */
    private $serviceLink2;

    /**
     * Set serviceLink2
     *
     * @param string $serviceLink2
     *
     * @return PromotionEmail
     */
    public function setServiceLink2($serviceLink2)
    {
        $this->serviceLink2 = $serviceLink2;

        return $this;
    }

    /**
     * Get serviceLink2
     *
     * @return string
     */
    public function getServiceLink2()
    {
        return $this->serviceLink2;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="serviceContent2", type="text", nullable=true)
     */
    private $serviceContent2;

    /**
     * Set serviceContent2
     *
     * @param string $serviceContent2
     *
     * @return PromotionEmail
     */
    public function setServiceContent2($serviceContent2)
    {
        $this->serviceContent2 = $serviceContent2;

        return $this;
    }

    /**
     * Get serviceContent2
     *
     * @return string
     */
    public function getServiceContent2()
    {
        return $this->serviceContent2;
    }




    /**
     * @var string
     *
     * @ORM\Column(name="footerPicture", type="string", length=255)
     */
    private $footerPicture;

    /**
     * Set footerPicture
     *
     * @param string $footerPicture
     *
     * @return PromotionEmail
     */
    public function setFooterPicture($footerPicture)
    {
        $this->footerPicture = $footerPicture;

        return $this;
    }

    /**
     * Get footerPicture
     *
     * @return string
     */
    public function getFooterPicture()
    {
        return $this->footerPicture;
    }


    /**
     * @Vich\UploadableField(mapping="email_images", fileNameProperty="footerPicture")
     */
    private $footerPictureFile;

    /**
     * @return mixed
     */
    public function getFooterPictureFile()
    {
        return $this->footerPictureFile;
    }

    /**
     * @param mixed $footerPictureFile
     * @throws \Exception
     */
    public function setFooterPictureFile($footerPictureFile)
    {
        $this->footerPictureFile = $footerPictureFile;

        if ($footerPictureFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * @var string
     *
     * @ORM\Column(name="footerPictureLink",  type="string", length=255, nullable=true)
     */
    private $footerPictureLink;

    /**
     * Set footerPictureLink
     *
     * @param string $footerPictureLink
     *
     * @return PromotionEmail
     */
    public function setFooterPictureLink($footerPictureLink)
    {
        $this->footerPictureLink = $footerPictureLink;

        return $this;
    }

    /**
     * Get footerPictureLink
     *
     * @return string
     */
    public function getFooterPictureLink()
    {
        return $this->footerPictureLink;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->blogs = new ArrayCollection();
    }

}

