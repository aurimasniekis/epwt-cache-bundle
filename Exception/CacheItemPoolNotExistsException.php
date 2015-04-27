<?php

namespace EPWT\CacheBundle\Exception;

use Exception;

/**
 * Class CacheItemPoolNotExistsException
 * @package EPWT\CacheBundle\Exception
 * @author Aurimas Niekis <aurimas.niekis@gmail.com>
 */
class CacheItemPoolNotExistsException extends \Exception
{
    public function __construct($name)
    {
        $message = sprintf(
            'CacheItemPool named "%s" not exists!',
            $name
        );

        parent::__construct($message);
    }
}
