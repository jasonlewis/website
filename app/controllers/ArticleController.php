<?php

use JasonLewis\Website\Articles\ArticleCollection;

class ArticleController extends BaseController {

	/**
	 * Create a new home controller instance.
	 * 
	 * @param  \JasonLewis\Website\Articles\ArticleCollection  $articles
	 * @return void
	 */
	public function __construct(ArticleCollection $articles)
	{
		$this->articles = $articles;
	}

	/**
	 * Read an individual article.
	 * 
	 * @param  string  $slug
	 * @return void
	 */
	public function getArticle($slug)
	{
		if ( ! $article = $this->articles->get($slug))
		{
			return 'Error';
		}

		$this->layout->title = $article->getMeta('title');
		$this->layout->nest('content', 'articles.read', compact('article'));
	}

}