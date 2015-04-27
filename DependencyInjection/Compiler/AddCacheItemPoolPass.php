<?php

namespace EPWT\CacheBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AddCacheItemPoolPass
 * @package EPWT\CacheBundle\DependencyInjection\Compiler
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
class AddCacheItemPoolPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('epwt_cache_pools')) {
            return;
        }

        $poolsContainer = $container->getDefinition('epwt_cache_pools');

        $pools = $container->findTaggedServiceIds('epwt_cache_pool');

        foreach ($pools as $id => $tags) {
            $tag = reset($tags);

            if (!array_key_exists('alias', $tag)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Service "%s" must have "alias" attribute for pool name on tag "epwt_cache_pool"!',
                        $id
                    )
                );
            }

            $poolDefinition = $container->getDefinition($id);
            if ('stdClass' === $poolDefinition->getClass()) {
                $poolDefinition->setClass($container->getParameter('epwt_cache.pool.class'));
            }

            if (array_key_exists('pool-name', $tag)) {
                $poolDefinition->addArgument($tag['pool-name']);
            } else {
                $poolDefinition->addArgument($tag['alias']);
            }

            if (array_key_exists('default-ttl', $tag)) {
                $poolDefinition->addArgument($tag['default-ttl']);
            }

            if (!array_key_exists('driver', $tag)) {
                throw new \InvalidArgumentException(sprintf(
                    'Service "%s" must have "driver" attribute on tag "epwt_cache_pool"!',
                    $id
                ));
            }

            $this->parseDriver($id, $tag, $poolDefinition, $container);

            $poolsContainer->addMethodCall('add', [$tag['alias'], new Reference($id)]);
        }
    }

    /**
     * @param string $id
     * @param array $tag
     * @param Definition $poolDefinition
     * @param ContainerBuilder $container
     */
    protected function parseDriver($id, $tag, $poolDefinition, $container)
    {
        $validDrivers = ['redis', 'sncredis'];
        $driverName = $tag['driver'];

        switch($driverName) {
            case 'redis':
                if (!array_key_exists('redis-id', $tag)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Service "%s" must have "redis-id" attribute for "redis" driver on tag "epwt_cache_pool"!',
                        $id
                    ));
                }

                $driverType = 'redis';
                $redisId = $tag['redis-id'];

                break;

            case 'snc_redis':
                if (!array_key_exists('sncredis-client', $tag)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Service "%s" must have "sncredis-client" attribute for "sncredis" driver on tag "epwt_cache_pool"!',
                        $id
                    ));
                }

                $driverType = 'redis';
                $redisId = 'snc_redis.' . $tag['sncredis-client'];

                break;

            default:
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid driver type "%s" valid driver names [%s]',
                        $driverName,
                        implode(', ', $validDrivers)
                    )
                );

                break;
        }

        switch($driverType) {
            case 'redis':
                $redisIdDriver = $redisId . '.driver';

                if (!$container->hasDefinition($redisIdDriver)) {
                    $redisDriverDefinition = new Definition('EPWT\\Cache\\Drivers\\RedisDriver');
                    $redisDriverDefinition->addMethodCall('setRedis', [new Reference($redisId)]);

                    $container->setDefinition($redisIdDriver, $redisDriverDefinition);
                }

                $poolDefinition->addMethodCall('setDriver', [new Reference($redisIdDriver)]);

                break;
        }
    }
}
