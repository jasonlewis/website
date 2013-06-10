<?php namespace JasonLewis\Website;

use Illuminate\Support\ServiceProvider;
use Michelf\MarkdownExtra as MarkdownParser;
use Symfony\Component\Yaml\Parser as YamlParser;
use JasonLewis\Website\Console\DocsRefreshCommand;
use JasonLewis\Website\Console\ArticleRefreshCommand;

class WebsiteServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the service provider.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->app['articles']->registerCollectionCache();

		$this->app['documents']->registerCollectionCache();
	}

	/**
	 * Register the service provider.
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->registerArticles();

		$this->registerDocuments();

		$this->registerCommands();
	}

	/**
	 * Register the documents components.
	 * 
	 * @return void
	 */
	protected function registerDocuments()
	{
		$this->registerDocumentsParser();

		$this->registerDocumentsFactory();

		$this->app['documents'] = $this->app->share(function($app)
		{
			$expires = $app['config']->get('site.expires');

			$loader = new Loader($app['documents.factory'], $app['path.base'].'/docs');

			return new Documents\DocumentCollection($loader, $app['cache'], $expires);
		});
	}

	/**
	 * Register the documents parser.
	 * 
	 * @return void
	 */
	protected function registerDocumentsParser()
	{
		$this->app['documents.parser'] = $this->app->share(function($app)
		{
			return new Documents\DocumentParser(new YamlParser, new MarkdownParser);
		});
	}

	/**
	 * Register the documents factory.
	 * 
	 * @return void
	 */
	protected function registerDocumentsFactory()
	{
		$this->app['documents.factory'] = $this->app->share(function($app)
		{
			return new Documents\DocumentFactory($app['files'], $app['documents.parser']);
		});
	}

	/**
	 * Register the articles components.
	 * 
	 * @return void
	 */
	protected function registerArticles()
	{
		$this->registerArticlesParser();

		$this->registerArticlesFactory();

		$this->app['articles'] = $this->app->share(function($app)
		{
			$expires = $app['config']->get('site.expires');

			$loader = new Loader($app['articles.factory'], $app['path.base'].'/articles');

			return new Articles\ArticleCollection($loader, $app['cache'], $app['request'], $app['paginator'], $expires);
		});
	}

	/**
	 * Register the articles parser.
	 * 
	 * @return void
	 */
	protected function registerArticlesParser()
	{
		$this->app['articles.parser'] = $this->app->share(function($app)
		{
			return new Articles\ArticleParser(new YamlParser, new MarkdownParser);
		});
	}

	/**
	 * Register the article factory.
	 * 
	 * @return void
	 */
	protected function registerArticlesFactory()
	{
		$this->app['articles.factory'] = $this->app->share(function($app)
		{
			return new Articles\ArticleFactory($app['files'], $app['articles.parser']);
		});
	}

	/**
	 * Register the commands.
	 * 
	 * @return void
	 */
	protected function registerCommands()
	{
		$this->app['articles.refresh.command'] = $this->app->share(function($app)
		{
			return new ArticleRefreshCommand($app['articles']);
		});

		$this->app['documents.refresh.command'] = $this->app->share(function($app)
		{
			return new DocsRefreshCommand($app['documents']);
		});

		$this->commands('articles.refresh.command', 'documents.refresh.command');
	}

}