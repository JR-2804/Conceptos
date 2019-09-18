<?php

namespace AppBundle\Controller;

use AppBundle\DTO\ProductDTO;
use AppBundle\Entity\Color;
use AppBundle\Entity\Image;
use AppBundle\Entity\Material;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route(name="new_product", path="/admin/product/new")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ProductType::class, new ProductDTO());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product();
            $product->setName($form->get('name')->getData());
            $product->setItem($form->get('item')->getData());
            $product->setDescription($form->get('description')->getData());
            $product->setCode($form->get('code')->getData());
            $product->setWeight($form->get('weight')->getData());
            $product->setShippingLimit($form->get('shippingLimit')->getData());
            $product->setIkeaPrice($form->get('ikeaPrice')->getData());

            $isFurniture = $form->get('isFurniture')->getData();
            if (is_string($isFurniture) && ('1' == $isFurniture || 'true' == $isFurniture)) {
                $isFurniture = true;
            } elseif (is_int($isFurniture) && 1 == $isFurniture) {
                $isFurniture = true;
            } elseif (is_bool($isFurniture) && $isFurniture) {
                $isFurniture = true;
            } else {
                $isFurniture = false;
            }
            $product->setIsFurniture($isFurniture);

            $isHighlight = $form->get('isHighlight')->getData();
            if (is_string($isHighlight) && ('1' == $isHighlight || 'true' == $isHighlight)) {
                $isHighlight = true;
            } elseif (is_int($isHighlight) && 1 == $isHighlight) {
                $isHighlight = true;
            } elseif (is_bool($isHighlight) && $isHighlight) {
                $isHighlight = true;
            } else {
                $isHighlight = false;
            }
            $product->setIsHighlight($isHighlight);
            $isFragile = $form->get('isFragile')->getData();
            if (is_string($isFragile) && ('1' == $isFragile || 'true' == $isFragile)) {
                $isFragile = true;
            } elseif (is_int($isFragile) && 1 == $isFragile) {
                $isFragile = true;
            } elseif (is_bool($isFragile) && $isFragile) {
                $isFragile = true;
            } else {
                $isFragile = false;
            }
            $product->setIsFragile($isFragile);
            $isAriplaneForniture = $form->get('isAriplaneForniture')->getData();
            if (is_string($isAriplaneForniture) && ('1' == $isAriplaneForniture || 'true' == $isAriplaneForniture)) {
                $isAriplaneForniture = true;
            } elseif (is_int($isAriplaneForniture) && 1 == $isAriplaneForniture) {
                $isAriplaneForniture = true;
            } elseif (is_bool($isAriplaneForniture) && $isAriplaneForniture) {
                $isAriplaneForniture = true;
            } else {
                $isAriplaneForniture = false;
            }
            $product->setIsAriplaneForniture($isAriplaneForniture);
            $isOversize = $form->get('isOversize')->getData();
            if (is_string($isOversize) && ('1' == $isOversize || 'true' == $isOversize)) {
                $isOversize = true;
            } elseif (is_int($isOversize) && 1 == $isOversize) {
                $isOversize = true;
            } elseif (is_bool($isOversize) && $isOversize) {
                $isOversize = true;
            } else {
                $isOversize = false;
            }
            $product->setIsOversize($isOversize);
            $isTableware = $form->get('isTableware')->getData();
            if (is_string($isTableware) && ('1' == $isTableware || 'true' == $isTableware)) {
                $isTableware = true;
            } elseif (is_int($isTableware) && 1 == $isTableware) {
                $isTableware = true;
            } elseif (is_bool($isTableware) && $isTableware) {
                $isTableware = true;
            } else {
                $isTableware = false;
            }
            $product->setIsTableware($isTableware);
            $isLamp = $form->get('isLamp')->getData();
            if (is_string($isLamp) && ('1' == $isLamp || 'true' == $isLamp)) {
                $isLamp = true;
            } elseif (is_int($isLamp) && 1 == $isLamp) {
                $isLamp = true;
            } elseif (is_bool($isLamp) && $isLamp) {
                $isLamp = true;
            } else {
                $isLamp = false;
            }
            $product->setIsLamp($isLamp);

            $config = $this->getDoctrine()->getRepository('AppBundle:Configuration')->find(1);
            $taxes = $product->getIkeaPrice() * ($config->getTaxTax() / 100);
            $shipping = $product->getWeight() * $config->getTicketPrice() / $config->getTotalWeight();
            $product->setShipping($shipping);
            $product->setTax($taxes);
            $product->setCalculatePrice($form->get('calculatePrice')->getData());
            $categories = $form->get('category')->getData();
            if (!is_array($categories)) {
                $categories = json_decode($categories, true);
            }
            $categoryText = '';
            foreach ($categories as $category) {
                $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($category);
                $product->addCategory($category);
                $categoryText .= $category->getName().' / ';

                $textParent = '';
                foreach ($category->getParents() as $parent) {
                    $textParent .= $parent->getName().' / ';
                }
                $textParent = substr($textParent, 0, strlen($textParent) - 2);
                $product->setParentCategoryText($textParent);
            }
            $product->setCategoryText(substr($categoryText, 0, strlen($categoryText) - 2));
            $product->setPrice($form->get('price')->getData());
            $image = $this->getDoctrine()->getRepository('AppBundle:Image')->find($form->get('image')->getData());
            $product->setMainImage($image);
            $images = json_decode($form->get('images')->getData(), true);
            foreach ($images as $img) {
                $imgDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($img['id']);
                $product->getImages()->add($imgDB);
            }
            $highlightImages = json_decode($form->get('highlightImages')->getData(), true);
            foreach ($highlightImages as $img) {
                $imgDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($img['id']);
                $product->getHighlightImages()->add($imgDB);
            }
            $product->setPopular(boolval($form->get('popular')->getData()));
            $product->setRecent(boolval($form->get('recent')->getData()));
            $product->setInStore(boolval($form->get('inStore')->getData()));
            if (boolval($form->get('inStore')->getData())) {
                $product->setStoreCount($form->get('countStore')->getData());
            }
            $color = $form->get('color')->getData();
            if (is_array($color)) {
                $color = $color[0];
            }
            $colorDB = $this->getDoctrine()->getRepository('AppBundle:Color')->find($color);
            if (null == $colorDB) {
                $colorDB = new Color();
                $colorDB->setName($color);
                $colorDB->addProduct($product);
                $this->getDoctrine()->getManager()->persist($colorDB);
            }
            $product->setColor($colorDB);
            $material = $form->get('material')->getData();
            if (is_array($material)) {
                $material = $material[0];
            }
            $materialDB = $this->getDoctrine()->getRepository('AppBundle:Material')->find($material);
            if (null == $materialDB) {
                $materialDB = new Material();
                $materialDB->setName($material);
                $materialDB->addProduct($product);
                $this->getDoctrine()->getManager()->persist($materialDB);
            }
            $product->setMaterial($materialDB);
            $categoriesFavorites = json_decode($form->get('favoritesCategories')->getData(), true);
            if (null != $categoriesFavorites) {
                foreach ($categoriesFavorites as $favorite) {
                    $categoryDB = $this->getDoctrine()->getRepository('AppBundle:Category')->find($favorite);
                    if (null != $categoryDB) {
                        $categoryDB->addFavoritesProduct($product);
                        $product->addFavoritesCategory($categoryDB);
                    }
                }
            }
            $this->getDoctrine()->getManager()->persist($product);
            $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
            $config->setLastProductUpdate(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('easyadmin', [
                'entity' => 'Product',
                'action' => 'list',
                'menuIndex' => $request->query->get('menuIndex'),
                'submenuIndex' => $request->query->get('submenuIndex'),
            ]);
        }
        $repoCategory = $this->getDoctrine()->getRepository('AppBundle:Category');
        $categories = $repoCategory->createQueryBuilder('c')
            ->where('c.parents IS NOT EMPTY')->getQuery()->getResult();
        $parents = $repoCategory->createQueryBuilder('c')
            ->where('c.subCategories IS NOT EMPTY')->getQuery()->getResult();

        return $this->render('::new_edit_product.html.twig', [
            'action' => 'new',
            'categories' => $categories,
            'parents' => $parents,
            'colors' => $this->getDoctrine()->getRepository('AppBundle:Color')->findAll(),
            'materials' => $this->getDoctrine()->getRepository('AppBundle:Material')->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="edit_product", path="/admin/product/edit/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $dto = new ProductDTO();
        $dto->setName($product->getName());
        $dto->setCode($product->getCode());
        $dto->setItem($product->getItem());
        $dto->setWeight($product->getWeight());
        $dto->setShippingLimit($product->getShippingLimit());
        $dto->setIkeaPrice($product->getIkeaPrice());
        $dto->setCalculatePrice($product->getCalculatePrice());
        $dto->setIsFurniture($product->getIsFurniture());
        $dto->setIsHighlight($product->getIsHighlight());
        $dto->setIsFragile($product->getIsFragile());
        $dto->setIsAriplaneForniture($product->getIsAriplaneForniture());
        $dto->setIsOversize($product->getIsOversize());
        $dto->setIsTableware($product->getIsTableware());
        $dto->setIsLamp($product->getIsLamp());

        $categories = [];
        foreach ($product->getCategories() as $category) {
            $categories[] = $category->getId();
        }
        $dto->setCategory(json_encode($categories));
        $dto->setPrice($product->getPrice());
        $dto->setDescription($product->getDescription());
        $dto->setPopular($product->getPopular());
        $dto->setRecent($product->getRecent());
        $dto->setInStore($product->getInStore());
        if ($product->getInStore()) {
            $dto->setCountStore($product->getStoreCount());
        }
        $favorites = [];
        foreach ($product->getFavoritesCategory() as $favorite) {
            $favorites[] = $favorite->getId();
        }
        $dto->setFavoritesCategories(json_encode($favorites));
        $color = $product->getColor();
        $dto->setColor(null != $color ? $color->getId() : null);
        $material = $product->getMaterial();
        $dto->setMaterial(null != $material ? $material->getId() : null);
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        $dto->setImage(json_encode([
            'id' => $product->getMainImage()->getId(),
            'name' => $product->getMainImage()->getOriginalName(),
            'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($product->getMainImage(), 'imageFile')),
            'path' => $helper->asset($product->getMainImage(), 'imageFile'),
        ]));
        $images = [];
        foreach ($product->getImages() as $image) {
            $images[] = [
                'id' => $image->getId(),
                'name' => $image->getOriginalName(),
                'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile')),
                'path' => $helper->asset($image, 'imageFile'),
            ];
        }
        $dto->setImages(json_encode($images));
        $highlightImages = [];
        foreach ($product->getHighlightImages() as $image) {
            $highlightImages[] = [
                'id' => $image->getId(),
                'name' => $image->getOriginalName(),
                'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile')),
                'path' => $helper->asset($image, 'imageFile'),
            ];
        }
        $dto->setHighlightImages(json_encode($highlightImages));
        $form = $this->createForm(ProductType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
            $productDB->setName($form->get('name')->getData());
            $productDB->setItem($form->get('item')->getData());
            $productDB->setCode($form->get('code')->getData());
            $productDB->setDescription($form->get('description')->getData());

            $isFurniture = $form->get('isFurniture')->getData();
            if (is_string($isFurniture) && ('1' == $isFurniture || 'true' == $isFurniture)) {
                $isFurniture = true;
            } elseif (is_int($isFurniture) && 1 == $isFurniture) {
                $isFurniture = true;
            } elseif (is_bool($isFurniture) && $isFurniture) {
                $isFurniture = true;
            } else {
                $isFurniture = false;
            }
            $productDB->setIsFurniture($isFurniture);

            $isHighlight = $form->get('isHighlight')->getData();
            if (is_string($isHighlight) && ('1' == $isHighlight || 'true' == $isHighlight)) {
                $isHighlight = true;
            } elseif (is_int($isHighlight) && 1 == $isHighlight) {
                $isHighlight = true;
            } elseif (is_bool($isHighlight) && $isHighlight) {
                $isHighlight = true;
            } else {
                $isHighlight = false;
            }
            $productDB->setIsHighlight($isHighlight);
            $isFragile = $form->get('isFragile')->getData();
            if (is_string($isFragile) && ('1' == $isFragile || 'true' == $isFragile)) {
                $isFragile = true;
            } elseif (is_int($isFragile) && 1 == $isFragile) {
                $isFragile = true;
            } elseif (is_bool($isFragile) && $isFragile) {
                $isFragile = true;
            } else {
                $isFragile = false;
            }
            $productDB->setIsFragile($isFragile);
            $isAriplaneForniture = $form->get('isAriplaneForniture')->getData();
            if (is_string($isAriplaneForniture) && ('1' == $isAriplaneForniture || 'true' == $isAriplaneForniture)) {
                $isAriplaneForniture = true;
            } elseif (is_int($isAriplaneForniture) && 1 == $isAriplaneForniture) {
                $isAriplaneForniture = true;
            } elseif (is_bool($isAriplaneForniture) && $isAriplaneForniture) {
                $isAriplaneForniture = true;
            } else {
                $isAriplaneForniture = false;
            }
            $productDB->setIsAriplaneForniture($isAriplaneForniture);
            $isOversize = $form->get('isOversize')->getData();
            if (is_string($isOversize) && ('1' == $isOversize || 'true' == $isOversize)) {
                $isOversize = true;
            } elseif (is_int($isOversize) && 1 == $isOversize) {
                $isOversize = true;
            } elseif (is_bool($isOversize) && $isOversize) {
                $isOversize = true;
            } else {
                $isOversize = false;
            }
            $productDB->setIsOversize($isOversize);
            $isTableware = $form->get('isTableware')->getData();
            if (is_string($isTableware) && ('1' == $isTableware || 'true' == $isTableware)) {
                $isTableware = true;
            } elseif (is_int($isTableware) && 1 == $isTableware) {
                $isTableware = true;
            } elseif (is_bool($isTableware) && $isTableware) {
                $isTableware = true;
            } else {
                $isTableware = false;
            }
            $productDB->setIsTableware($isTableware);
            $isLamp = $form->get('isLamp')->getData();
            if (is_string($isLamp) && ('1' == $isLamp || 'true' == $isLamp)) {
                $isLamp = true;
            } elseif (is_int($isLamp) && 1 == $isLamp) {
                $isLamp = true;
            } elseif (is_bool($isLamp) && $isLamp) {
                $isLamp = true;
            } else {
                $isLamp = false;
            }
            $productDB->setIsLamp($isLamp);

            $productDB->setWeight($form->get('weight')->getData());
            $productDB->setShippingLimit($form->get('shippingLimit')->getData());
            $productDB->setIkeaPrice($form->get('ikeaPrice')->getData());
            $productDB->setCalculatePrice($form->get('calculatePrice')->getData());

            $config = $this->getDoctrine()->getRepository('AppBundle:Configuration')->find(1);
            $taxes = $product->getIkeaPrice() * ($config->getTaxTax() / 100);
            $shipping = $product->getWeight() * $config->getTicketPrice() / $config->getTotalWeight();
            $product->setShipping($shipping);
            $product->setTax($taxes);

            $productDB->getCategories()->clear();

            $categories = $form->get('category')->getData();
            if (!is_array($categories)) {
                $categories = json_decode($categories, true);
            }
            $categoryText = '';
            $textParent = '';
            foreach ($categories as $category) {
                $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($category);
                $productDB->addCategory($category);
                $categoryText .= $category->getName().' / ';

                foreach ($category->getParents() as $parent) {
                    $textParent .= $parent->getName().' / ';
                }
                $textParent = substr($textParent, 0, strlen($textParent) - 2);
            }
            $productDB->setCategoryText(substr($categoryText, 0, strlen($categoryText) - 2));

            $productDB->setParentCategoryText($textParent);
            $productDB->setCategoryText($categoryText);

            $productDB->setPrice($form->get('price')->getData());
            $image = $this->getDoctrine()->getRepository('AppBundle:Image')->find($form->get('image')->getData());
            $productDB->setMainImage($image);
            $images = json_decode($form->get('images')->getData(), true);
            foreach ($images as $img) {
                $imgDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($img['id']);
                if (!$product->getImages()->contains($imgDB)) {
                    $product->getImages()->add($imgDB);
                }
            }
            $highlightImages = json_decode($form->get('highlightImages')->getData(), true);
            foreach ($highlightImages as $img) {
                $imgDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($img['id']);
                if (!$product->getHighlightImages()->contains($imgDB)) {
                    $product->getHighlightImages()->add($imgDB);
                }
            }
            $imagesToDelete = json_decode($form->get('imagesToDelete')->getData(), true);
            foreach ($imagesToDelete as $item) {
                $imgDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($item['id']);
                $this->getDoctrine()->getManager()->remove($imgDB);
            }
            $product->setPopular(boolval($form->get('popular')->getData()));
            $product->setRecent(boolval($form->get('recent')->getData()));
            $product->setInStore(boolval($form->get('inStore')->getData()));
            if (boolval($form->get('inStore')->getData())) {
                $product->setStoreCount($form->get('countStore')->getData());
            }

            $color = $form->get('color')->getData();
            if (is_array($color)) {
                $color = $color[0];
            }
            $colorDB = $this->getDoctrine()->getRepository('AppBundle:Color')->find($color);
            if (null == $colorDB) {
                $colorDB = new Color();
                $colorDB->setName($color);
                $this->getDoctrine()->getManager()->persist($colorDB);
            }
            if (!$colorDB->getProducts()->contains($productDB)) {
                $colorDB->addProduct($productDB);
            }
            $productDB->setColor($colorDB);
            $material = $form->get('material')->getData();
            if (is_array($material)) {
                $material = $material[0];
            }
            $materialDB = $this->getDoctrine()->getRepository('AppBundle:Material')->find($material);
            if (null == $materialDB) {
                $materialDB = new Material();
                $materialDB->setName($material);
                $this->getDoctrine()->getManager()->persist($materialDB);
            }
            if (!$materialDB->getProducts()->contains($productDB)) {
                $materialDB->addProduct($productDB);
            }
            $product->setMaterial($materialDB);
            $favoritesCategory = json_decode($form->get('favoritesCategories')->getData(), true);
            foreach ($product->getFavoritesCategory() as $categoryFav) {
                $categoryFav->removeFavoritesProduct($product);
            }
            $product->getFavoritesCategory()->clear();
            if (null != $favoritesCategory) {
                foreach ($favoritesCategory as $favorite) {
                    $categoryDB = $this->getDoctrine()->getRepository('AppBundle:Category')->find($favorite);
                    if (null != $categoryDB) {
                        $categoryDB->addFavoritesProduct($product);
                        $product->addFavoritesCategory($categoryDB);
                    }
                }
            }
            $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
            $config->setLastProductUpdate(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('easyadmin', [
                'entity' => 'Product',
                'action' => 'list',
            ]);
        }
        $repoCategory = $this->getDoctrine()->getRepository('AppBundle:Category');
        $categories = $repoCategory->createQueryBuilder('c')
            ->where('c.parents IS NOT EMPTY')->getQuery()->getResult();
        $parents = $repoCategory->createQueryBuilder('c')
            ->where('c.subCategories IS NOT EMPTY')->getQuery()->getResult();

        return $this->render('::new_edit_product.html.twig', [
            'action' => 'edit',
            'categories' => $categories,
            'parents' => $parents,
            'colors' => $this->getDoctrine()->getRepository('AppBundle:Color')->findAll(),
            'materials' => $this->getDoctrine()->getRepository('AppBundle:Material')->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="update_parent_text", path="/product/parent/text")
     *
     * @return JsonResponse
     */
    public function updateParentTextAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $product) {
            $category = $product->getCategory();
            $textParent = '';
            foreach ($category->getParents() as $parent) {
                $textParent .= $parent->getName().' / ';
            }
            $textParent = substr($textParent, 0, strlen($textParent) - 2);
            $product->setParentCategoryText($textParent);
        }
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse('ok');
    }

    /**
     * @Route(name="upload", path="/upload")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $upload = new UploadedFile($_FILES['file']['tmp_name'], $_FILES['file']['name'], $_FILES['file']['type'], $_FILES['file']['size'], $_FILES['file']['error']);
        $image = new Image();
        $image->setImage($_FILES['file']['name']);
        $image->setOriginalName($_FILES['file']['name']);
        $image->setImageFile($upload);
        $this->getDoctrine()->getManager()->persist($image);
        $this->getDoctrine()->getManager()->flush();
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        $imagePath = $this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile');
        $clientExtention = strtoupper($upload->getClientMimeType());
        $maxWidth = 500;
        $maxHeight = 500;
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
        $data = [
            'id' => $image->getId(),
            'name' => $image->getOriginalName(),
            'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile')),
            'path' => $helper->asset($image, 'imageFile'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route(name="export_db", path="/admin/product/export")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $this->get('export_service')->export($this->getParameter('kernel.root_dir'));

        $flash = "Base de datos generada correctamente <a href='/download/db/conceptos-lasted.conceptos'>Descargar</a>";
        $this->addFlash('success', $flash);

        return $this->redirectToRoute('easyadmin', [
            'entity' => 'Product',
            'action' => 'list',
            'menuIndex' => 0,
            'submenuIndex' => -1,
        ]);
    }

    /**
     * @Route(name="reduce_images", path="/reduce/image")
     *
     * @return JsonResponse
     */
    public function reduceFileAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $product) {
            $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
            $image = $product->getMainImage();
            $imagePath = $this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile');
            $clientExtention = strtoupper(substr($image->getOriginalName(), strrpos($image->getOriginalName(), '.'), strlen($image->getOriginalName())));
            $maxWidth = 500;
            $maxHeight = 500;
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
            foreach ($product->getImages() as $image) {
                $imagePath = $this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile');
                $clientExtention = strtoupper(substr($image->getOriginalName(), strrpos($image->getOriginalName(), '.'), strlen($image->getOriginalName())));
                $maxWidth = 500;
                $maxHeight = 500;
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
            }
        }

        return new JsonResponse('ok');
    }

    /**
     * @Route(name="reduce_image_category", path="/reduce/category/image")
     *
     * @return JsonResponse
     */
    public function reduceCategoryFileAction()
    {
        $categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        foreach ($categories as $image) {
            $imagePath = $this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile');
            $clientExtention = strtoupper(substr($image->getImage(), strrpos($image->getImage(), '.'), strlen($image->getImage())));
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
            foreach ($image->getSubCategories() as $subCategory) {
                $imagePath = $this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($subCategory, 'imageFile');
                $clientExtention = strtoupper(substr($subCategory->getImage(), strrpos($subCategory->getImage(), '.'), strlen($subCategory->getImage())));
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
            }
        }

        return new JsonResponse('ok');
    }

    /**
     * @Route(name="update_category_reference", path="/update/category/reference")
     *
     * @return JsonResponse
     */
    public function updateCategoryReferences()
    {
        $this->get('product_service')->updateCategoryReference();

        return new JsonResponse('ok');
    }

    /**
     * @Route(name="calculate_product_price", path="/product/price/calculate", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function calculatePriceAction(Request $request)
    {
        $weight = $request->request->get('weight');
        $weight = floatval($weight);
        $ikeaPrice = $request->request->get('ikeaPrice');
        $ikeaPrice = floatval($ikeaPrice);
        $isFurniture = $request->request->get('isFurniture');
        if (is_string($isFurniture) && 'true' == $isFurniture) {
            $isFurniture = true;
        } elseif (is_int($isFurniture) && 1 == $isFurniture) {
            $isFurniture = true;
        } elseif (is_bool($isFurniture) && $isFurniture) {
            $isFurniture = true;
        } else {
            $isFurniture = false;
        }
        $isFragile = $request->request->get('isFragile');
        if (is_string($isFragile) && 'true' == $isFragile) {
            $isFragile = true;
        } elseif (is_int($isFragile) && 1 == $isFragile) {
            $isFragile = true;
        } elseif (is_bool($isFragile) && $isFragile) {
            $isFragile = true;
        } else {
            $isFragile = false;
        }
        $isAriplaneForniture = $request->request->get('isAriplaneForniture');
        if (is_string($isAriplaneForniture) && 'true' == $isAriplaneForniture) {
            $isAriplaneForniture = true;
        } elseif (is_int($isAriplaneForniture) && 1 == $isAriplaneForniture) {
            $isAriplaneForniture = true;
        } elseif (is_bool($isAriplaneForniture) && $isAriplaneForniture) {
            $isAriplaneForniture = true;
        } else {
            $isAriplaneForniture = false;
        }
        $isOversize = $request->request->get('isOversize');
        if (is_string($isOversize) && 'true' == $isOversize) {
            $isOversize = true;
        } elseif (is_int($isOversize) && 1 == $isOversize) {
            $isOversize = true;
        } elseif (is_bool($isOversize) && $isOversize) {
            $isOversize = true;
        } else {
            $isOversize = false;
        }
        $isTableware = $request->request->get('isTableware');
        if (is_string($isTableware) && 'true' == $isTableware) {
            $isTableware = true;
        } elseif (is_int($isTableware) && 1 == $isTableware) {
            $isTableware = true;
        } elseif (is_bool($isTableware) && $isTableware) {
            $isTableware = true;
        } else {
            $isTableware = false;
        }
        $isLamp = $request->request->get('isLamp');
        if (is_string($isLamp) && 'true' == $isLamp) {
            $isLamp = true;
        } elseif (is_int($isLamp) && 1 == $isLamp) {
            $isLamp = true;
        } elseif (is_bool($isLamp) && $isLamp) {
            $isLamp = true;
        } else {
            $isLamp = false;
        }

        $price = $this->get('product_service')->calculateProductPrice(
            $weight,
            $ikeaPrice,
            $isFurniture,
            $isFragile,
            $isAriplaneForniture,
            $isOversize,
            $isTableware,
            $isLamp
        );

        return new JsonResponse($price);
    }

    /**
     * @Route(path="/admin/furniture/price")
     */
    public function updateFurniturePrice()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->join('c.parents', 'pr')
            ->where("pr.name = 'MOBILIARIO'")
            ->getQuery()->getResult();

        foreach ($products as $product) {
            $finalPrice = $this->get('product_service')->calculateProductPrice(
                $product->getWeight(),
                $product->getIkeaPrice(),
                true,
                $product->getIsFragile(),
                $product->getIsAriplaneForniture(),
                $product->getIsOversize(),
                $product->getIsTableware(),
                $product->getIsLamp()
            );
            $product->setPrice($finalPrice);
            $product->setIsFurniture(true);
        }
        $em->flush();

        return new JsonResponse('ok');
    }
}
