<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdmin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends BaseAdmin
{
    /**
     * @Route(path="/", name="easyadmin")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if ($request->query->get('entity') == 'Product' && $request->query->get('action') == 'new') {
            return $this->redirectToRoute('new_product', array('menuIndex' => $request->query->get('menuIndex'), 'submenuIndex' => $request->query->get('submenuIndex')));
        }
        if (
            $request->query->get('entity') == 'Product' &&
            $request->query->get('action') == 'edit' &&
            ($request->query->get('property') != 'popular' && $request->query->get('property') != 'recent' && $request->query->get('property') != 'inStore' && $request->query->get('property') != 'isDisabled')
          ) {
            return $this->redirectToRoute('edit_product', array('id' => $request->query->get('id'), 'menuIndex' => $request->query->get('menuIndex'), 'submenuIndex' => $request->query->get('submenuIndex')));
        }
        if ($request->query->get('entity') == 'Page' && $request->query->get('action') == 'edit') {
            return $this->redirectToRoute('edit_page', array(
                'id' => $request->query->get('id')
            ));
        }
        return parent::indexAction($request);
    }

    /**
     * @Route(name="update_slide", path="/update-slide")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateSlideAction(Request $request)
    {
      $slidePreview = $this->getDoctrine()->getManager()->getRepository('AppBundle:SlidePreview')->find(1);
      $slidePreview->setData(json_decode($request->request->get('data'), true));
      $this->getDoctrine()->getManager()->flush();

      return new JsonResponse();
    }

    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $user->setEnabled(true);
        $user->addRole('ROLE_ADMIN');
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function prePersistPostEntity($post)
    {
        $post->setUser($this->getUser());
        $post->setCreatedDate(new \DateTime());
        $post->setVisitCount(0);
        $post->setPath(str_replace(" ", "-", $post->getTitle()));
        $post->setPromoted(false);
    }

    public function prePersistOfferEntity($offer)
    {
        $this->updateDateUpdate();

        if ($offer->getOnlyInStoreProducts()) {
          $categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();
          foreach ($categories as $category) {
            $category->addOffer($offer);
            $offer->addCategory($category);
          }
        }
    }

    public function preUpdateOfferEntity($offer)
    {
        $this->updateDateUpdate();
    }

    public function preRemoveOfferEntity($entity)
    {
        $this->updateDateUpdate();
    }

    public function prePersistCategoryEntity($category)
    {
        $this->updateDateUpdate();
    }

    public function preUpdateCategoryEntity($category)
    {
        $this->updateDateUpdate();
    }

    public function preRemoveCategoryEntity($category)
    {
        $this->updateDateUpdate();
    }

    public function prePersistClientEntity($client)
    {
        $this->updateClientRequests($client);
    }

    public function preUpdateClientEntity($client)
    {
        $this->updateClientRequests($client);
    }

    private function updateClientRequests($client)
    {
      foreach ($client->getRequests() as $request) {
          $requestDB = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->find($request->getId());
          $requestDB->setClient($client);
          $this->getDoctrine()->getManager()->persist($requestDB);
      }
    }

    private function updateDateUpdate()
    {
        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
        $config->setLastProductUpdate(new \DateTime());
    }
}
