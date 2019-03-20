<?php

namespace Wa72\AdaptimageBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageHelper;

class AdaptImageExtension extends AbstractExtension
{
    /**
     * @var ResponsiveImageHelper
     */
    private $rih;

    public function __construct(ResponsiveImageHelper $rih)
    {
        $this->rih = $rih;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('wa72_responsive_img', [$this, 'responsiveImage'], ['is_safe' => ['html']])
        ];
    }
    public function responsiveImage($image, $class, $options = [])
    {
        return $this->rih->getResponsiveHtmlImageTag($image, $class, $options);
    }
}
