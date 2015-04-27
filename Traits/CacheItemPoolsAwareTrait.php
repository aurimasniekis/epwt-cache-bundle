<?php

namespace EPWT\CacheBundle\Traits;

use EPWT\Cache\Core\CacheItemPool;
use EPWT\CacheBundle\Core\CacheItemPoolsContainer;
use EPWT\CacheBundle\Exception\CacheItemPoolNotExistsException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait CacheItemPoolsAwareTrait
 * @package EPWT\CacheBundle\Traits
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
trait CacheItemPoolsAwareTrait
{
    /**
     * @var CacheItemPoolsContainer
     */
    protected $cacheItemPoolsContainer;

    /**
     * @param string $name ClassItemPool Alias
     *
     * @return CacheItemPool
     * @throws CacheItemPoolNotExistsException
     */
    public function getCacheItemPool($name)
    {
        if (!$this->cacheItemPoolsContainer) {
            if (method_exists($this, 'getContainer')) {
                $this->cacheItemPoolsContainer = $this->getContainer()->get('epwt_cache_pools');
            } elseif (property_exists($this, 'container')) {
                $this->cacheItemPoolsContainer = $this->container->get('epwt_cache_pools');
            } elseif (method_exists($this, 'get')) {
                $this->cacheItemPoolsContainer = $this->get('epwt_cache_pools');
            } else {
                return false;
            }
        }

        return $this->cacheItemPoolsContainer->get($name);
    }
}
