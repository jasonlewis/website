<article class="article">
	<header>
		<h2><a href="{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}">{{ $article->getMeta('title') }}</a></h2>
	</header>

	<footer>
		Posted by <span class="author">{{ $article->getMeta('author') }}</span> on the <time datetime="{{ $article->getDate()->format('Y-m-d') }}">{{ $article->getDate() }}</time>
	</footer>

	<div class="body">
		{{ $article->getContent() }}
	</div>

	@include('comments')
</article>