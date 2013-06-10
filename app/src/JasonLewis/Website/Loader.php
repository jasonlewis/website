<?php namespace JasonLewis\Website;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Loader {

	/**
	 * Factory interface instance.
	 * 
	 * @var \JasonLewis\Website\FactoryInterface
	 */
	protected $factory;

	/**
	 * Path to the documents.
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * Create a new loader instance.
	 * 
	 * @param  \JasonLewis\Website\FactoryInterface  $factory
	 * @param  string  $path
	 * @return void
	 */
	public function __construct(FactoryInterface $factory, $path)
	{
		$this->factory = $factory;
		$this->path = $path;
	}

	/**
	 * Load all items and return as an array.
	 * 
	 * @return array
	 */
	public function load()
	{
		$items = [];

		foreach ($this->getFilesystemIterator($this->path) as $item)
		{
			if ( ! $item->isFile()) continue;

			$absolutePath = $item->getRealPath();

			$relativePath = trim(str_replace([realpath($this->path), '\\'], ['', '/'], $absolutePath), '/\\');

			$items[] = $this->factory->make($absolutePath, $relativePath);
		}

		return $items;
	}

	/**
	 * Get a filesystem iterator instance.
	 * 
	 * @param  string  $path
	 * @return \RecursiveIteratorIterator
	 */
	public function getFilesystemIterator($path)
	{
		return new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
	}
	
}