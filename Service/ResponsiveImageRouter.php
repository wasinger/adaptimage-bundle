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
        $baseurl = $this->router->getContext()->getBaseUrl();
        if (substr($original_image_url, 0, strlen($baseurl)) === $baseurl) {
            $original_image_url = substr($original_image_url, strlen($baseurl));
        }
        if (substr($original_image_url, 0, 1) == '/') {
            $original_image_url = substr($original_image_url, 1);
        }
        $path = realpath($this->webroot . \DIRECTORY_SEPARATOR . $original_image_url);
        return ImageFileInfo::createFromFile($path);
    }

    public function generateUrl($original_image_url, $image_class, $image_width)
    {
        $baseurl = $this->router->getContext()->getBaseUrl();
        if (substr($original_image_url, 0, strlen($baseurl)) === $baseurl) {
            $original_image_url = substr($original_image_url, strlen($baseurl));
        }
        if (substr($original_image_url, 0, 1) == '/') {
            $original_image_url = substr($original_image_url, 1);
        }
        return $this->router->generate('responsive_image', ['class' => $image_class, 'width' => $image_width, 'image' => $original_image_url]);
    }

}