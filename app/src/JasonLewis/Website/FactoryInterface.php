<?php namespace JasonLewis\Website;

interface FactoryInterface {

	/**
	 * Make a new document.
	 * 
	 * @param  string  $absolutePath
	 * @param  string  $relativePath
	 * @return mixed
	 */
	public function make($absolutePath, $relativePath);

}