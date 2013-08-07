@foreach ($articles->getCollection() as $article)

	<article class="article">
		<header>
			<h1><a href="{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}">{{ $article->getMeta('title') }}</a></h1>

			By <span class="author">{{ $article->getMeta('author') }}</span> on the <time datetime="{{ $article->getDate()->format('Y-m-d') }}">{{ $article->getDate() }}</time>
		</header>

		<div class="body">
			@if ($article->hasExcerpt())
				{{ $article->getExcerpt() }}

				<span class="read-more">
					<a href="{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}">Continue reading &raquo;</a>
				</span>
			@else
				{{ $article->getContent() }}
			@endif
		</div>
	</article>

@endforeach

{{ $articles->links() }}