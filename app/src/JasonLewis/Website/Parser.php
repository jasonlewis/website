<?php namespace JasonLewis\Website;

use Michelf\MarkdownExtra as MarkdownParser;
use Symfony\Component\Yaml\Parser as YamlParser;

abstract class Parser {

	/**
	 * Yaml parser instance.
	 * 
	 * @var \Symfony\Component\Yaml\Parser
	 */
	protected $yaml;

	/**
	 * Markdown extra parser instance.
	 * 
	 * @var \Michelf\MarkdownExtra
	 */
	protected $markdown;

	/**
	 * Create a new article parser instance.
	 * 
	 * @param  \Symfony\Component\Yaml\Parser  $yaml
	 * @param  \Michelf\MarkdownExtra  $markdown
	 * @return void
	 */
	public function __construct(YamlParser $yaml, MarkdownParser $markdown)
	{
		$this->yaml = $yaml;
		$this->markdown = $markdown;
	}

	/**
	 * Parse a content string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return array
	 */
	abstract public function parse($content, $path);

	/**
	 * Parse meta data from a string.
	 * 
	 * @param  string  $content
	 * @param  string  $path
	 * @return array
	 */
	protected function parseMeta($content, $path)
	{
		$meta = strstr($content, $this->metaSplitter, true);

		return $this->yaml->parse($meta);
	}

	/**
	 * Strip the meta data from a string
	 * 
	 * @param  string  $content
	 * @return string
	 */
	protected function stripMeta($content)
	{
		return trim(substr(strstr($content, $this->metaSplitter), 3));
	}

}