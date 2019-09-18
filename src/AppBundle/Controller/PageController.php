<?php

namespace AppBundle\Controller;

use AppBundle\DTO\PageDTO;
use AppBundle\Entity\Image\ImageSite;
use AppBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends Controller
{
    /**
     * @Route(name="edit_page", path="/page/edit/{id}")
     *
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editPageAction(Request $request, $id)
    {
        $page = $this->getDoctrine()->getRepository('AppBundle:Page\Page')->find($id);
        $dto = new PageDTO();
        $dto->setId($page->getId());
        $dto->setData(json_encode($page->getData()));
        $dto->setName($page->getName());
        $form = $this->createForm(PageType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $pageDB = $this->getDoctrine()->getRepository('AppBundle:Page\Page')->find($data->getId());
            if (null != $pageDB) {
                if (!is_array($data->getData())) {
                    $pageDB->setData(json_decode($data->getData(), true));
                } else {
                    $pageDB->setData($data->getData());
                }
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('easyadmin', [
                    'entity' => 'Page',
                    'action' => 'list',
                    'menuIndex' => 6,
                    'subMenuIndex' => -1,
                ]);
            }
        }

        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();

        return $this->render(':admin/page:edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    /**
     * @Route(name="upload_site_image", path="/site/upload")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadImagePage(Request $request)
    {
        $upload = new UploadedFile($_FILES['file']['tmp_name'], $_FILES['file']['name'], $_FILES['file']['type'], $_FILES['file']['size'], $_FILES['file']['error']);
        $image = new ImageSite();
        $image->setImage($_FILES['file']['name']);
        $image->setOriginalName($_FILES['file']['name']);
        $image->setImageFile($upload);
        $this->getDoctrine()->getManager()->persist($image);
        $this->getDoctrine()->getManager()->flush();
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        $data = [
            'id' => $image->getId(),
            'name' => $image->getOriginalName(),
            'size' => filesize($this->getParameter('kernel.root_dir').'/../public_html'.$helper->asset($image, 'imageFile')),
            'path' => $helper->asset($image, 'imageFile'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route(name="remove_image_site", path="/site/image/remove/{id}", methods={"POST"}, defaults={"id"="-1"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function removeImagePageAction(Request $request, $id)
    {
        $imagePage = $this->getDoctrine()->getManager()->getRepository('AppBundle:Image\ImageSite')->find($id);
        if (null != $imagePage) {
            $this->getDoctrine()->getManager()->remove($imagePage);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse();
    }
}
