<article class="article">
	<header>
		<h1><a href="{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}">{{ $article->getMeta('title') }}</a></h1>

		By <span class="author">{{ $article->getMeta('author') }}</span> on the <time datetime="{{ $article->getDate()->format('Y-m-d') }}">{{ $article->getDate() }}</time>
	</header>

	<div class="body">
		{{ $article->getContent() }}
	</div>

	@include('comments')
</article>