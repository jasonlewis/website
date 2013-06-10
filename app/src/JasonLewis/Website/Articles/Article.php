<?php namespace JasonLewis\Website\Articles;

use ExpressiveDate;

class Article {

	/**
	 * Article URI slug.
	 * 
	 * @var string
	 */
	protected $slug;

	/**
	 * Article published date.
	 * 
	 * @var string
	 */
	protected $date;

	/**
	 * Article meta data.
	 * 
	 * @var array
	 */
	protected $meta = [];

	/**
	 * Article excerpt.
	 * 
	 * @var string
	 */
	protected $excerpt;

	/**
	 * Article content.
	 * 
	 * @var string
	 */
	protected $content;

	/**
	 * Create a new article instance.
	 * 
	 * @param  string  $slug
	 * @param  string  $date
	 * @param  array  $meta
	 * @param  string  $excerpt
	 * @param  string  $content
	 * @return void
	 */
	public function __construct($slug, $date, $meta, $excerpt, $content)
	{
		$this->slug = $slug;
		$this->date = $date;
		$this->meta = $meta;
		$this->excerpt = $excerpt;
		$this->content = $content;
	}

	/**
	 * Get the slug of the article.
	 * 
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Get the published date of the article.
	 * 
	 * @return \ExpressiveDate
	 */
	public function getDate()
	{
		$date = $this->hasMeta('date') ? $this->getMeta('date') : $this->date;
		
		return (new ExpressiveDate($date))->setDefaultDateFormat('jS \o\f F, Y');
	}

	/**
	 * Get all meta data or a specific meta data key.
	 * 
	 * @param  string  $key
	 * @return array|string
	 */ 
	public function getMeta($key = null)
	{
		if ( ! is_null($key) and isset($this->meta[$key]))
		{
			return $this->meta[$key];
		}

		return $this->meta;
	}

	/**
	 * Determine if the article has any meta or a specific meta key.
	 * 
	 * @param  string  $meta
	 * @return bool
	 */
	public function hasMeta($key = null)
	{
		return is_null($key) ? ! empty($this->meta) : isset($this->meta[$key]);
	}

	/**
	 * Get the excerpt of the article.
	 * 
	 * @return string
	 */
	public function getExcerpt()
	{
		return $this->excerpt;
	}

	/**
	 * Determine if the article has an excerpt.
	 * 
	 * @return bool
	 */
	public function hasExcerpt()
	{
		return ! is_null($this->excerpt);
	}

	/**
	 * Get the content of the article.
	 * 
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Determine if the article has any content.
	 * 
	 * @return bool
	 */
	public function hasContent()
	{
		return ! is_null($this->content);
	}

}