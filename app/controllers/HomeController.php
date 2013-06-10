<?php

use JasonLewis\Website\Articles\ArticleCollection;

class HomeController extends BaseController {

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
	 * Show the index page.
	 * 
	 * @return void
	 */
	public function getIndex()
	{
		$this->layout->title = 'Ramblings From The Land Down Under';
		$this->layout->nest('content', 'articles.index', ['articles' => $this->articles->orderByDate()->paginate(5) ]);
	}

	/**
	 * Show the articles with some pagination to look at older articles.
	 * 
	 * @return void
	 */
	public function getArticles()
	{
		return $this->getIndex();
	}

	/**
	 * Show the RSS feed as an XML response.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function getRss()
	{
		$rss = View::make('layouts.rss', ['articles' => $this->articles->orderByDate()])->render();

		return Response::make($rss, 200, ['content-type' => 'application/xml']);
	}

	/**
	 * Show the about page, all about me!
	 *
	 * @return void
	 */
	public function getAbout()
	{
		$this->layout->title = 'About Me';
		$this->layout->nest('content', 'pages.about');
	}

	public function getLaravelTutorials()
	{
		$this->layout->title = 'Laravel Tutorials';
		$this->layout->nest('content', 'pages.laravel');	
	}

}