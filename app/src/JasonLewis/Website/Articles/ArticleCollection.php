<?php namespace JasonLewis\Website\Articles;

use Illuminate\Http\Request;
use JasonLewis\Website\Loader;
use JasonLewis\Website\Collection;
use Illuminate\Cache\CacheManager;
use Illuminate\Pagination\Environment as Paginator;

class ArticleCollection extends Collection {

	/**
	 * Illuminate http request instance.
	 * 
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * Illuminate pagination environment instance.
	 * 
	 * @var \Illuminate\Pagination\Environment
	 */
	protected $paginator;

	/**
	 * Collection identifier used for caching.
	 * 
	 * @var string
	 */
	protected $identifier = 'articles';

	/**
	 * Create a new article collection instance.
	 * 
	 * @param  \JasonLewis\Website\Loader  $loader
	 * @param  \Illuminate\Cache\CacheManager  $cache
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Pagination\Environment  $paginator
	 * @param  int  $expires
	 * @param  array  $items
	 * @return void
	 */
	public function __construct(Loader $loader, CacheManager $cache, Request $request, Paginator $paginator, $expires, $items = [])
	{
		$this->loader = $loader;
		$this->cache = $cache;
		$this->request = $request;
		$this->paginator = $paginator;
		$this->expires = $expires;

		parent::__construct($this->loader, $this->cache, $expires, $items);
	}

	/**
	 * Order by the articles date either descending or ascending.
	 * 
	 * @param  bool  $descending
	 * @return \JasonLewis\Website\Articles\ArticleCollection
	 */
	public function orderByDate($descending = true)
	{
		$this->sortBy(function($article) { return $article->getDate()->format('Y/m/d'); });

		if ($descending)
		{
			$this->reverse();
		}

		return $this;
	}

	/**
	 * Reverse the article collection.
	 * 
	 * @return \JasonLewis\Website\Articles\ArticleCollection
	 */
	public function reverse()
	{
		$this->items = array_reverse($this->items);

		return $this;
	}

	/**
	 * Paginate the collection.
	 * 
	 * @param  int  $perPage
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function paginate($perPage = 5)
	{
		$totalPages = ceil(count($this->items) / $perPage);

		// Get the page and make sure that the page hasn't been tampered with. If the page is
		// out of bounds then we'll default to the last page or the first page.
		$page = $this->request->query('page', 1);

		if ($page > $totalPages)
		{
			$page = $totalPages;
		}
		elseif ($page < 1)
		{
			$page = 1;
		}

		$articles = array_slice($this->items, ($page - 1) * $perPage, $perPage);

		return $this->paginator->make($articles, count($this->items), $perPage);
	}

}