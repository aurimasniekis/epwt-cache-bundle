<?php

namespace EPWT\CacheBundle\Core;

use EPWT\Cache\Core\CacheItemPool;
use EPWT\CacheBundle\Exception\CacheItemPoolAlreadyRegisteredException;
use EPWT\CacheBundle\Exception\CacheItemPoolNotExistsException;

/**
 * Class CacheItemPoolsContainer
 * @package EPWT\CacheBundle\Core
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
class CacheItemPoolsContainer
{
    /**
     * @var CacheItemPool[]|array
     */
    protected $pools;

    public function __construct()
    {
        $this->pools = [];
    }

    /**
     * @param string $name
     * @param CacheItemPool $pool
     *
     * @return $this
     * @throws CacheItemPoolAlreadyRegisteredException
     */
    public function add($name, $pool)
    {
        if ($this->has($name)) {
            throw new CacheItemPoolAlreadyRegisteredException($name);
        }

        $this->pools[$name] = $pool;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->pools);
    }

    /**
     * @param string $name
     *
     * @return CacheItemPool
     * @throws CacheItemPoolNotExistsException
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new CacheItemPoolNotExistsException($name);
        }

        return $this->pools[$name];
    }

    /**
     * @param string $name
     *
     * @return $this
     * @throws CacheItemPoolNotExistsException
     */
    public function delete($name)
    {
        if (!$this->has($name)) {
            throw new CacheItemPoolNotExistsException($name);
        }

        unset($this->pools[$name]);

        return $this;
    }
}
