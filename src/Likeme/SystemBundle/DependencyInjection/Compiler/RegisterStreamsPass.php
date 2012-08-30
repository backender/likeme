<?php

namespace Likeme\SystemBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RegisterStreamsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('likeme.gaufrette.stream_registry')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('likeme.stream_filesystem') as $id => $tags) {
            $domain = '';
            foreach ($tags as $eachTag) {
                if (!empty($eachTag['alias'])) {
                    $domain = $eachTag['alias'];
                    break;
                }
            }

            $container
                ->getDefinition('likeme.gaufrette.stream_registry')
                ->addMethodCall('addStreamDefinition', array($domain, new Reference($id)))
            ;
        }
    }
}