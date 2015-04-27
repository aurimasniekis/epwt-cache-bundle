EPWTCacheBundle
===============
[![Latest Stable Version](https://poser.pugx.org/epwt/cache-bundle/v/stable)](https://packagist.org/packages/epwt/cache-bundle) [![Total Downloads](https://poser.pugx.org/epwt/cache-bundle/downloads)](https://packagist.org/packages/epwt/cache-bundle) [![Latest Unstable Version](https://poser.pugx.org/epwt/cache-bundle/v/unstable)](https://packagist.org/packages/epwt/cache-bundle) [![License](https://poser.pugx.org/epwt/cache-bundle/license)](https://packagist.org/packages/epwt/cache-bundle)
[![Analytics](https://ga-beacon.appspot.com/UA-62340336-1/gcds/epwt-cache)](https://github.com/igrigorik/ga-beacon) 


The EPWTCacheBundle provides wrapper for `epwt/cache` library smashed with [Symfony DependencyInjection](https://github.com/symfony/DependencyInjection) magic.

#### EPWT/Cache

EPWT/Cache is PSR-6 (Yes it only proposed) compiliant CacheItemPool implementation for Redis and maybe in future other Cache storage.

## Require

 * Symfony >2.3
 * phpredis/predis ([SncRedisBundle](https://github.com/snc/SncRedisBundle) does really fine)
 
## Instalation

```bash

	composer require epwt/cache-bundle "~1.0"
	
``` 

```php

	public function registerBundles()
	{
		$bundles[] = new EPWT\CacheBundle\EPWTCacheBundle();
	}
	
```
 
## Configuration

All CacheItemPool configuration is done via Symfony Container

### Currently supported drivers:

 * redis
 * sncredis
 
## Configuration Examples

#### Redis Driver

```xml

	<service id="acme.demo.items.pool" class="stdClass">
	    <tag name="epwt_cache_pool" alias="acme_demo_pool" driver="redis" redis-id="acme.demo.redis"/>
	</service>
	
```

#### SncRedis Driver

```xml

	<service id="acme.demo.items.pool" class="stdClass">
	    <tag name="epwt_cache_pool" alias="acme_demo_pool" driver="snc_redis" sncredis-client="default"/>
	</service>
	
```

#### Additional Options

* If you want you can extend `CacheItemPool` class and specify it in `class` attribute.
* By default `CacheItemPool` name is `alias` attribute value but if you want use different one specify with `pool-name` attribute
* If you want to specify whole `CacheItemPool` default TTL use attribute `default-ttl` value is in seconds from setting value


## Usage Examples

* This bundle has `CacheItemPoolsContainer` service with id `epwt_cache_pools`
* This budnle also provides `CacheItemPoolsAwareTrait` with requires `$this->getContainer` method, `$this->container` property or `$this->get()` method (In Controllers only) and provides `getCacheItemPool($name)` method for getting `CacheItemPool`

### With Trait

```php

	class HelloWorldCommand extends ContainerAwareCommand
	{
	    use CacheItemPoolsAwareTrait;
	
	    protected function configure()
	    {
	        $this->setName('acme:hello');
	    }
	
	    protected function execute(InputInterface $input, OutputInterface $output)
	    {
	        $pool = $this->getCacheItemPool('acme_demo_pool');
	
	        $poolItem = new CacheItem('foo');
	        $poolItem->set('bar');
	
	        $pool->save($poolItem);
	    }
	}
	
```

### Without Trait

```php

	class HelloWorldCommand extends ContainerAwareCommand
	{
	    protected function configure()
	    {
	        $this->setName('acme:hello');
	    }
	
	    protected function execute(InputInterface $input, OutputInterface $output)
	    {
	        $pool = $this->getContainer()->get('epwt_cache_pools')->get('acme_demo_pool');
	
	        $poolItem = new CacheItem('foo');
	        $poolItem->set('bar');
	
	        $pool->save($poolItem);
	    }
	}
	
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

EPWTCacheBundle is brought to you by [Aurimas Niekis](https://github.com/gcds).

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/gcds/epwt-cache-bundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.