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
use Doctrine\ORM\EntityNotFoundException;

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
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
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
                'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            ]);
        }

        return $this->render('@FOSUser/Registration/register_content.html.twig', [
            'form' => $form->createView(),
            'home' => $home,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
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
            foreach ($persistedPrefacture->getPreFactureProducts() as $prefactureProduct) {
              if (!$this->IsEntityDefined($prefactureProduct)) {
                $prefactureProduct->setProduct(null);
              }
            }
            $clientPrefactures[] = $persistedPrefacture;
          }
        }

        $clientRequests = [];
        if (count($clientPrefactures) == 0) {
          $persistedRequests = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Request')->findAll();
          foreach ($persistedRequests as $persistedRequest) {
            if ($persistedRequest->getClient()->getEmail() == $userMail and count($persistedRequest->getPreFactures()) == 0) {
              $exists = false;
              foreach ($persistedPrefactures as $persistedPrefacture) {
                if ($persistedPrefacture->getRequest() && $persistedPrefacture->getRequest()->getId() == $clientRequest->getId()) {
                  $exists = true;
                }
              }

              if ($exists == false) {
                foreach ($persistedRequest->getRequestProducts() as $requestProduct) {
                  if (!$this->IsEntityDefined($requestProduct)) {
                    $requestProduct->setProduct(null);
                  }
                }
                $clientRequests[] = $persistedRequest;
              }
            }
          }
        }

        $externalRequests = $user->getExternalRequests();
        foreach($externalRequests as $externalRequest) {
          foreach($externalRequest->getExternalRequestProducts() as $externalRequestProduct) {
            $offerPrice = $this->get('product_service')->findProductOfferPrice($externalRequestProduct->getProduct());
            $externalRequestProduct->getProduct()->setPriceOffer($offerPrice);
          }
        }

        $commercialPreFactures = [];
        $commercialFactures = [];
        $commercialClients = [];
        $commercialPaymentCharge = 0;
        $commercialPaymentTotal = 0;
        if ($this->getUser()->hasRole("ROLE_COMMERCIAL")) {
          $commercialPreFactures = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\PreFacture')->findBy([
            'commercial' => $this->getUser()->getId(),
          ]);
          $commercialFactures = $this->getDoctrine()->getManager()->getRepository('AppBundle:Request\Facture')->findBy([
            'commercial' => $this->getUser()->getId(),
          ]);

          foreach($commercialPreFactures as $commercialPreFacture) {
            $client = $commercialPreFacture->getClient();
            $exists = false;
            foreach($commercialClients as $commercialClient) {
              if ($commercialClient->getEmail() == $client->getEmail()) {
                $exists = true;
              }
            }
            if (!$exists) {
              $commercialClients[] = $client;
            }

            foreach($commercialPreFacture->getPreFactureProducts() as $preFactureProducts) {
              $offerPrice = $this->get('product_service')->findProductOfferPrice($preFactureProducts->getProduct());
              $preFactureProducts->getProduct()->setPriceOffer($offerPrice);
            }

            if (count($commercialPreFacture->getFactures()) <= 0) {
              $commercialPaymentCharge += $commercialPreFacture->getFinalPrice();
            }
          }

          foreach($commercialFactures as $commercialFacture) {
            $client = $commercialFacture->getClient();
            $exists = false;
            foreach($commercialClients as $commercialClient) {
              if ($commercialClient->getEmail() == $client->getEmail()) {
                $exists = true;
              }
            }
            if (!$exists) {
              $commercialClients[] = $client;
            }

            foreach($commercialFacture->getFactureProducts() as $factureProduct) {
              $offerPrice = $this->get('product_service')->findProductOfferPrice($factureProduct->getProduct());
              $factureProduct->getProduct()->setPriceOffer($offerPrice);
            }

            $commercialPaymentTotal += $commercialFacture->getFinalPrice();
          }
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'showSuccessToast' => $showSuccessToast,
            'requests' => $clientRequests,
            'prefactures' => $clientPrefactures,
            'externalRequests' => $externalRequests,
            'commercialPreFactures' => $commercialPreFactures,
            'commercialFactures' => $commercialFactures,
            'commercialPaymentCharge' => $commercialPaymentCharge,
            'commercialPaymentTotal' => $commercialPaymentTotal,
            'commercialClients' => $commercialClients,
            'home' => $home,
            'membership' => $membership,
            'categories' => $this->get('category_service')->getAll(),
            'terms' => $config->getTermAndConditions(),
            'privacy' => $config->getPrivacyPolicy(),
            'count' => $this->get('shop_cart_service')->countShopCart($this->getUser()),
            'shopCartProducts' => $this->get('shop_cart_service')->getShopCartProducts($this->getUser()),
            'shopCartBags' => $this->get('shop_cart_service')->getShopCartBags($this->getUser()),
        ]);
    }

    public function IsEntityDefined($prefactureProduct) {
      try {
        if ($prefactureProduct->getProduct() != null && $prefactureProduct->getProduct()->getName() != null) {
          return true;
        } else {
          return false;
        }
      } catch (EntityNotFoundException $e) {
        return false;
      }
    }
}
