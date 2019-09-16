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
     * @Route(name="blog_home", path="/blog")
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
            'count' => $this->countShopCart($request),
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
            'count' => $this->countShopCart($request),
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
            'count' => $this->countShopCart($request),
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
            'count' => $this->countShopCart($request),
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
            'count' => $this->countShopCart($request),
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
