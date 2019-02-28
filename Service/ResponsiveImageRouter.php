<?php
namespace Wa72\AdaptimageBundle\Service;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Wa72\AdaptImage\Exception\ImageFileNotFoundException;
use Wa72\AdaptImage\ImageFileInfo;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageRouterInterface;

class ResponsiveImageRouter implements ResponsiveImageRouterInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $webroot;

    /**
     * ResponsiveImageRouter constructor.
     *
     * @param Router $router The symfony router service
     * @param string $webroot Full filesystem path of the root of the website, e.g.
     */
    public function __construct(Router $router, $webroot)
    {
        $this->webroot = $webroot;
        $this->router = $router;
    }

    public function getOriginalImageFileInfo($original_image_url)
    {
        if (substr($original_image_url, 0, 1) != '/') {
            $original_image_url = '/' . $original_image_url;
        }
        $path = realpath($this->webroot . $original_image_url);
        return ImageFileInfo::createFromFile($path);
    }

    public function generateUrl($original_image_url, $image_class, $image_width)
    {
        return $this->router->generate('responsive_image', ['class' => $image_class, 'width' => $image_width, 'image' => $original_image_url]);
    }

}