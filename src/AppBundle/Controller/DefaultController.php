<?php

namespace AppBundle\Controller;

use AppBundle\DTO\ConfigDTO;
use AppBundle\Entity\Configuration;
use AppBundle\Form\ConfigType;
use AppBundle\Form\UploadAppType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route(name="config_view", path="/admin/config/view")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function configAction(Request $request)
    {
        $configDB = $this->getDoctrine()->getRepository('AppBundle:Configuration')->find(1);
        $dto = new ConfigDTO();
        if (null != $configDB) {
            $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
            $dto->setHours($configDB->getHours());
            $dto->setPhone($configDB->getPhone());
            $dto->setEmail($configDB->getEmail());
            $dto->setTerms($configDB->getTermAndConditions());
            $dto->setPrivacyPolicy($configDB->getPrivacyPolicy());
            $dto->setTotalWeight($configDB->getTotalWeight());
            $dto->setTaxTax($configDB->getTaxTax());
            $dto->setTaxFurniture($configDB->getTaxFurniture());
            $dto->setBenefit($configDB->getBenefit());
            $dto->setTicketPrice($configDB->getTicketPrice());
            if (null != $configDB->getImage()) {
                $arr = [
                    'id' => $configDB->getId(),
                    'name' => $configDB->getImage()->getOriginalName(),
                    'size' => filesize($this->getParameter('kernel.root_dir').'/../web'.$helper->asset($configDB->getImage(), 'imageFile')),
                    'path' => $helper->asset($configDB->getImage(), 'imageFile'),
                ];
            } else {
                $arr = [];
            }
            $dto->setImage(json_encode($arr));
        }
        $form = $this->createForm(ConfigType::class, $dto);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $configDBS = $this->getDoctrine()->getRepository('AppBundle:Configuration')->find(1);
            $exist = true;
            if (null == $configDBS) {
                $configDBS = new Configuration();
                $exist = false;
            }
            $configDBS->setPhone($form->get('phone')->getData());
            $configDBS->setHours($form->get('hours')->getData());
            $configDBS->setEmail($form->get('email')->getData());
            $configDBS->setTermAndConditions($form->get('terms')->getData());
            $configDBS->setPrivacyPolicy($form->get('privacyPolicy')->getData());
            $imageDB = $this->getDoctrine()->getRepository('AppBundle:Image')->find($form->get('image')->getData());
            if (null != $imageDB) {
                $configDBS->setImage($imageDB);
            }
            $configDBS->setTotalWeight($form->get('totalWeight')->getData());
            $configDBS->setTaxTax($form->get('taxTax')->getData());
            $configDBS->setTaxFurniture($form->get('taxFurniture')->getData());
            $configDBS->setBenefit($form->get('benefit')->getData());
            $configDBS->setTicketPrice($form->get('ticketPrice')->getData());
            if (!$exist) {
                $this->getDoctrine()->getManager()->persist($configDBS);
            }
            $this->getDoctrine()->getManager()->flush();
            $recalculatePrice = $form->get('recalculatePrice')->getData();
            if (is_string($recalculatePrice) && ('1' == $recalculatePrice || 'true' == $recalculatePrice)) {
                $this->get('product_service')->recalculatePrices();
            }
            if (is_bool($recalculatePrice) && $recalculatePrice) {
                $this->get('product_service')->recalculatePrices();
            }
            if (is_numeric($recalculatePrice) && 1 == $recalculatePrice) {
                $this->get('product_service')->recalculatePrices();
            }
            $this->addFlash('success', 'Configuración guardada correctamente');

            return $this->redirectToRoute('easyadmin', [
                'entity' => 'Product',
                'action' => 'list',
                'menuIndex' => 0,
                'submenuIndex' => -1,
            ]);
        }

        return $this->render('config.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="upload_app", path="/upload/app")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadAppAction(Request $request)
    {
        $dto = new ConfigDTO();
        $path = $this->getParameter('kernel.root_dir').'/../web/download/app/app-lasted.apk';
        $uploadedFile = new UploadedFile($path, 'app-lasted.apk', null, filesize($path));
        $dto->setApp($uploadedFile);
        $form = $this->createForm(UploadAppType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->getData()->getApp();
            $this->addFlash('success', 'Aplicación actualizada con éxito');
            $pathApp = $this->getParameter('kernel.root_dir').'/../web/download/app/';
            $file->move($pathApp, 'app-lasted.apk');

            return $this->redirectToRoute('easyadmin', [
                'entity' => 'Product',
                'action' => 'list',
                'menuIndex' => 0,
                'submenuIndex' => -1,
            ]);
        }

        return $this->render('::upload-app.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
