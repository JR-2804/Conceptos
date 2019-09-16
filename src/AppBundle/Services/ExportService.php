<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ExportService
{

    private $em;
    private $uploaderHelper;

    /**
     * ExportService constructor.
     * @param $em
     * @param $uploaderHelper
     */
    public function __construct(EntityManager $em, UploaderHelper $uploaderHelper)
    {
        $this->em = $em;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function export($basePath)
    {
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();
        $date = new \DateTime();
        $fileTmp = '../public_html/db/conceptos-' . date_format($date, 'd-m-Y') . '.sqlite';
        copy('../public_html/conceptos.sqlite', $fileTmp);
        $pdo = new \PDO('sqlite:' . $fileTmp);
        $this->populateGiftCard($pdo, $basePath);

        $this->populateMemberCard($pdo, $basePath);

        $page = $this->em->getRepository('AppBundle:Page\Page')->findOneBy(array(
            'name' => 'Pantallas de la aplicación'
        ));

        $teamContent = $page->getData()['team'];

        $imagePath = $basePath . "/../public_html/" . $teamContent['image']['path'];
        $clientExtention = strtoupper(substr($teamContent['image']['path'], strrpos($teamContent['image']['path'], '.'), strlen($teamContent['image']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        list($width, $height) = getimagesize($basePath . "/../public_html/" . $teamContent['image']['path']);
        $mainImageTeam = base64_encode(file_get_contents($basePath . "/../public_html/" . $teamContent['image']['path']));
        $query = "INSERT INTO team_text (id, text, main_image, height) VALUES (1, '" . $teamContent['text'] . "', '" . $mainImageTeam . "', " . $height . ")";

        $pdo->beginTransaction();
        $pdo->exec($query);
        $pdo->commit();

        $colors = $this->em->getRepository('AppBundle:Color')->findAll();
        $pdo->beginTransaction();
        foreach ($colors as $color) {
            $query = 'INSERT INTO COLOR (id, color_name) VALUES (' . $color->getId() . ',"' . $color->getName() . '")';
            $pdo->exec($query);
        }
        $pdo->commit();

        $materials = $this->em->getRepository('AppBundle:Material')->findAll();
        $pdo->beginTransaction();
        foreach ($materials as $material) {
            $query = 'INSERT INTO MATERIAL (id, material_name) VALUES (' . $material->getId() . ', "' . $material->getName() . '")';
            $pdo->exec($query);
        }
        $pdo->commit();

        $pdo->beginTransaction();
        foreach ($categories as $category) {
            $imagePath = $basePath . "/../public_html" . $this->uploaderHelper->asset($category, 'imageFile');
            $clientExtention = strtoupper(substr($category->getImage(), strrpos($category->getImage(), '.'), strlen($category->getImage())));
            $maxWidth = 500;
            $maxHeight = 250;
            if (substr_count($clientExtention, 'PNG') > 0) {
                $original = imagecreatefrompng($imagePath);
            } else {
                $original = imagecreatefromjpeg($imagePath);
            }
            list($width, $height) = getimagesize($imagePath);
            $xRatio = $maxWidth / $width;
            $yRatio = $maxHeight / $height;
            if ($width <= $maxWidth && $height <= $maxHeight) {
                $finalWidth = $width;
                $finalHeight = $height;
            } elseif (($xRatio * $height) < $maxHeight) {
                $finalHeight = ceil($xRatio * $height);
                $finalWidth = $maxWidth;
            } else {
                $finalWidth = ceil($yRatio * $width);
                $finalHeight = $maxHeight;
            }
            $resized = imagecreatetruecolor($finalWidth, $finalHeight);
            imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
            imagedestroy($original);
            if (substr_count($clientExtention, 'PNG') > 0) {
                imagepng($resized, $imagePath);
            } else {
                imagejpeg($resized, $imagePath);
            }
            $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($category, 'imageFile')));
            $query = 'INSERT INTO CATEGORY (id, name, image, image_height)VALUES (' . $category->getId() . ', "' . $category->getName() . '","' . $content . '",' . $finalHeight . ')';
            $pdo->exec($query);
            foreach ($category->getFavoritesProducts() as $favoritesProduct) {
                $query = 'INSERT INTO CATEGORY_PRODUCT (category_id, product_id) VALUES (' . $category->getId() . ',' . $favoritesProduct->getId() . ')';
                $pdo->exec($query);
            }
        }
        $pdo->commit();

        $repoCategory = $this->em->getRepository('AppBundle:Category');
        $parents = $repoCategory->createQueryBuilder('c')
            ->where('c.parents IS EMPTY')->getQuery()->getResult();
        $pdo->beginTransaction();
        foreach ($parents as $parent) {
            foreach ($parent->getSubCategories() as $subCategory) {
                $query = "INSERT INTO CATEGORY_PARENT (parent_id, child_id) VALUES (" . $parent->getId() . "," . $subCategory->getId() . ")";
                $pdo->exec($query);
            }
        }
        $pdo->commit();

        $products = $this->em->getRepository('AppBundle:Product')->findAll();
        $pdo->beginTransaction();
        $batchSize = 100;
        foreach ($products as $index => $product) {
            if ($product->getColor() != null) {
                $colorId = $product->getColor()->getId();
            } else {
                $colorId = null;
            }
            if ($product->getMaterial() != null) {
                $materialId = $product->getMaterial()->getId();
            } else {
                $materialId = null;
            }
            $query = "INSERT INTO PRODUCT (id, category_id, name_product, description, code, price, popular, recent, item, color_id, material_id) VALUES (" . $product->getId() . ", " . $product->getCategory()->getId() . ",'" . $product->getName() . "', '" . $product->getDescription() .
                "', '" . $product->getCode() . "', '" . $product->getPrice() . "', '" . $product->getPopular() . "', '" . $product->getRecent() . "', '" . $product->getItem() . "','" . $colorId . "', '" . $materialId . "')";
            $pdo->exec($query);
            $images = $product->getImages();
            foreach ($images as $image) {
                $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($image, 'imageFile')));
                $query = "INSERT INTO IMAGE (id, image, original_name, content, product_id, main) VALUES (" . $image->getId() . ", '" . $image->getImage() . "', '" . $image->getOriginalName() . "', '" . $content . "', '" . $product->getId() . "', " . 0 . ")";
                $pdo->exec($query);
            }
            $mainImage = $product->getMainImage();
            $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($mainImage, 'imageFile')));
            $query = "INSERT INTO IMAGE (id, image, original_name, content, product_id, main) VALUES (" . $mainImage->getId() . ", '" . $mainImage->getImage() . "', '" . $mainImage->getOriginalName() . "', '" . $content . "', '" . $product->getId() . "', " . 1 . ")";
            $pdo->exec($query);
            if ($index % $batchSize == 0) {
                $pdo->commit();
                $pdo->beginTransaction();
            }
        }
        $pdo->commit();
        $phones = $this->em->getRepository('AppBundle:Phone')->findAll();
        $pdo->beginTransaction();
        foreach ($phones as $phone) {
            $query = 'INSERT INTO PHONE (id, phone_number, count_send) VALUES (' . $phone->getId() . ', "' . $phone->getNumber() . '",0)';
            $pdo->exec($query);
        }
        $pdo->commit();

        $offers = $this->em->getRepository('AppBundle:Offer')->findAll();
        $pdo->beginTransaction();
        foreach ($offers as $offer) {
            $startDate = date_format($offer->getStartDate(), 'Y-m-d H:i:s.u');
            $endDate = date_format($offer->getEndDate(), 'Y-m-d H:i:s.u');
            $query = 'INSERT INTO OFFER (id, name_offer, price, startDate, endDate) VALUES (' . $offer->getId() . ', "' . $offer->getName() . '", ' . $offer->getPrice() . ', "' . $startDate . '", "' . $endDate . '")';
            $pdo->exec($query);
            foreach ($offer->getProducts() as $productOff) {
                $query = 'INSERT INTO OFFER_PRODUCT (offer_id, product_id)VALUES (' . $offer->getId() . ', ' . $productOff->getId() . ')';
                $pdo->exec($query);
            }
        }
        $pdo->commit();

        $pdo->beginTransaction();
        $config = $this->em->getRepository('AppBundle:Configuration')->find(1);
        $contentConfig = base64_encode(file_get_contents($basePath . "/../public_html" . $this->uploaderHelper->asset($config->getImage(), 'imageFile')));
        $terms = $config->getTermAndConditions();
        list($width, $height) = getimagesize($basePath . "/../public_html" . $this->uploaderHelper->asset($config->getImage(), 'imageFile'));
        $query = "INSERT INTO CONFIG (id, hours, phone, image, terms, height) VALUES (" . $config->getId() . ", '" . $config->getHours() . "', '" . $config->getPhone() . "', '" . $contentConfig . "','" . $terms . "', " . $height . ")";
        $pdo->exec($query);
        $pdo->commit();
        copy($fileTmp, '../public_html/download/db/conceptos-lasted.conceptos');
        $config->setLastDatabaseExport(new \DateTime());
        $this->em->flush();
    }

    public function exportApp($basePath)
    {
        $date = new \DateTime();
        $fileTmp = '../public_html/db/conceptos-' . $date->getTimestamp() . '.conceptos';
        copy('../public_html/conceptos.sqlite', $fileTmp);

        $pdo = new \PDO('sqlite:' . $fileTmp);
        $this->populateGiftCard($pdo, $basePath);

        $this->populateMemberCard($pdo, $basePath);

        $page = $this->em->getRepository('AppBundle:Page\Page')->findOneBy(array(
            'name' => 'Pantallas de la aplicación'
        ));

        $teamContent = $page->getData()['team'];

        $imagePath = $basePath . "/../public_html/" . $teamContent['image']['path'];
        $clientExtention = strtoupper(substr($teamContent['image']['path'], strrpos($teamContent['image']['path'], '.'), strlen($teamContent['image']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        list($width, $height) = getimagesize($basePath . "/../public_html/" . $teamContent['image']['path']);
        $mainImageTeam = base64_encode(file_get_contents($basePath . "/../public_html/" . $teamContent['image']['path']));
        $query = "INSERT INTO team_text (id, text, main_image, height) VALUES (1, '" . $teamContent['text'] . "', '" . $mainImageTeam . "', " . $height . ")";

        $pdo->beginTransaction();
        $pdo->exec($query);
        $pdo->commit();

        return $fileTmp;
    }

    public function exportColor($fileDB)
    {
        $colors = $this->em->getRepository('AppBundle:Color')->findAll();
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($colors as $color) {
            $query = 'INSERT INTO COLOR (id, color_name) VALUES (' . $color->getId() . ',"' . $color->getName() . '")';
            $pdo->exec($query);
        }
        $pdo->commit();
    }

    public function exportMaterial($fileDB)
    {
        $materials = $this->em->getRepository('AppBundle:Material')->findAll();
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($materials as $material) {
            $query = 'INSERT INTO MATERIAL (id, material_name) VALUES (' . $material->getId() . ', "' . $material->getName() . '")';
            $pdo->exec($query);
        }
        $pdo->commit();
    }

    public function exportCategories($fileDB, $basePath)
    {
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($categories as $category) {
            $imagePath = $basePath . "/../public_html" . $this->uploaderHelper->asset($category, 'imageFile');
            $clientExtention = strtoupper(substr($category->getImage(), strrpos($category->getImage(), '.'), strlen($category->getImage())));
            $maxWidth = 500;
            $maxHeight = 250;
            if (substr_count($clientExtention, 'PNG') > 0) {
                $original = imagecreatefrompng($imagePath);
            } else {
                $original = imagecreatefromjpeg($imagePath);
            }
            list($width, $height) = getimagesize($imagePath);
            $xRatio = $maxWidth / $width;
            $yRatio = $maxHeight / $height;
            if ($width <= $maxWidth && $height <= $maxHeight) {
                $finalWidth = $width;
                $finalHeight = $height;
            } elseif (($xRatio * $height) < $maxHeight) {
                $finalHeight = ceil($xRatio * $height);
                $finalWidth = $maxWidth;
            } else {
                $finalWidth = ceil($yRatio * $width);
                $finalHeight = $maxHeight;
            }
            $resized = imagecreatetruecolor($finalWidth, $finalHeight);
            imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
            imagedestroy($original);
            if (substr_count($clientExtention, 'PNG') > 0) {
                imagepng($resized, $imagePath);
            } else {
                imagejpeg($resized, $imagePath);
            }
            $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($category, 'imageFile')));
            $query = 'INSERT INTO CATEGORY (id, name, image, image_height)VALUES (' . $category->getId() . ', "' . $category->getName() . '","' . $content . '",' . $finalHeight . ')';
            $pdo->exec($query);
            foreach ($category->getFavoritesProducts() as $favoritesProduct) {
                $query = 'INSERT INTO CATEGORY_PRODUCT (category_id, product_id) VALUES (' . $category->getId() . ',' . $favoritesProduct->getId() . ')';
                $pdo->exec($query);
            }
        }

        $repoCategory = $this->em->getRepository('AppBundle:Category');
        $parents = $repoCategory->createQueryBuilder('c')
            ->where('c.parents IS EMPTY')->getQuery()->getResult();
        foreach ($parents as $parent) {
            foreach ($parent->getSubCategories() as $subCategory) {
                $query = "INSERT INTO CATEGORY_PARENT (parent_id, child_id) VALUES (" . $parent->getId() . "," . $subCategory->getId() . ")";
                $pdo->exec($query);
            }
        }
        $pdo->commit();
    }

    public function exportProducts($fileDB, $page)
    {
        $products = $this->em->getRepository('AppBundle:Product')->findBy(array(), null, 100, $page * 100);
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($products as $index => $product) {
            if ($product->getColor() != null) {
                $colorId = $product->getColor()->getId();
            } else {
                $colorId = null;
            }
            if ($product->getMaterial() != null) {
                $materialId = $product->getMaterial()->getId();
            } else {
                $materialId = null;
            }
            $query = "INSERT INTO PRODUCT (id, name_product, description, code, price, popular, recent, item, color_id, material_id) VALUES (" . $product->getId() . ",'" . $product->getName() . "', '" . $product->getDescription() .
                "', '" . $product->getCode() . "', '" . $product->getPrice() . "', '" . $product->getPopular() . "', '" . $product->getRecent() . "', '" . $product->getItem() . "','" . $colorId . "', '" . $materialId . "')";
            $pdo->exec($query);
            $images = $product->getImages();
            foreach ($images as $image) {
                $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($image, 'imageFile')));
                $query = "INSERT INTO IMAGE (id, image, original_name, content, product_id, main) VALUES (" . $image->getId() . ", '" . $image->getImage() . "', '" . $image->getOriginalName() . "', '" . $content . "', '" . $product->getId() . "', " . 0 . ")";
                $pdo->exec($query);
            }
            $categories = $product->getCategories();
            foreach ($categories as $category) {
                $query = "INSERT INTO PRODUCT_CATEGORY(product_id, category_id) VALUES (" . $product->getId() . ", " . $category->getId() . ")";
                $pdo->exec($query);
            }
            $mainImage = $product->getMainImage();
            $content = base64_encode(file_get_contents('../public_html' . $this->uploaderHelper->asset($mainImage, 'imageFile')));
            $query = "INSERT INTO IMAGE (id, image, original_name, content, product_id, main) VALUES (" . $mainImage->getId() . ", '" . $mainImage->getImage() . "', '" . $mainImage->getOriginalName() . "', '" . $content . "', '" . $product->getId() . "', " . 1 . ")";
            $pdo->exec($query);
        }
        $pdo->commit();
        if (count($products) < 100) {
            return true;
        } else {
            return false;
        }
    }

    public function exportOffers($fileDB)
    {
        $offers = $this->em->getRepository('AppBundle:Offer')->findAll();
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($offers as $offer) {
            $startDate = date_format($offer->getStartDate(), 'Y-m-d H:i:s.u');
            $endDate = date_format($offer->getEndDate(), 'Y-m-d H:i:s.u');
            $query = 'INSERT INTO OFFER (id, name_offer, price, startDate, endDate) VALUES (' . $offer->getId() . ', "' . $offer->getName() . '", ' . $offer->getPrice() . ', "' . $startDate . '", "' . $endDate . '")';
            $pdo->exec($query);
            foreach ($offer->getProducts() as $productOff) {
                $query = 'INSERT INTO OFFER_PRODUCT (offer_id, product_id)VALUES (' . $offer->getId() . ', ' . $productOff->getId() . ')';
                $pdo->exec($query);
            }
        }
        $pdo->commit();
    }

    public function exportPhones($fileDB)
    {
        $phones = $this->em->getRepository('AppBundle:Phone')->findAll();
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        foreach ($phones as $phone) {
            $query = 'INSERT INTO PHONE (id, phone_number, count_send) VALUES (' . $phone->getId() . ', "' . $phone->getNumber() . '",0)';
            $pdo->exec($query);
        }
        $pdo->commit();
    }

    public function exportConfig($fileDB, $basePath)
    {
        $pdo = new \PDO('sqlite:' . $fileDB);
        $pdo->beginTransaction();
        $config = $this->em->getRepository('AppBundle:Configuration')->find(1);
        $fileName = substr($fileDB, strpos($fileDB, 'conceptos'), strlen($fileDB));
        $config->setLastDBExported($fileName);
        $contentConfig = base64_encode(file_get_contents($basePath . "/../public_html" . $this->uploaderHelper->asset($config->getImage(), 'imageFile')));
        $terms = $config->getTermAndConditions();
        list($width, $height) = getimagesize($basePath . "/../public_html" . $this->uploaderHelper->asset($config->getImage(), 'imageFile'));
        $query = "INSERT INTO CONFIG (id, hours, phone, image, terms, height) VALUES (" . $config->getId() . ", '" . $config->getHours() . "', '" . $config->getPhone() . "', '" . $contentConfig . "','" . $terms . "', " . $height . ")";
        $pdo->exec($query);
        $pdo->commit();
        $this->em->flush();
//        copy($fileDB, '../public_html/download/db/conceptos-lasted.conceptos');
    }

    private function populateGiftCard(\PDO $db, $basePath)
    {
        $page = $this->em->getRepository('AppBundle:Page\Page')->findOneBy(array(
            'name' => 'Pantallas de la aplicación'
        ));
        $giftCardContent = $page->getData()['gift'];

        //MAIN IMAGE
        $imagePath = $basePath . "/../public_html/" . $giftCardContent['image']['path'];
        $clientExtention = strtoupper(substr($giftCardContent['image']['path'], strrpos($giftCardContent['image']['path'], '.'), strlen($giftCardContent['image']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        //GIFT 15 IMAGE

        $imagePath = $basePath . "/../public_html/" . $giftCardContent['gift15']['path'];
        $clientExtention = strtoupper(substr($giftCardContent['gift15']['path'], strrpos($giftCardContent['gift15']['path'], '.'), strlen($giftCardContent['gift15']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        //GIFT 25 IMAGE

        $imagePath = $basePath . "/../public_html/" . $giftCardContent['gift25']['path'];
        $clientExtention = strtoupper(substr($giftCardContent['gift25']['path'], strrpos($giftCardContent['gift25']['path'], '.'), strlen($giftCardContent['gift25']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        //GIFT 50 IMAGE

        $imagePath = $basePath . "/../public_html/" . $giftCardContent['gift50']['path'];
        $clientExtention = strtoupper(substr($giftCardContent['gift50']['path'], strrpos($giftCardContent['gift50']['path'], '.'), strlen($giftCardContent['gift50']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        $mainImageGiftCard = base64_encode(file_get_contents($basePath . "/../public_html/" . $giftCardContent['image']['path']));
        $gift15 = base64_encode(file_get_contents($basePath . "/../public_html/" . $giftCardContent['gift15']['path']));
        $gift25 = base64_encode(file_get_contents($basePath . "/../public_html/" . $giftCardContent['gift25']['path']));
        $gift50 = base64_encode(file_get_contents($basePath . "/../public_html/" . $giftCardContent['gift50']['path']));
        list($width, $height) = getimagesize($basePath . "/../public_html/" . $giftCardContent['image']['path']);

        $query = "INSERT INTO gift_card_text (id, text, main_image, gift_15, gift_25, gift_50, height) VALUES (1,'" . $giftCardContent['text'] . "', '" . $mainImageGiftCard . "', '" . $gift15 . "', '" . $gift25 . "', '" . $gift50 . "', " . $height . ")";
        $db->beginTransaction();
        $db->exec($query);
        $db->commit();
    }

    private function populateMemberCard(\PDO $db, $basePath)
    {
        $page = $this->em->getRepository('AppBundle:Page\Page')->findOneBy(array(
            'name' => 'Pantallas de la aplicación'
        ));

        $memberCardContent = $page->getData()['members'];

        //MAIN IMAGE
        $imagePath = $basePath . "/../public_html/" . $memberCardContent['image']['path'];
        $clientExtention = strtoupper(substr($memberCardContent['image']['path'], strrpos($memberCardContent['image']['path'], '.'), strlen($memberCardContent['image']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        //GOLD IMAGE
        $imagePath = $basePath . "/../public_html/" . $memberCardContent['gold']['path'];
        $clientExtention = strtoupper(substr($memberCardContent['gold']['path'], strrpos($memberCardContent['gold']['path'], '.'), strlen($memberCardContent['gold']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }


        //PLATINUM IMAGE
        $imagePath = $basePath . "/../public_html/" . $memberCardContent['platinum']['path'];
        $clientExtention = strtoupper(substr($memberCardContent['platinum']['path'], strrpos($memberCardContent['platinum']['path'], '.'), strlen($memberCardContent['platinum']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }

        //PREMIUM IMAGE
        $imagePath = $basePath . "/../public_html/" . $memberCardContent['premium']['path'];
        $clientExtention = strtoupper(substr($memberCardContent['premium']['path'], strrpos($memberCardContent['premium']['path'], '.'), strlen($memberCardContent['premium']['path'])));
        $maxWidth = 500;
        $maxHeight = 250;

        if (substr_count($clientExtention, 'PNG') > 0) {
            $original = imagecreatefrompng($imagePath);
        } else {
            $original = imagecreatefromjpeg($imagePath);
        }
        list($width, $height) = getimagesize($imagePath);
        $xRatio = $maxWidth / $width;
        $yRatio = $maxHeight / $height;
        if ($width <= $maxWidth && $height <= $maxHeight) {
            $finalWidth = $width;
            $finalHeight = $height;
        } elseif (($xRatio * $height) < $maxHeight) {
            $finalHeight = ceil($xRatio * $height);
            $finalWidth = $maxWidth;
        } else {
            $finalWidth = ceil($yRatio * $width);
            $finalHeight = $maxHeight;
        }
        $resized = imagecreatetruecolor($finalWidth, $finalHeight);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);
        imagedestroy($original);
        if (substr_count($clientExtention, 'PNG') > 0) {
            imagepng($resized, $imagePath);
        } else {
            imagejpeg($resized, $imagePath);
        }


        $mainImageMemberCard = base64_encode(file_get_contents($basePath . "/../public_html/" . $memberCardContent['image']['path']));
        $goldImageMemberCard = base64_encode(file_get_contents($basePath . "/../public_html/" . $memberCardContent['gold']['path']));
        $platinumImageMemberCard = base64_encode(file_get_contents($basePath . "/../public_html/" . $memberCardContent['platinum']['path']));
        $premiumImageMemberCard = base64_encode(file_get_contents($basePath . "/../public_html/" . $memberCardContent['premium']['path']));

        list($width, $height) = getimagesize($basePath . "/../public_html/" . $memberCardContent['image']['path']);
        $query = "INSERT INTO member_card_text (id, text, main_image, gold, platinum, premium, height) VALUES (1, '" . $memberCardContent['text'] . "', '" . $mainImageMemberCard . "', '" . $goldImageMemberCard . "', '" . $platinumImageMemberCard . "', '" . $premiumImageMemberCard . "', " . $height . ")";
        $db->beginTransaction();
        $db->exec($query);
        $db->commit();
    }

}