<?php namespace JasonLewis\Website;

use Illuminate\Cache\CacheManager;

abstract class Collection extends \Illuminate\Support\Collection {

	/**
	 * Loader instance.
	 * 
	 * @var \JasonLewis\Website\Loader
	 */
	protected $loader;

	/**
	 * Illuminate cache manager instance.
	 * 
	 * @var \Illuminate\Cache\CacheManager
	 */
	protected $cache;

	/**
	 * Time in minutes before articles expire from cache.
	 * 
	 * @var int
	 */
	protected $expires = 1440;

	/**
	 * Collection identifier used for caching.
	 * 
	 * @var string
	 */
	protected $identifier;

	/**
	 * Create a new article collection instance.
	 * 
	 * @param  \JasonLewis\Website\Loader  $loader
	 * @param  \Illuminate\Cache\CacheManager  $cache
	 * @param  int  $expires
	 * @param  array  $items
	 * @return void
	 */
	public function __construct(Loader $loader, CacheManager $cache, $expires, $items = [])
	{
		$this->loader = $loader;
		$this->cache = $cache;
		$this->expires = $expires;

		parent::__construct($items);
	}

	/**
	 * Register the collections cache and store each of the cached items on
	 * the collection.
	 * 
	 * @return \JasonLewis\Website\DocumentCollection
	 */
	public function registerCollectionCache()
	{
		// If the cache does not contain the items identifier then we'll use the loader
		// to load all items and store the items in the cache.
		$items = $this->cache->remember($this->identifier, $this->expires, function()
		{
			$items = $this->loader->load();

			// Spin over each of the items and store the individual articles in the cache
			// as well so that when we request a single item we can quickly pull it from
			// the cache.
			foreach ($items as $item)
			{
				$this->cache->put($item->getSlug(), $item, $this->expires);
			}

			return $items;
		});

		// With the cached items we'll now spin over each one and using the array access
		// assign it to our document collection.
		foreach ($items as $item) $this[$item->getSlug()] = $item;

		return $this;
	}

	/**
	 * Forget the collection cache.
	 * 
	 * @return \JasonLewis\Website\DocumentCollection
	 */
	public function forgetCollectionCache()
	{
		$this->cache->forget($this->identifier);

		return $this;
	}

}