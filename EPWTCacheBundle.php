<?php

namespace EPWT\CacheBundle;

use EPWT\CacheBundle\DependencyInjection\Compiler\AddCacheItemPoolPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EPWTCacheBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddCacheItemPoolPass());
    }
}
