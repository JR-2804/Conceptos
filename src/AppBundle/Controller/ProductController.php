<?php

namespace AppBundle\Controller;

use AppBundle\DTO\ProductDTO;
use AppBundle\Entity\Color;
use AppBundle\Entity\Image;
use AppBundle\Entity\Material;
use AppBundle\Entity\Product;
use AppBundle\Entity\ComboProduct;
use AppBundle\Entity\ProductMetaname;
use AppBundle\Entity\ProductCode;
use AppBundle\Entity\ProductColor;
use AppBundle\Entity\ProductMaterial;
use AppBundle\Entity\ProductRoom;
use AppBundle\Entity\ComplementaryProduct;
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
            $product->setBrand($form->get('brand')->getData());
            $product->setPriority($form->get('priority')->getData());
            $product->setItem($form->get('item')->getData());
            $product->setDescription($form->get('description')->getData());
            $product->setCode($form->get('code')->getData());
            $product->setWeight($form->get('weight')->getData());
            $product->setShippingLimit($form->get('shippingLimit')->getData());
            $product->setIkeaPrice($form->get('ikeaPrice')->getData());
            $product->setNumberOfPackages($form->get('numberOfPackages')->getData());
            $product->setWidthLeft($form->get('widthLeft')->getData());
            $product->setWidthRight($form->get('widthRight')->getData());
            $product->setWidth($form->get('width')->getData());
            $product->setHeightMin($form->get('heightMin')->getData());
            $product->setHeightMax($form->get('heightMax')->getData());
            $product->setHeight($form->get('height')->getData());
            $product->setDeepMin($form->get('deepMin')->getData());
            $product->setDeepMax($form->get('deepMax')->getData());
            $product->setDeep($form->get('deep')->getData());
            $product->setLength($form->get('length')->getData());
            $product->setDiameter($form->get('diameter')->getData());
            $product->setMaxLoad($form->get('maxLoad')->getData());
            $product->setArea($form->get('area')->getData());
            $product->setThickness($form->get('thickness')->getData());
            $product->setVolume($form->get('volume')->getData());
            $product->setSurfaceDensity($form->get('surfaceDensity')->getData());

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
            $isMattress = $form->get('isMattress')->getData();
            if (is_string($isMattress) && ('1' == $isMattress || 'true' == $isMattress)) {
                $isMattress = true;
            } elseif (is_int($isMattress) && 1 == $isMattress) {
                $isMattress = true;
            } elseif (is_bool($isMattress) && $isMattress) {
                $isMattress = true;
            } else {
                $isMattress = false;
            }
            $product->setIsMattress($isMattress);
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
            $isAriplaneMattress = $form->get('isAriplaneMattress')->getData();
            if (is_string($isAriplaneMattress) && ('1' == $isAriplaneMattress || 'true' == $isAriplaneMattress)) {
                $isAriplaneMattress = true;
            } elseif (is_int($isAriplaneMattress) && 1 == $isAriplaneMattress) {
                $isAriplaneMattress = true;
            } elseif (is_bool($isAriplaneMattress) && $isAriplaneMattress) {
                $isAriplaneMattress = true;
            } else {
                $isAriplaneMattress = false;
            }
            $product->setIsAriplaneMattress($isAriplaneMattress);
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
            $isFaucet = $form->get('isFaucet')->getData();
            if (is_string($isFaucet) && ('1' == $isFaucet || 'true' == $isFaucet)) {
                $isFaucet = true;
            } elseif (is_int($isFaucet) && 1 == $isFaucet) {
                $isFaucet = true;
            } elseif (is_bool($isFaucet) && $isFaucet) {
                $isFaucet = true;
            } else {
                $isFaucet = false;
            }
            $product->setIsFaucet($isFaucet);
            $isGrill = $form->get('isGrill')->getData();
            if (is_string($isGrill) && ('1' == $isGrill || 'true' == $isGrill)) {
                $isGrill = true;
            } elseif (is_int($isGrill) && 1 == $isGrill) {
                $isGrill = true;
            } elseif (is_bool($isGrill) && $isGrill) {
                $isGrill = true;
            } else {
                $isGrill = false;
            }
            $product->setIsGrill($isGrill);
            $isShelf = $form->get('isShelf')->getData();
            if (is_string($isShelf) && ('1' == $isShelf || 'true' == $isShelf)) {
                $isShelf = true;
            } elseif (is_int($isShelf) && 1 == $isShelf) {
                $isShelf = true;
            } elseif (is_bool($isShelf) && $isShelf) {
                $isShelf = true;
            } else {
                $isShelf = false;
            }
            $product->setIsShelf($isShelf);
            $isDesk = $form->get('isDesk')->getData();
            if (is_string($isDesk) && ('1' == $isDesk || 'true' == $isDesk)) {
                $isDesk = true;
            } elseif (is_int($isDesk) && 1 == $isDesk) {
                $isDesk = true;
            } elseif (is_bool($isDesk) && $isDesk) {
                $isDesk = true;
            } else {
                $isDesk = false;
            }
            $product->setIsDesk($isDesk);
            $isBookcase = $form->get('isBookcase')->getData();
            if (is_string($isBookcase) && ('1' == $isBookcase || 'true' == $isBookcase)) {
                $isBookcase = true;
            } elseif (is_int($isBookcase) && 1 == $isBookcase) {
                $isBookcase = true;
            } elseif (is_bool($isBookcase) && $isBookcase) {
                $isBookcase = true;
            } else {
                $isBookcase = false;
            }
            $product->setIsBookcase($isBookcase);
            $isComoda = $form->get('isComoda')->getData();
            if (is_string($isComoda) && ('1' == $isComoda || 'true' == $isComoda)) {
                $isComoda = true;
            } elseif (is_int($isComoda) && 1 == $isComoda) {
                $isComoda = true;
            } elseif (is_bool($isComoda) && $isComoda) {
                $isComoda = true;
            } else {
                $isComoda = false;
            }
            $product->setIsComoda($isComoda);
            $isRepisa = $form->get('isRepisa')->getData();
            if (is_string($isRepisa) && ('1' == $isRepisa || 'true' == $isRepisa)) {
                $isRepisa = true;
            } elseif (is_int($isRepisa) && 1 == $isRepisa) {
                $isRepisa = true;
            } elseif (is_bool($isRepisa) && $isRepisa) {
                $isRepisa = true;
            } else {
                $isRepisa = false;
            }
            $product->setIsRepisa($isRepisa);

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
            $product->setIsDisabled(boolval($form->get('isDisabled')->getData()));
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
            $comboProducts = json_decode($form->get('comboProducts')->getData(), true);
            if (null != $comboProducts) {
                foreach ($comboProducts as $comboProduct) {
                    $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($comboProduct["id"]);
                    if (null != $productDB) {
                        $combo = new ComboProduct();
                        $combo->setParentProduct($product);
                        $combo->setProduct($productDB);
                        $combo->setCount($comboProduct["count"]);
                        $this->getDoctrine()->getManager()->persist($combo);

                        $product->addComboProduct($combo);
                    }
                }
            }
            $complementaryProducts = json_decode($form->get('complementaryProducts')->getData(), true);
            if (null != $complementaryProducts) {
                foreach ($complementaryProducts as $complementaryProductId) {
                    $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($complementaryProductId);
                    if (null != $productDB) {
                        $complementaryProduct = new ComplementaryProduct();
                        $complementaryProduct->setParentProduct($product);
                        $complementaryProduct->setProduct($productDB);
                        $this->getDoctrine()->getManager()->persist($complementaryProduct);

                        $product->addComplementaryProduct($complementaryProduct);
                    }
                }
            }
            $metaNames = json_decode($form->get('metaNames')->getData(), true);
            foreach ($product->getMetaNames() as $metaName) {
                $product->removeMetaName($metaName);
                $this->getDoctrine()->getManager()->remove($metaName);
            }
            $product->getMetaNames()->clear();
            if ($metaNames != null) {
                foreach ($metaNames as $metaName) {
                    $meta = new ProductMetaname();
                    $meta->setName($metaName["name"]);
                    $meta->setProduct($product);
                    $this->getDoctrine()->getManager()->persist($meta);

                    $product->addMetaName($meta);
                }
            }
            $codes = json_decode($form->get('codes')->getData(), true);
            foreach ($product->getCodes() as $code) {
                $product->removeCode($code);
                $this->getDoctrine()->getManager()->remove($code);
            }
            $product->getCodes()->clear();
            if ($codes != null) {
                foreach ($codes as $code) {
                    $productCode = new ProductCode();
                    $productCode->setCode($code);
                    $productCode->setProduct($product);
                    $this->getDoctrine()->getManager()->persist($productCode);

                    $product->addCode($productCode);
                }
            }
            $colors = json_decode($form->get('colors')->getData(), true);
            foreach ($product->getColors() as $color) {
                $product->removeColor($color);
                $this->getDoctrine()->getManager()->remove($color);
            }
            $product->getColors()->clear();
            if ($colors != null) {
                foreach ($colors as $color) {
                    $productColor = new ProductColor();
                    $productColor->setColor($color);
                    $productColor->setProduct($product);
                    $this->getDoctrine()->getManager()->persist($productColor);

                    $product->addColor($productColor);
                }
            }
            $materials = json_decode($form->get('materials')->getData(), true);
            foreach ($product->getMaterials() as $material) {
                $product->removeMaterial($material);
                $this->getDoctrine()->getManager()->remove($material);
            }
            $product->getMaterials()->clear();
            if ($materials != null) {
                foreach ($materials as $material) {
                    $productMaterial = new ProductMaterial();
                    $productMaterial->setMaterial($material);
                    $productMaterial->setProduct($product);
                    $this->getDoctrine()->getManager()->persist($productMaterial);

                    $product->addMaterial($productMaterial);
                }
            }
            $rooms = json_decode($form->get('rooms')->getData(), true);
            foreach ($product->getRooms() as $room) {
                $product->removeRoom($room);
                $this->getDoctrine()->getManager()->remove($room);
            }
            $product->getRooms()->clear();
            if ($rooms != null) {
                foreach ($rooms as $room) {
                    $productRoom = new ProductRoom();
                    $productRoom->setRoom($room);
                    $productRoom->setProduct($product);
                    $this->getDoctrine()->getManager()->persist($productRoom);

                    $product->addRoom($productRoom);
                }
            }
            $classification = $this->getDoctrine()->getRepository('AppBundle:Category')->find($form->get('classification')->getData());
            $product->setClassification($classification);
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
            'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
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
        $dto->setBrand($product->getBrand());
        $dto->setPriority($product->getPriority());
        $dto->setCode($product->getCode());
        $dto->setItem($product->getItem());
        $dto->setWeight($product->getWeight());
        $dto->setShippingLimit($product->getShippingLimit());
        $dto->setIkeaPrice($product->getIkeaPrice());
        $dto->setCalculatePrice($product->getCalculatePrice());
        $dto->setIsFurniture($product->getIsFurniture());
        $dto->setIsMattress($product->getIsMattress());
        $dto->setIsHighlight($product->getIsHighlight());
        $dto->setIsFragile($product->getIsFragile());
        $dto->setIsAriplaneForniture($product->getIsAriplaneForniture());
        $dto->setIsAriplaneMattress($product->getIsAriplaneMattress());
        $dto->setIsOversize($product->getIsOversize());
        $dto->setIsTableware($product->getIsTableware());
        $dto->setIsLamp($product->getIsLamp());
        $dto->setIsFaucet($product->getIsFaucet());
        $dto->setIsGrill($product->getIsGrill());
        $dto->setIsShelf($product->getIsShelf());
        $dto->setIsDesk($product->getIsDesk());
        $dto->setIsBookcase($product->getIsBookcase());
        $dto->setIsComoda($product->getIsComoda());
        $dto->setIsRepisa($product->getIsRepisa());
        $dto->setNumberOfPackages($product->getNumberOfPackages());
        $dto->setWidthLeft($product->getWidthLeft());
        $dto->setWidthRight($product->getWidthRight());
        $dto->setWidth($product->getWidth());
        $dto->setHeightMin($product->getHeightMin());
        $dto->setHeightMax($product->getHeightMax());
        $dto->setHeight($product->getHeight());
        $dto->setDeepMin($product->getDeepMin());
        $dto->setDeepMax($product->getDeepMax());
        $dto->setDeep($product->getDeep());
        $dto->setLength($product->getLength());
        $dto->setDiameter($product->getDiameter());
        $dto->setMaxLoad($product->getMaxLoad());
        $dto->setArea($product->getArea());
        $dto->setThickness($product->getThickness());
        $dto->setVolume($product->getVolume());
        $dto->setSurfaceDensity($product->getSurfaceDensity());

        $categories = [];
        foreach ($product->getCategories() as $category) {
            $categories[] = $category->getId();
        }
        $dto->setPrice($product->getPrice());
        $dto->setDescription($product->getDescription());
        $dto->setPopular($product->getPopular());
        $dto->setIsDisabled($product->getIsDisabled());
        $dto->setRecent($product->getRecent());
        $dto->setInStore($product->getInStore());
        if ($product->getInStore()) {
            $dto->setCountStore($product->getStoreCount());
        }

        $similarProducts = [];
        foreach ($product->getSimilarProducts() as $similarProduct) {
            $similarProducts[] = $similarProduct->getId();
        }
        $dto->setSimilarProducts(json_encode($similarProducts));


        $favorites = [];
        foreach ($product->getFavoritesCategory() as $favorite) {
            $favorites[] = $favorite->getId();
        }
        $dto->setFavoritesCategories(json_encode($favorites));
        $comboProducts = [];
        foreach ($product->getComboProducts() as $comboProduct) {
            $comboProducts[] = [
              "id" => $comboProduct->getProduct()->getId(),
              "code" => $comboProduct->getProduct()->getCode(),
              "count" => $comboProduct->getCount(),
              "price" => $comboProduct->getProduct()->getPrice(),
            ];
        }
        $dto->setComboProducts(json_encode($comboProducts));
        $complementaryProducts = [];
        foreach ($product->getComplementaryProducts() as $complementaryProduct) {
            $complementaryProducts[] = $complementaryProduct->getProduct()->getId();
        }
        $dto->setComplementaryProducts(json_encode($complementaryProducts));
        $color = $product->getColor();
        $dto->setColor(null != $color ? $color->getId() : null);
        $material = $product->getMaterial();
        $dto->setMaterial(null != $material ? $material->getId() : null);
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        if ($product->getMainImage()) {
          $dto->setImage(json_encode([
              'id' => $product->getMainImage()->getId(),
              'name' => $product->getMainImage()->getOriginalName(),
              'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($product->getMainImage(), 'imageFile')),
              'path' => $helper->asset($product->getMainImage(), 'imageFile'),
          ]));
        }
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
        $metaNames = [];
        foreach ($product->getMetaNames() as $metaName) {
            $metaNames[] = [
              "name" => $metaName->getName(),
            ];
        }
        $dto->setMetaNames(json_encode($metaNames));
        $dto->setCategory(json_encode($categories));
        $codes = [];
        foreach ($product->getCodes() as $code) {
            $codes[] = $code->getCode();
        }
        $dto->setCodes(json_encode($codes));
        $colors = [];
        foreach ($product->getColors() as $color) {
            $colors[] = $color->getColor();
        }
        $dto->setColors(json_encode($colors));
        $materials = [];
        foreach ($product->getMaterials() as $material) {
            $materials[] = $material->getMaterial();
        }
        $dto->setMaterials(json_encode($materials));
        $rooms = [];
        foreach ($product->getRooms() as $room) {
            $rooms[] = $room->getRoom();
        }
        $classification = $product->getClassification();
        if ($product->getClassification()) {
          $dto->setClassification(null != $classification ? $classification->getId() : null);
        }
        $dto->setRooms(json_encode($rooms));
        $form = $this->createForm(ProductType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
            $productDB->setName($form->get('name')->getData());
            $productDB->setBrand($form->get('brand')->getData());
            $productDB->setPriority($form->get('priority')->getData());
            $productDB->setItem($form->get('item')->getData());
            $productDB->setCode($form->get('code')->getData());
            $productDB->setDescription($form->get('description')->getData());
            $productDB->setNumberOfPackages($form->get('numberOfPackages')->getData());
            $productDB->setWidthLeft($form->get('widthLeft')->getData());
            $productDB->setWidthRight($form->get('widthRight')->getData());
            $productDB->setWidth($form->get('width')->getData());
            $productDB->setHeightMin($form->get('heightMin')->getData());
            $productDB->setHeightMax($form->get('heightMax')->getData());
            $productDB->setHeight($form->get('height')->getData());
            $productDB->setDeepMin($form->get('deepMin')->getData());
            $productDB->setDeepMax($form->get('deepMax')->getData());
            $productDB->setDeep($form->get('deep')->getData());
            $productDB->setLength($form->get('length')->getData());
            $productDB->setDiameter($form->get('diameter')->getData());
            $productDB->setMaxLoad($form->get('maxLoad')->getData());
            $productDB->setArea($form->get('area')->getData());
            $productDB->setThickness($form->get('thickness')->getData());
            $productDB->setVolume($form->get('volume')->getData());
            $productDB->setSurfaceDensity($form->get('surfaceDensity')->getData());

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

            $isMattress = $form->get('isMattress')->getData();
            if (is_string($isMattress) && ('1' == $isMattress || 'true' == $isMattress)) {
                $isMattress = true;
            } elseif (is_int($isMattress) && 1 == $isMattress) {
                $isMattress = true;
            } elseif (is_bool($isMattress) && $isMattress) {
                $isMattress = true;
            } else {
                $isMattress = false;
            }
            $productDB->setIsMattress($isMattress);

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
            $isAriplaneMattress = $form->get('isAriplaneMattress')->getData();
            if (is_string($isAriplaneMattress) && ('1' == $isAriplaneMattress || 'true' == $isAriplaneMattress)) {
                $isAriplaneMattress = true;
            } elseif (is_int($isAriplaneMattress) && 1 == $isAriplaneMattress) {
                $isAriplaneMattress = true;
            } elseif (is_bool($isAriplaneMattress) && $isAriplaneMattress) {
                $isAriplaneMattress = true;
            } else {
                $isAriplaneMattress = false;
            }
            $productDB->setIsAriplaneMattress($isAriplaneMattress);
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
            $isFaucet = $form->get('isFaucet')->getData();
            if (is_string($isFaucet) && ('1' == $isFaucet || 'true' == $isFaucet)) {
                $isFaucet = true;
            } elseif (is_int($isFaucet) && 1 == $isFaucet) {
                $isFaucet = true;
            } elseif (is_bool($isFaucet) && $isFaucet) {
                $isFaucet = true;
            } else {
                $isFaucet = false;
            }
            $product->setIsFaucet($isFaucet);
            $isGrill = $form->get('isGrill')->getData();
            if (is_string($isGrill) && ('1' == $isGrill || 'true' == $isGrill)) {
                $isGrill = true;
            } elseif (is_int($isGrill) && 1 == $isGrill) {
                $isGrill = true;
            } elseif (is_bool($isGrill) && $isGrill) {
                $isGrill = true;
            } else {
                $isGrill = false;
            }
            $product->setIsGrill($isGrill);
            $isShelf = $form->get('isShelf')->getData();
            if (is_string($isShelf) && ('1' == $isShelf || 'true' == $isShelf)) {
                $isShelf = true;
            } elseif (is_int($isShelf) && 1 == $isShelf) {
                $isShelf = true;
            } elseif (is_bool($isShelf) && $isShelf) {
                $isShelf = true;
            } else {
                $isShelf = false;
            }
            $product->setIsShelf($isShelf);
            $isDesk = $form->get('isDesk')->getData();
            if (is_string($isDesk) && ('1' == $isDesk || 'true' == $isDesk)) {
                $isDesk = true;
            } elseif (is_int($isDesk) && 1 == $isDesk) {
                $isDesk = true;
            } elseif (is_bool($isDesk) && $isDesk) {
                $isDesk = true;
            } else {
                $isDesk = false;
            }
            $product->setIsDesk($isDesk);
            $isBookcase = $form->get('isBookcase')->getData();
            if (is_string($isBookcase) && ('1' == $isBookcase || 'true' == $isBookcase)) {
                $isBookcase = true;
            } elseif (is_int($isBookcase) && 1 == $isBookcase) {
                $isBookcase = true;
            } elseif (is_bool($isBookcase) && $isBookcase) {
                $isBookcase = true;
            } else {
                $isBookcase = false;
            }
            $product->setIsBookcase($isBookcase);
            $isComoda = $form->get('isComoda')->getData();
            if (is_string($isComoda) && ('1' == $isComoda || 'true' == $isComoda)) {
                $isComoda = true;
            } elseif (is_int($isComoda) && 1 == $isComoda) {
                $isComoda = true;
            } elseif (is_bool($isComoda) && $isComoda) {
                $isComoda = true;
            } else {
                $isComoda = false;
            }
            $product->setIsComoda($isComoda);
            $isRepisa = $form->get('isRepisa')->getData();
            if (is_string($isRepisa) && ('1' == $isRepisa || 'true' == $isRepisa)) {
                $isRepisa = true;
            } elseif (is_int($isRepisa) && 1 == $isRepisa) {
                $isRepisa = true;
            } elseif (is_bool($isRepisa) && $isRepisa) {
                $isRepisa = true;
            } else {
                $isRepisa = false;
            }
            $product->setIsRepisa($isRepisa);
            $isDisabled = $form->get('isDisabled')->getData();
            if (is_string($isDisabled) && ('1' == $isDisabled || 'true' == $isDisabled)) {
                $isComoda = true;
            } elseif (is_int($isDisabled) && 1 == $isDisabled) {
                $isComoda = true;
            } elseif (is_bool($isDisabled) && $isDisabled) {
                $isDisabled = true;
            } else {
                $isDisabled = false;
            }
            $product->setIsDisabled($isDisabled);

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



            $similarProductIds = json_decode($form->get('similarProducts')->getData(), true);
            $previousSimilarProductIds = [];

            foreach ($product->getSimilarProducts() as $similarProduct) {
                $previousSimilarProductIds[] = $similarProduct->getid();
                $product->removeSimilarProduct($similarProduct);
            }

            foreach ($previousSimilarProductIds as $previousSimilarProductId){
                $previousSimilarProduct = $this->getDoctrine()->getRepository('AppBundle:Product')->find($previousSimilarProductId);
                foreach ($previousSimilarProduct->getSimilarProducts() as $previousSimilarProductSimilarProduct)
                    $previousSimilarProduct->removeSimilarProduct($previousSimilarProductSimilarProduct);
            }

            if ($similarProductIds != null) {
                foreach ($similarProductIds as $similarProductId) {
                    $similarProduct = $this->getDoctrine()->getRepository('AppBundle:Product')->find($similarProductId);

                    $product->addSimilarProduct($similarProduct);
                    $similarProduct->addSimilarProduct($product);

                    foreach ($similarProductIds as $similarProductOtherId) {
                        if ($similarProductOtherId == $similarProductId)
                            continue;

                        $similarProductOther = $this->getDoctrine()->getRepository('AppBundle:Product')->find($similarProductOtherId);
                        $similarProduct->addSimilarProduct($similarProductOther);
                    }

                    $this->getDoctrine()->getManager()->persist($similarProduct);
                }

                $this->getDoctrine()->getManager()->persist($product);
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
            $comboProducts = json_decode($form->get('comboProducts')->getData(), true);
            foreach ($product->getComboProducts() as $comboProduct) {
                $product->removeComboProduct($comboProduct);
                $this->getDoctrine()->getManager()->remove($comboProduct);
            }
            $product->getComboProducts()->clear();
            if ($comboProducts != null) {
                foreach ($comboProducts as $comboProduct) {
                  $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($comboProduct["id"]);
                  if (null != $productDB) {
                      $combo = new ComboProduct();
                      $combo->setParentProduct($product);
                      $combo->setProduct($productDB);
                      $combo->setCount($comboProduct["count"]);
                      $this->getDoctrine()->getManager()->persist($combo);

                      $product->addComboProduct($combo);
                  }
                }
            }
            $complementaryProducts = json_decode($form->get('complementaryProducts')->getData(), true);
            foreach ($product->getComplementaryProducts() as $complementaryProduct) {
                $product->removeComplementaryProduct($complementaryProduct);
                $this->getDoctrine()->getManager()->remove($complementaryProduct);
            }
            $product->getComplementaryProducts()->clear();
            if ($complementaryProducts != null) {
                foreach ($complementaryProducts as $complementaryProductId) {
                  $productDB = $this->getDoctrine()->getRepository('AppBundle:Product')->find($complementaryProductId);
                  if (null != $productDB) {
                      $complementaryProduct = new ComplementaryProduct();
                      $complementaryProduct->setParentProduct($product);
                      $complementaryProduct->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($complementaryProduct);

                      $product->addComplementaryProduct($complementaryProduct);
                  }
                }
            }
            $metaNames = json_decode($form->get('metaNames')->getData(), true);
            foreach ($product->getMetaNames() as $metaName) {
                $product->removeMetaName($metaName);
                $this->getDoctrine()->getManager()->remove($metaName);
            }
            $product->getMetaNames()->clear();
            if ($metaNames != null) {
                foreach ($metaNames as $metaName) {
                  if (null != $productDB) {
                      $meta = new ProductMetaname();
                      $meta->setName($metaName["name"]);
                      $meta->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($meta);

                      $product->addMetaName($meta);
                  }
                }
            }
            $codes = json_decode($form->get('codes')->getData(), true);
            foreach ($product->getCodes() as $code) {
                $product->removeCode($code);
                $this->getDoctrine()->getManager()->remove($code);
            }
            $product->getCodes()->clear();
            if ($codes != null) {
                foreach ($codes as $code) {
                  if (null != $productDB) {
                      $productCode = new ProductCode();
                      $productCode->setCode($code);
                      $productCode->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($productCode);

                      $product->addCode($productCode);
                  }
                }
            }
            $colors = json_decode($form->get('colors')->getData(), true);
            foreach ($product->getColors() as $color) {
                $product->removeColor($color);
                $this->getDoctrine()->getManager()->remove($color);
            }
            $product->getColors()->clear();
            if ($colors != null) {
                foreach ($colors as $color) {
                  if (null != $productDB) {
                      $productColor = new ProductColor();
                      $productColor->setColor($color);
                      $productColor->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($productColor);

                      $product->addColor($productColor);
                  }
                }
            }
            $materials = json_decode($form->get('materials')->getData(), true);
            foreach ($product->getMaterials() as $material) {
                $product->removeMaterial($material);
                $this->getDoctrine()->getManager()->remove($material);
            }
            $product->getMaterials()->clear();
            if ($materials != null) {
                foreach ($materials as $material) {
                  if (null != $productDB) {
                      $productMaterial = new ProductMaterial();
                      $productMaterial->setMaterial($material);
                      $productMaterial->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($productMaterial);

                      $product->addMaterial($productMaterial);
                  }
                }
            }
            $rooms = json_decode($form->get('rooms')->getData(), true);
            foreach ($product->getRooms() as $room) {
                $product->removeRoom($room);
                $this->getDoctrine()->getManager()->remove($room);
            }
            $product->getRooms()->clear();
            if ($rooms != null) {
                foreach ($rooms as $room) {
                  if (null != $productDB) {
                      $productRoom = new ProductRoom();
                      $productRoom->setRoom($room);
                      $productRoom->setProduct($productDB);
                      $this->getDoctrine()->getManager()->persist($productRoom);

                      $product->addRoom($productRoom);
                  }
                }
            }
            $classification = $this->getDoctrine()->getRepository('AppBundle:Category')->find($form->get('classification')->getData());
            $productDB->setClassification($classification);
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
            'product'=>$product,
            'action' => 'edit',
            'categories' => $categories,
            'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
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
        $numberOfPackages = $request->request->get('numberOfPackages');
        if (is_string($isFurniture) && 'true' == $isFurniture) {
            $isFurniture = true;
        } elseif (is_int($isFurniture) && 1 == $isFurniture) {
            $isFurniture = true;
        } elseif (is_bool($isFurniture) && $isFurniture) {
            $isFurniture = true;
        } else {
            $isFurniture = false;
        }
        $isMattress = $request->request->get('isMattress');
        $numberOfPackages = $request->request->get('numberOfPackages');
        if (is_string($isMattress) && 'true' == $isMattress) {
            $isMattress = true;
        } elseif (is_int($isMattress) && 1 == $isMattress) {
            $isMattress = true;
        } elseif (is_bool($isMattress) && $isMattress) {
            $isMattress = true;
        } else {
            $isMattress = false;
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
        $isAriplaneMattress = $request->request->get('isAriplaneMattress');
        if (is_string($isAriplaneMattress) && 'true' == $isAriplaneMattress) {
            $isAriplaneMattress = true;
        } elseif (is_int($isAriplaneMattress) && 1 == $isAriplaneMattress) {
            $isAriplaneMattress = true;
        } elseif (is_bool($isAriplaneMattress) && $isAriplaneMattress) {
            $isAriplaneMattress = true;
        } else {
            $isAriplaneMattress = false;
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
        $isFaucet = $request->request->get('isFaucet');
        if (is_string($isFaucet) && 'true' == $isFaucet) {
            $isFaucet = true;
        } elseif (is_int($isFaucet) && 1 == $isFaucet) {
            $isFaucet = true;
        } elseif (is_bool($isFaucet) && $isFaucet) {
            $isFaucet = true;
        } else {
            $isFaucet = false;
        }
        $isGrill = $request->request->get('isGrill');
        if (is_string($isGrill) && 'true' == $isGrill) {
            $isGrill = true;
        } elseif (is_int($isGrill) && 1 == $isGrill) {
            $isGrill = true;
        } elseif (is_bool($isGrill) && $isGrill) {
            $isGrill = true;
        } else {
            $isGrill = false;
        }
        $isShelf = $request->request->get('isShelf');
        if (is_string($isShelf) && 'true' == $isShelf) {
            $isShelf = true;
        } elseif (is_int($isShelf) && 1 == $isShelf) {
            $isShelf = true;
        } elseif (is_bool($isShelf) && $isShelf) {
            $isShelf = true;
        } else {
            $isShelf = false;
        }
        $isDesk = $request->request->get('isDesk');
        if (is_string($isDesk) && 'true' == $isDesk) {
            $isDesk = true;
        } elseif (is_int($isDesk) && 1 == $isDesk) {
            $isDesk = true;
        } elseif (is_bool($isDesk) && $isDesk) {
            $isDesk = true;
        } else {
            $isDesk = false;
        }
        $isBookcase = $request->request->get('isBookcase');
        if (is_string($isBookcase) && 'true' == $isBookcase) {
            $isBookcase = true;
        } elseif (is_int($isBookcase) && 1 == $isBookcase) {
            $isBookcase = true;
        } elseif (is_bool($isBookcase) && $isBookcase) {
            $isBookcase = true;
        } else {
            $isBookcase = false;
        }
        $isComoda = $request->request->get('isComoda');
        if (is_string($isComoda) && 'true' == $isComoda) {
            $isComoda = true;
        } elseif (is_int($isComoda) && 1 == $isComoda) {
            $isComoda = true;
        } elseif (is_bool($isComoda) && $isComoda) {
            $isComoda = true;
        } else {
            $isComoda = false;
        }
        $isRepisa = $request->request->get('isRepisa');
        if (is_string($isRepisa) && 'true' == $isRepisa) {
            $isRepisa = true;
        } elseif (is_int($isRepisa) && 1 == $isRepisa) {
            $isRepisa = true;
        } elseif (is_bool($isRepisa) && $isRepisa) {
            $isRepisa = true;
        } else {
            $isRepisa = false;
        }

        $price = $this->get('product_service')->calculateProductPrice(
            $weight,
            $ikeaPrice,
            $isFurniture,
            $isFragile,
            $isAriplaneForniture,
            $isOversize,
            $isTableware,
            $isLamp,
            $numberOfPackages,
            $isMattress,
            $isAriplaneMattress,
            $isFaucet,
            $isGrill,
            $isShelf,
            $isDesk,
            $isBookcase,
            $isComoda,
            $isRepisa
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
                false,
                $product->getIsOversize(),
                $product->getIsTableware(),
                $product->getIsLamp(),
                $product->getNumberOfPackages(),
                $product->getIsMattress(),
                false,
                $product->getIsFaucet(),
                $product->getIsGrill(),
                $product->getIsShelf(),
                $product->getIsDesk(),
                $product->getIsBookcase(),
                $product->getIsComoda(),
                $product->getIsRepisa()
            );
            $product->setPrice($finalPrice);
            $product->setIsFurniture(true);
        }
        $em->flush();

        return new JsonResponse('ok');
    }
}
