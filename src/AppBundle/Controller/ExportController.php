<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends Controller
{

    /**
     * @Route(name="export_db_view", path="/admin/export/db")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportViewAction()
    {
        return $this->render(':admin/export:export.html.twig');
    }

    /**
     * @Route(name="export_element", path="/admin/export/element/{element}", defaults={"element":"app"})
     * @param Request $request
     * @param $element
     * @return JsonResponse
     */
    public function exportElementAction(Request $request, $element)
    {
        $basePath = $this->getParameter("kernel.root_dir");
        $file = $request->query->get('file');
        $last = $request->query->get('last');
        if ($element == 'app') {
            $file = $this->get('export_service')->exportApp($basePath);
        }
        if ($element == 'color') {
            $this->get('export_service')->exportColor($file);
        }
        if ($element == 'material') {
            $this->get('export_service')->exportMaterial($file);
        }
        if ($element == 'category') {
            $this->get('export_service')->exportCategories($file, $basePath);
        }
        if ($element == 'product') {
            $page = $request->query->get('page');
            $last = $this->get('export_service')->exportProducts($file, $page);
        }
        if ($element == 'offer') {
            $this->get('export_service')->exportOffers($file);
        }
        if ($element == 'phone') {
            $this->get('export_service')->exportPhones($file);
        }
        if ($element == 'config') {
            $this->get('export_service')->exportConfig($file, $basePath);
            $config = $this->getDoctrine()->getManager()->getRepository('AppBundle:Configuration')->find(1);
            $config->setLastDatabaseExport(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $flash = "Base de datos generada correctamente <a href='/db/'. $file .'>Descargar</a>";
            $this->addFlash('success', $flash);
        }
        return new JsonResponse(array(
            'fileName' => $file,
            'last' => $last
        ));
    }
}