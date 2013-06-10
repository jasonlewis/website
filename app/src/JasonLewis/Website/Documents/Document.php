<?php namespace JasonLewis\Website\Documents;

class Document {

	/**
	 * Documents slug.
	 * 
	 * @var string
	 */
	protected $slug;

	/**
	 * Document meta data.
	 * 
	 * @var array
	 */
	protected $meta = [];

	/**
	 * Documents content.
	 * 
	 * @var string
	 */
	protected $content;

	/**
	 * Create a new document instance.
	 * 
	 * @param  string  $slug
	 * @param  array  $meta
	 * @param  string  $content
	 * @return void
	 */
	public function __construct($slug, $meta, $content)
	{
		$this->slug = $slug;
		$this->meta = $meta;
		$this->content = $content;
	}

	/**
	 * Get the documents slug.
	 * 
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Get the documents content.
	 * 
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
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

}