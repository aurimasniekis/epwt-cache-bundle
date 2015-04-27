<?php

namespace EPWT\CacheBundle\Traits;

/**
 * Trait CacheItemPoolsAwareTrait
 * @package EPWT\CacheBundle\Traits
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
trait CacheItemPoolsAwareTrait
{
    /**
     * @var \EPWT\CacheBundle\Core\CacheItemPoolsContainer
     */
    protected $cacheItemPoolsContainer;

    /**
     * @param string $name ClassItemPool Alias
     *
     * @return \EPWT\Cache\Core\CacheItemPool
     * @throws \EPWT\CacheBundle\Exception\CacheItemPoolNotExistsException
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
