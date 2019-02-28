<?php
namespace Wa72\AdaptimageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageHelper;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adaptimage")
 */
class ImageController extends AbstractController
{
    /** @var ResponsiveImageHelper */
    private $rih;

    public function __construct(ResponsiveImageHelper $rih)
    {
        $this->rih = $rih;
    }

    /**
     * @Route(
     *     "/{class}/{width}/{image}",
     *     name="responsive_image",
     *     methods="GET",
     *     requirements={"image"=".+"}
     * )
     */
    public function image(Request $request, $class, $width, $image)
    {
        try {
            $ifi = $this->rih->getResizedImageVersion($image, $class, $width);
            $response = new BinaryFileResponse($ifi->getFileinfo());
            $response->isNotModified($request);
        } catch (\Exception $e) {
            $content = ($this->container->getParameter("kernel.environment") == 'dev' ? $e->getMessage() : '');
            $response = new Response($content, 404);
        }
        return $response;
    }

}