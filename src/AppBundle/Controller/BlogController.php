<?php

namespace AppBundle\Controller;

use AppBundle\DTO\CommentDTO;
use AppBundle\Entity\Blog\Comment;
use AppBundle\Entity\Blog\BlogLike;
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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
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
    public function getProduct($code) {
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

        $offerPrice = $this->get('product_service')->findProductOfferPrice($product);
        $productJson['priceOffer'] = $offerPrice;

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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
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
     * @Route(name="add_comment", path="/blog/post/comment/add/{id}", methods={"POST"})
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
        $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->find($id);

        $comment = new Comment();
        $comment->setDate(new \DateTime());
        $comment->setName($data->getName());
        $comment->setEmail($data->getEmail());
        $comment->setText($data->getText());
        $comment->setPost($post);
        $post->addComment($comment);
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $emailBody = $this->renderView(':site:comment-email.html.twig', [
          'username' => $data->getName(),
          'comment' => $data->getText(),
        ]);
        $this->get('email_service')->send($config->getEmail(), 'Comentario Realizado', "comercial@tiendaconceptos.com", 'Comentario Realizado', $emailBody);

        return $this->redirectToRoute('blog_details', [
          'id' => $id,
          'title' => $post->getPath(),
        ]);
      }

      return $this->render(':site/blog:add-comment.html.twig', [
        'form' => $form->createView(),
        'postId' => $id
      ]);
    }

    /**
     * @Route(name="remove_comment", path="/blog/post/comment/remove/{postId}/{commentId}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeCommentAction(Request $request, $postId, $commentId)
    {
      $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->find($postId);
      $comment = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Comment')->find($commentId);

      $this->getDoctrine()->getManager()->remove($comment);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('blog_details', [
        'id' => $postId,
        'title' => $post->getPath(),
      ]);
    }

    /**
     * @Route(name="add_like", path="/blog/post/like/add/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sendLikeAction(Request $request, $id)
    {
      $post = $this->getDoctrine()->getManager()->getRepository('AppBundle:Blog\Post')->find($id);
      if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
        $like = new BlogLike();
        $like->setPost($post);
        $like->setUser($this->getUser());
        $post->addLike($like);
        $this->getDoctrine()->getManager()->persist($like);
        $this->getDoctrine()->getManager()->flush();
      }

      return $this->redirectToRoute('blog_details', [
        'id' => $id,
        'title' => $post->getPath(),
      ]);
    }
}
