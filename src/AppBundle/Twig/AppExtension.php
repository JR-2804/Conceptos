<?php
// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;


class AppExtension extends \Twig_Extension
{
    private $imagineCacheManager;

    public function __construct(ContainerInterface $container = null)
    {
        $this->imagineCacheManager = $container->get('liip_imagine.cache.manager');
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('imgTag', [$this, 'imgTag']),
        );
    }

    public function imgTag($image, $sizes = '100vw', $alt = null, $class = null, $style='')
    {
        $html = "<img class=\"lazyload blur-up $class \" sizes=\"$sizes\" ";
        $src = $this->imagineCacheManager->getBrowserPath($image, 'extrasmall_thumbnail');
        $html .= " src=\"$src\" ";
        $html .= " data-srcset=\"".$this->filterSrcset($image)."\" ";
        $html .= "alt=\"$alt\" style=\"$style\">";

        return $html;
    }

    /**
     * @param string $imagesPath, the relative path to image, ex: static/uploads/image/pic.jpg
     * @param array $sizes, the sizes, depends on the filters 'min_width_XXX' defined on liip_image config file
     * @return string
     */
    public function filterSrcset($imagePath, $sizes = [1920, 1200, 1000, 900, 800, 600])
    {
        $html = '';
        foreach ($sizes as $value) {
            //obtiene las rutas de las imagenes según el tamaño
            $resolvedPath = $this->imagineCacheManager->getBrowserPath($imagePath, 'min_width_'.$value);
            //completa la lista de "path tamaño" de las imagenes disponibles
            $html .= ' '.$resolvedPath.' '.$value.'w, ';
        }
        $html = trim($html, ", ");

        return $html;
    }

}