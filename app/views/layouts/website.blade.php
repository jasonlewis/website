<!doctype html>

<html lang="en">
	<head>
		<title>{{ $title }} &ndash; Jason Lewis</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="alternate" type="application/rss+xml" title="Jason Lewis RSS Feed" href="{{ URL::to('rss') }}" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		@stylesheets('website')

		<script>
			var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-30437380-1"]);_gaq.push(["_setDomainName","jasonlewis.me"]);_gaq.push(["_trackPageview"]);(function(){var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})()
		</script>
	</head>

	<body onload="prettyPrint()">

		<div class="container">
			<header>
				<a href="{{ URL::to('/') }}"><img src="{{ URL::asset('assets/images/me.jpg') }}" /></a>

				@include('layouts.partial.navigation')
			</header>

			@if (isset($nav))
				<div class="side-nav">
					{{ $nav }}
				</div>
			@endif

			<div class="content">
				{{ $content }}
			</div>

			<footer>
				<div class="left">Copyright &copy; 2012 - {{ date('Y') }} Jason Lewis</div>
				<div class="right"><a href="#">Back To Top</a></div>
			</footer>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
		@javascripts('website')
	</body>
</html>