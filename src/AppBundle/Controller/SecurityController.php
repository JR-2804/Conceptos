<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
{
    /**
     * @Route(name="site_login", path="/login")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        if ($error != null) {
          $request->getSession()->set('loginError', true);
          return new RedirectResponse($this->generateUrl('site_home'));
        } else {
          $request->getSession()->set('loginError', false);
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        return $this->render('@FOSUser/Security/login_content.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'home' => $home,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'count' => $this->countShopCart($request),
        ]);
    }

    /**
     * @Route(name="site_register", path="/register/")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        /** @var FactoryInterface $formFactory */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var UserManagerInterface $userManager */
        $userManager = $this->get('fos_user.user_manager');
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $oldParameters = $request->request->get('fos_user_registration_form');
        if ($oldParameters) {
            $imageId = $oldParameters['image'];
            $image = $this->getDoctrine()->getRepository('AppBundle:Image')->find($imageId);
            $oldParameters['image'] = $image;
            $request->request->set('fos_user_registration_form', $oldParameters);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('site_home');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }

            return $this->render('@FOSUser/Registration/register.html.twig', [
                'form' => $form->createView(),
                'home' => $home,
                'categories' => $this->get('category_service')->getAll(),
                'terms' => $config->getTermAndConditions(),
                'privacy' => $config->getPrivacyPolicy(),
                'count' => $this->countShopCart($request),
            ]);
        }

        return $this->render('@FOSUser/Registration/register_content.html.twig', [
            'form' => $form->createView(),
            'home' => $home,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'count' => $this->countShopCart($request),
        ]);
    }

    /**
     * @Route(name="site_profile", path="/profile/")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $showSuccessToast = false;
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var FactoryInterface $formFactory */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($user->getImage()) {
            $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
            $jsonImage = json_encode([
                'id' => $user->getImage()->getId(),
                'name' => $user->getImage()->getOriginalName(),
                'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($user->getImage(), 'imageFile')),
                'path' => $helper->asset($user->getImage(), 'imageFile'),
            ]);
            $form->get('jsonImage')->setData($jsonImage);
        }

        $oldParameters = $request->request->get('fos_user_profile_form');
        if ($oldParameters) {
            $imageId = $oldParameters['image'];
            $image = $this->getDoctrine()->getRepository('AppBundle:Image')->find($imageId);
            $oldParameters['image'] = $image;
            $request->request->set('fos_user_profile_form', $oldParameters);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserManagerInterface $userManager */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);
            $showSuccessToast = true;
        }

        $home = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Home',
        ]);
        $membership = $this->getDoctrine()->getManager()->getRepository('AppBundle:Page\Page')->findOneBy([
            'name' => 'Membresia',
        ]);

        $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);

        $userMail = $this->getUser()->getEmail();
        $clientPrefactures = [];
        $persistedPrefactures = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->findAll();
        foreach ($persistedPrefactures as $persistedPrefacture) {
          if ($persistedPrefacture->getClient()->getEmail() == $userMail) {
            $clientPrefactures[] = $persistedPrefacture;
          }
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'showSuccessToast' => $showSuccessToast,
            'prefactures' => $clientPrefactures,
            'home' => $home,
            'membership' => $membership,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'count' => $this->countShopCart($request),
            'shopCartProducts' => $this->getShopCartProducts(json_decode($request->getSession()->get('products'), true)),
        ]);
    }

    private function getShopCartProducts($products)
    {
      if ($products == null) {
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
        $session = $request->getSession();
        if ($session->has('products')) {
            return count(json_decode($session->get('products'), true));
        }

        return 0;
    }
}
