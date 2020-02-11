<?php

namespace AppBundle\Controller;

use AppBundle\DTO\CommentDTO;
use AppBundle\Entity\Blog\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends Controller
{
    /**
     * @Route(name="blog_home", path="/blog/")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $request->query->get('page', 0);
        $firstResult = $page * 10;
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')
            ->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults(10)
            ->getQuery()->getResult();
        $countPosts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->select('COUNT(p.id) as count_post')->getQuery()->getSingleScalarResult();
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(20)->getQuery()->getResult();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render('site/blog/index.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'posts' => $posts,
            'lasts' => $lasts,
            'blogCategories' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Category')->findAll(),
            'tags' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Tag')->findAll(),
            'title' => 'Inicio',
            'pages' => intval($countPosts / 10),
            'url_to_go' => $this->generateUrl('blog_home'),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="apiLoadProducts", path="/apiProduct/{code}")
     */
    public function getProduct($code){

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Product');
        $product = $productsRepository->findOneBy(['code'=>$code]);
        $productJson = [];
        $productJson['name'] = $product->getName();
        $productJson['id'] = $product->getId();
        $productJson['description'] = $product->getDescription();
        $productJson['price'] = $product->getPrice();
        $productJson['image'] = '/uploads/'.$product->getMainImage()->getImage();
        $productJson['url'] = $this->generateUrl('product_details', ['id'=>$product->getId()]);
        $productJson['addCart'] = $this->generateUrl('add_shop_card', ['id'=>$product->getId()]);
        $productJson['inStore'] = $product->getInStore();
        var_dump($product->getOffers());
        exit();
        $productJson['priceOffer'] = $product->getPriceOffer();

        return $this->json($productJson);
    }

    /**
     * @Route(name="blog_details", path="/blog/post/{id}/{title}")
     *
     * @param Request $request
     * @param $id
     * @param $title
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Request $request, $id, $title)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->find($id);
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->where('p.id <> :id')
            ->setParameter('id', $id)
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(3)->getQuery()->getResult();
        $post->setVisitCount($post->getVisitCount() + 1);
        $this->getDoctrine()->getManager()->flush();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site/blog:details.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'post' => $post,
            'lasts' => $lasts,
            'blogCategories' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Category')->findAll(),
            'tags' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Tag')->findAll(),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="post_category", path="/blog/post/category/{id}/{category}")
     *
     * @param Request $request
     * @param $id
     * @param $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByCategory(Request $request, $id, $category)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $request->query->get('page', 0);
        $firstResult = $page * 10;
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')
            ->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->where('c.id = :category')
            ->setFirstResult($firstResult)
            ->setMaxResults(10)
            ->orderBy('p.createdDate', 'DESC')
            ->setParameter('category', $id)
            ->getQuery()->getResult();
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(3)->getQuery()->getResult();
        $countPosts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->select('COUNT(p.id) as count_post')->getQuery()->getSingleScalarResult();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render('site/blog/index.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'posts' => $posts,
            'lasts' => $lasts,
            'blogCategories' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Category')->findAll(),
            'tags' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Tag')->findAll(),
            'title' => 'Artículos de la categoría: '.$category,
            'pages' => intval($countPosts / 10),
            'url_to_go' => $this->generateUrl('post_category', ['id' => $id, 'category' => $category]),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="post_tag", path="/blog/post/tag/{id}/{tag}")
     *
     * @param Request $request
     * @param $id
     * @param $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByTag(Request $request, $id, $tag)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $page = $request->query->get('page', 0);
        $firstResult = $page * 10;
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')
            ->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('t.id = :tag')
            ->setFirstResult($firstResult)
            ->setMaxResults(10)
            ->orderBy('p.createdDate', 'DESC')
            ->setParameter('tag', $id)
            ->getQuery()->getResult();
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(3)->getQuery()->getResult();
        $countPosts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->select('COUNT(p.id) as count_post')->getQuery()->getSingleScalarResult();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render('site/blog/index.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'posts' => $posts,
            'lasts' => $lasts,
            'blogCategories' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Category')->findAll(),
            'tags' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Tag')->findAll(),
            'title' => 'Artículos con el tag: '.$tag,
            'pages' => intval($countPosts / 10),
            'url_to_go' => $this->generateUrl('post_tag', ['id' => $id, 'tag' => $tag]),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="post_search", path="/blog/post/buscar", methods={"GET"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);
        $term = $request->query->get('term', false);
        if ($term) {
            $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')
                ->createQueryBuilder('p')
                ->where('p.summary LIKE :term OR p.title LIKE :term OR p.body LIKE :term')
                ->orderBy('p.createdDate', 'DESC')
                ->setParameter('term', '%'.$term.'%')
                ->getQuery()->getResult();
        } else {
            $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')
                ->createQueryBuilder('p')
                ->orderBy('p.createdDate', 'DESC')
                ->getQuery()->getResult();
        }
        $lasts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->orderBy('p.createdDate', 'DESC')
            ->setMaxResults(3)->getQuery()->getResult();
        $countPosts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->createQueryBuilder('p')
            ->select('COUNT(p.id) as count_post')->getQuery()->getSingleScalarResult();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render(':site/blog:index.html.twig', [
            'home' => $home,
            'membership' => $membership,
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
            'posts' => $posts,
            'lasts' => $lasts,
            'blogCategories' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Category')->findAll(),
            'tags' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Tag')->findAll(),
            'title' => 'Inicio',
            'pages' => intval($countPosts / 10),
            'url_to_go' => $this->generateUrl('blog_home'),
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
        ]);
    }

    /**
     * @Route(name="add_comment", path="/blog/post/comment/add", methods={"POST"})
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sendCommentAction(Request $request, $id)
    {
        $dto = new CommentDTO();
        $dto->setId($id);
        $form = $this->createForm(CommentType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $comment = new Comment();
            $comment->setDate(new \DateTime());
            $comment->setEmail($data->getEmail());
            $comment->setName($data->getName());
            $comment->setText($data->getText());
            $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->find($id);
            $comment->setPost($post);
            $this->getDoctrine()->getManager()->persist($comment);
            $post->addComment($comment);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_details', [
                'id' => $id,
                'title' => $post->getPath(),
            ]);
        }

        return $this->render(':site/blog:add-comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getShopCartProducts($products)
    {
      if ($products === null) {
        $products = [];
      }

      $productsDB = [];
      foreach ($products as $product) {
          if (array_key_exists('id', $product) && ('target15' == $product['id'] || 'target25' == $product['id'] || 'target50' == $product['id'] || 'target100' == $product['id'])) {
              $name = 'Tarjeta de 15 CUC';
              switch ($product['id']) {
                  case 'target15':
                      $price = 15;
                      break;
                  case 'target25':
                      $name = 'Tarjeta de 25 CUC';
                      $price = 25;
                      break;
                  case 'target50':
                      $name = 'Tarjeta de 50 CUC';
                      $price = 50;
                      break;
                  default:
                      $name = 'Tarjeta de 100 CUC';
                      $price = 100;
                      break;
              }
              $productsDB[] = [
                  'id' => $product['id'],
                  'uuid' => $product['uuid'],
                  'type' => 'target',
                  'price' => $price,
                  'count' => $product['count'],
                  'name' => $name,
              ];
          } else {
              $productDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($product['product']);
              $price = $productDB->getPrice();

              $offerExists = false;
              $offerDB = null;
              if ($productDB->getOffers() && $productDB->getOffers()[0]) {
                $offerDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')->find($productDB->getOffers()[0]);
              }

              if ($offerDB) {
                $price = $offerDB->getPrice();
                $offerExists = true;
              } else {
                $categories = [];
                foreach ($productDB->getCategories() as $category) {
                  $categories[] = $category->getId();

                  if (($category->getOffers()[0]) && ((!$category->getOffers()[0]->getOnlyInStoreProducts()) or ($category->getOffers()[0]->getOnlyInStoreProducts() && $productDB->getInStore()))) {
                    $price = ceil($productDB->getPrice()*(1 - $category->getOffers()[0]->getPrice()/100));
                    $offerExists = true;
                  } else {
                    foreach ($category->getParents() as $parentCategory) {
                      if (($parentCategory->getOffers()[0]) && ((!$parentCategory->getOffers()[0]->getOnlyInStoreProducts()) or ($parentCategory->getOffers()[0]->getOnlyInStoreProducts() && $productDB->getInStore()))) {
                        $price = ceil($productDB->getPrice()*(1 - $parentCategory->getOffers()[0]->getPrice()/100));
                        $offerExists = true;
                      }
                    }
                  }
                }
              }

              $productsDB[] = [
                  'id' => $productDB->getId(),
                  'uuid' => $product['uuid'],
                  'price' => $price,
                  'offerExists' => $offerExists,
                  'count' => $product['count'],
                  'storeCount' => $productDB->getStoreCount(),
                  'name' => $productDB->getName(),
                  'image' => $productDB->getMainImage(),
                  'weight' => $productDB->getWeight(),
                  'ikeaPrice' => $productDB->getIkeaPrice(),
                  'isFurniture' => $productDB->getIsFurniture(),
                  'isFragile' => $productDB->getIsFragile(),
                  'isAirplaneFurniture' => $productDB->getIsAriplaneForniture(),
                  'isOversize' => $productDB->getIsOversize(),
                  'isTableware' => $productDB->getIsTableware(),
                  'isLamp' => $productDB->getIsLamp(),
                  'numberOfPackages' => $productDB->getNumberOfPackages(),
                  'isMattress' => $productDB->getIsMattress(),
                  'isAirplaneMattress' => $productDB->getIsAriplaneMattress(),
                  'isFaucet' => $productDB->getIsFaucet(),
                  'isGrill' => $productDB->getIsGrill(),
                  'isShelf' => $productDB->getIsShelf(),
                  'isDesk' => $productDB->getIsDesk(),
                  'isBookcase' => $productDB->getIsBookcase(),
                  'isComoda' => $productDB->getIsComoda(),
                  'isRepisa' => $productDB->getIsRepisa(),
                  'categories' => json_encode($categories),
              ];
          }
      }
      return $productsDB;
    }

    private function countShopCart(Request $request)
    {
      $total = 0;
        $session = $request->getSession();
        if ($session->has('products')) {
            $products = json_decode($session->get('products'), true);
            foreach ($products as $product) {
              $total += $product['count'];
            }
        }

        return $total;
    }
}
