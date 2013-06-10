<rss version="2.0"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
  xmlns:admin="http://webns.net/mvcb/"
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:content="http://purl.org/rss/1.0/modules/content/">

	<channel>
		<title><![CDATA[Jason Lewis]]></title>
		<link>{{ URL::to('/') }}</link>
		<dc:language>en</dc:language>
		<dc:creator>me@jasonlewis.me</dc:creator>
		<dc:rights>Copyright {{ date('Y') }}</dc:rights>
		<dc:date>{{ date('c') }}</dc:date>

		@foreach ($articles as $article)
			<item>
  				<title><![CDATA[{{ $article->getMeta('title') }}]]></title>
  				<link>{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}</link>
  				<guid>{{ URL::action('ArticleController@getArticle', $article->getSlug()) }}</guid>
  				<description><![CDATA[{{ $article->getContent() }}]]></description>
  				<pubDate>{{ $article->getDate() }}</pubDate>
			</item>
		@endforeach

	</channel>
</rss>