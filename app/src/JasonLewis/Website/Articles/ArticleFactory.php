<?php namespace JasonLewis\Website\Articles;

use Illuminate\Filesystem\Filesystem;
use JasonLewis\Website\FactoryInterface;

class ArticleFactory implements FactoryInterface {

	/**
	 * Illuminate filesystem instance.
	 * 
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Website article parser instance.
	 * 
	 * @var \JasonLewis\Website\Articles\ArticleParser
	 */
	protected $parser;

	/**
	 * Create a new article factory instance.
	 * 
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @param  \JasonLewis\Website\Articles\ArticleParser  $parser
	 * @return void
	 */
	public function __construct(Filesystem $files, ArticleParser $parser)
	{
		$this->files = $files;
		$this->parser = $parser;
	}

	/**
	 * Make a new article instance from the path to an article.
	 * 
	 * @param  string  $absolutePath
	 * @param  string  $relativePath
	 * @return \JasonLewis\Website\Articles\Article
	 */
	public function make($absolutePath, $relativePath)
	{
		$parsed = $this->parser->parse($this->files->get($absolutePath), $absolutePath);

		return new Article($parsed['slug'], $parsed['date'], $parsed['meta'], $parsed['excerpt'], $parsed['content']);
	}

}