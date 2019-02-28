<?php
namespace Wa72\AdaptimageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Wa72\AdaptImage\ImageResizer;
use Wa72\AdaptImage\Output\OutputPathGeneratorBasedir;
use Wa72\AdaptImage\Output\OutputPathGeneratorInterface;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageClass;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageHelper;
use Wa72\AdaptImage\ResponsiveImages\ResponsiveImageRouterInterface;
use Wa72\AdaptimageBundle\Service\ResponsiveImageRouter;

class Wa72AdaptimageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (\class_exists('\Imagick')) {
            $imagine_class = \Imagine\Imagick\Imagine::class;
        } elseif (\class_exists('\Gmagick')) {
            $imagine_class = \Imagine\Gmagick\Imagine::class;
        } else {
            $imagine_class = \Imagine\Gd\Imagine::class;
        }

        $d = new Definition($imagine_class);
        $container->setDefinition(\Imagine\Image\ImagineInterface::class, $d);

        $d = new Definition(OutputPathGeneratorBasedir::class);
        $d->addArgument($container->getParameter('kernel.cache_dir') . \DIRECTORY_SEPARATOR . 'imagecache');
        $container->setDefinition(OutputPathGeneratorInterface::class, $d);

        $d = new Definition(ImageResizer::class);
        $d->addArgument(new Reference(\Imagine\Image\ImagineInterface::class));
        $container->setDefinition(ImageResizer::class, $d);

        $d = new Definition(ResponsiveImageRouter::class);
        $d->addArgument(new Reference('router'));
        $d->addArgument($container->getParameter('kernel.project_dir') . \DIRECTORY_SEPARATOR . 'public');
        $container->setDefinition(ResponsiveImageRouterInterface::class, $d);

        $d = new Definition(ResponsiveImageHelper::class);
        $d->addArgument(new Reference(ResponsiveImageRouterInterface::class));
        $d->addMethodCall('setResizer', [new Reference(ImageResizer::class)]);
        foreach ($config['classes'] as $name => $settings) {
            $a = new ResponsiveImageClass($name, $settings['widths'], $settings['sizes_attribute']);
            $d->addMethodCall('addClass', [$a]);
        }
        $container->setDefinition(ResponsiveImageHelper::class, $d);

    }
}