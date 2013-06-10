title: Asset Organization
---
## Asset Organization

**Warning:** Assets should never be outside of the public directory. Doing so can cause problems with some of Basset's functionality and with some filters. If you wish to hide your Less or Sass files then use htaccess. You've been warned.

The organization of assets can vary from project to project, however if you're using the default `application` collection then you'll want need to correctly setup your assets. The following directory structure is recommended.

> This should be viewed as a recommendation and as such some projects might want to organize their assets differently.

<?prettify?>

	/public
	    /assets
	        /javascripts
	            /coffeescripts
	        /stylesheets
	            /less
	            /sass
	/node_modules

Under the `public` directory you'll need to create an `assets` directory. The `assets` directory will contain your `javascripts` and `stylesheets` directories. The next level of directories are only required if you wish to use CoffeeScript, Less or Sass. It's entirely up to you. It's recommended that you install your Node modules in a `node_modules` directory on the same level as `public`.

The `application` collection will handle these directories for you and apply the correct filters so that minimal setup by you is required.

> See [Default Collection](/code/basset/4.0/collections#default-collection).