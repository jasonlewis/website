<?php namespace JasonLewis\Website\Documents;

use JasonLewis\Website\Parser;

class DocumentParser extends Parser {

	/**
	 * Article meta data splitter.
	 * 
	 * @var string
	 */
	protected $metaSplitter = '---';

	/**
	 * Parse the docs content.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return array
	 */
	public function parse($content, $path)
	{
		foreach (['slug', 'meta', 'content'] as $type)
		{
			$parsed[$type] = $this->{'parse'.studly_case($type)}($content, $path);
		}

		return $parsed;
	}

	protected function parseSlug($content, $path)
	{
		return strstr($path, '.md', true);
	}

	protected function parseContent($content, $path)
	{
		$content = $this->stripMeta($content);

		return $this->markdown->transform($content);
	}

}