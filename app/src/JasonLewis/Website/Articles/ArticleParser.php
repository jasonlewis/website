<?php namespace JasonLewis\Website\Articles;

use JasonLewis\Website\Parser;

class ArticleParser extends Parser {

	/**
	 * Article meta data splitter.
	 * 
	 * @var string
	 */
	protected $metaSplitter = '---';

	/**
	 * Article excerpt splitter.
	 * 
	 * @var string
	 */
	protected $excerptSplitter = '---more---';

	/**
	 * Parse an article from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return array
	 */
	public function parse($content, $path)
	{
		foreach (['slug', 'date', 'meta', 'excerpt', 'content'] as $type)
		{
			$parsed[$type] = $this->{'parse'.studly_case($type)}($content, $path);
		}

		return $parsed;
	}

	/**
	 * Parse content from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return string
	 */
	protected function parseContent($content, $path)
	{
		$content = $this->stripMeta($content);

		$content = str_replace($this->excerptSplitter, '', $content);

		return $this->markdown->transform($content);
	}

	/**
	 * Parse an exceprt from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return string
	 */
	protected function parseExcerpt($content, $path)
	{
		$content = $this->stripMeta($content);

		if (strpos($content, $this->excerptSplitter))
		{
			$excerpt = strstr($content, $this->excerptSplitter, true);

			return $this->markdown->transform($excerpt);
		}
	}

	/**
	 * Parse the slug from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return string
	 */
	protected function parseSlug($content, $path)
	{
		return preg_replace('/^[\d]{4}-[\d]{2}-[\d]{2}-/', '', strstr(basename($path), '.md', true));
	}

	/**
	 * Parse the date from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return string
	 */
	protected function parseDate($content, $path)
	{
		return preg_replace('/^([\d]{4}-[\d]{2}-[\d]{2})-.*/', '$1', basename($path));
	}

}