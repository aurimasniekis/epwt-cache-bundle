<?php

namespace EPWT\CacheBundle\Exception;

/**
 * Class CacheItemPoolAlreadyRegisteredException
 * @package EPWT\CacheBundle\Exception
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
class CacheItemPoolAlreadyRegisteredException extends \Exception
{

    public function __construct($name)
    {
        $message = sprintf(
            'CacheItemPool named "%s" already registered!',
            $name
        );

        parent::__construct($message);
    }
}
