title: "Laravel: Getting Started"
author: "Jason Lewis"
---
Almost one year ago a beautiful and expressive PHP 5.3 web applications framework was released to the world. It's name is [Laravel](http://laravel.com). Laravel was created by [Taylor Otwell](https://twitter.com/#!/taylorotwell) to be an extremely powerful yet flexible framework that anyone (with some knowledge!) could just pick up and use. Since its birth Laravel has grown with the help of many contributors to become what it is today, one of the best framework choices for PHP.

I aim to provide Laravel tutorials and code samples every now and again when I get the chance. Today I'll talk about getting started with Laravel and setting up a new project with Git.

---more---

### Creating a Git repository
Open up your terminal and traverse to where you want your project. We'll then create our new project directory, change to it and initialize an empty repository.

<?prettify?>

	$ mkdir laravel-project && cd laravel-project
	$ git init
	Initialized empty Git repository in c:/wamp/www/laravel-project/.git/

Great we have our empty repository, we can now go ahead and fetch a copy of Laravel.

### The Laravel Remote
To grab a copy of Laravel we could've just straight out cloned the GitHub repository with `git clone https://github.com/laravel/laravel.git laravel-project`, however I prefer starting with an empty repository for new projects.

To get Laravel we'll need to add a **remote** to our repository so we can fetch the latest version of Laravel. Once we have a remote we'll perform a `git fetch`.

<?prettify?>

	$ git remote add laravel https://github.com/laravel/laravel.git
	$ git fetch laravel
	remote: Counting objects: 13175, done.
	remote: Compressing objects: 100% (4265/4265), done.
	remote: Total 13175 (delta 9148), reused 12655 (delta 8725)
	Receiving objects: 100% (13175/13175), 3.49 MiB | 504 KiB/s, done.
	Resolving deltas: 100% (9148/9148), done.
	From https://github.com/laravel/laravel
	 * [new branch]      develop    -> laravel/develop
	 * [new branch]      master     -> laravel/master
	 * [new branch]      staging    -> laravel/staging
	 * [new tag]         v3.1.4     -> v3.1.4
	From https://github.com/laravel/laravel
	 * [new tag]         v1.5.0     -> v1.5.0
	 * [new tag]         v1.5.1     -> v1.5.1
	 * [new tag]         v1.5.2     -> v1.5.2
	 * [new tag]         v1.5.3     -> v1.5.3
	 * [new tag]         v1.5.4     -> v1.5.4
	 * [new tag]         v1.5.5     -> v1.5.5
	 * [new tag]         v1.5.6     -> v1.5.6
	 * [new tag]         v1.5.7     -> v1.5.7
	 * [new tag]         v1.5.8     -> v1.5.8
	 * [new tag]         v1.5.9     -> v1.5.9
	 * [new tag]         v2.0.0     -> v2.0.0
	 * [new tag]         v2.0.0-alpha-1 -> v2.0.0-alpha-1
	 * [new tag]         v2.0.0-beta-1 -> v2.0.0-beta-1
	 * [new tag]         v2.0.0-beta-2 -> v2.0.0-beta-2
	 * [new tag]         v2.0.1     -> v2.0.1
	 * [new tag]         v2.0.2     -> v2.0.2
	 * [new tag]         v2.0.3     -> v2.0.3
	 * [new tag]         v2.0.4     -> v2.0.4
	 * [new tag]         v2.0.5     -> v2.0.5
	 * [new tag]         v2.0.6     -> v2.0.6
	 * [new tag]         v2.0.7     -> v2.0.7
	 * [new tag]         v2.0.8     -> v2.0.8
	 * [new tag]         v2.0.9     -> v2.0.9
	 * [new tag]         v2.1.0     -> v2.1.0
	 * [new tag]         v2.1.1     -> v2.1.1
	 * [new tag]         v3.0.0     -> v3.0.0
	 * [new tag]         v3.0.0-beta-2 -> v3.0.0-beta-2
	 * [new tag]         v3.0.1     -> v3.0.1
	 * [new tag]         v3.0.2     -> v3.0.2
	 * [new tag]         v3.0.3     -> v3.0.3
	 * [new tag]         v3.0.4     -> v3.0.4
	 * [new tag]         v3.1.0     -> v3.1.0
	 * [new tag]         v3.1.1     -> v3.1.1
	 * [new tag]         v3.1.2     -> v3.1.2
	 * [new tag]         v3.1.3     -> v3.1.3

Our terminal gives us a nice display of all the branches and tags that are now available to us on the **laravel** remote. If you look at your directory it's still very much empty. So where's our copy of Laravel? We need to perform a **merge** to get the files and folders.

<?prettify?>

	$ git merge laravel/master

We now have the latest version of Laravel.

For arguments sake let's say Taylor releases a new version and you want to merge in the latest changes. It's important to remember that before you fetch anything, branch off so you don't affect your current working branch in case upgrading causes some problems. Once on the new branch you can fetch and merge the changes.

<?prettify?>

	$ git branch -b upgrade
	$ git fetch laravel

	You should see your fetching results here.

	$ git merge laravel/master
	Auto-merging application/routes.php
	CONFLICT (content): Merge conflict in application/routes.php
	Automatic merge failed; fix conflicts and then commit the result.

Uh-oh! Conflict? What could that be? What I did as an example before doing this was delete everything in the `application/routes.php` file, just so we could look at resolving conflicts in Git. You can do this one of two ways, use `git diff <file>` or open up the conflicting file in your favorite editor. I normally go with the editor, but whatever works best for you.

This is what the `application/routes.php` file looks like.

<?prettify?>

	<?php
	<<<<<<< HEAD
	// We have deleted everything, just as an example.
	=======
	/*
	|--------------------------------------------------------------------------
	| Application Routes
	|--------------------------------------------------------------------------
	|
	| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
	| breeze to setup your applications using Laravel's RESTful routing, and it
	| is perfectly suited for building both large applications and simple APIs.
	| Enjoy the fresh air and simplicity of the framework.
	|
	| Let's respond to a simple GET request to http://example.com/hello:
	|
	|		Route::get('hello', function()
	|		{
	|			return 'Hello World!';
	|		});
	|
	| You can even respond to more than one URI:
	|
	|		Route::post('hello, world', function()
	|		{
	|			return 'Hello World!';
	|		});
	|
	| It's easy to allow URI wildcards using (:num) or (:any):
	|
	|		Route::put('hello/(:any)', function($name)
	|		{
	|			return "Welcome, $name.";
	|		});
	|
	*/

	Route::get('/', function()
	{
		return View::make('home.index');
	});

	/*
	|--------------------------------------------------------------------------
	| Application 404 & 500 Error Handlers
	|--------------------------------------------------------------------------
	|
	| To centralize and simplify 404 handling, Laravel uses an awesome event
	| system to retrieve the response. Feel free to modify this function to
	| your tastes and the needs of your application.
	|
	| Similarly, we use an event to handle the display of 500 level errors
	| within the application. These errors are fired when there is an
	| uncaught exception thrown in the application.
	|
	*/

	Event::listen('404', function()
	{
		return Response::error('404');
	});

	Event::listen('500', function()
	{
		return Response::error('500');
	});

	/*
	|--------------------------------------------------------------------------
	| Route Filters
	|--------------------------------------------------------------------------
	|
	| Filters provide a convenient method for attaching functionality to your
	| routes. The built-in "before" and "after" filters are called before and
	| after every request to your application, and you may even create other
	| filters that can be attached to individual routes.
	|
	| Let's walk through an example...
	|
	| First, define a filter:
	|
	|		Route::filter('filter', function()
	|		{
	|			return 'Filtered!';
	|		});
	|
	| Next, attach the filter to a route:
	|
	|		Router::register('GET /', array('before' => 'filter', function()
	|		{
	|			return 'Hello World!';
	|		}));
	|
	*/

	Route::filter('before', function()
	{
		// Do stuff before every request to your application...
	});

	Route::filter('after', function($response)
	{
		// Do stuff after every request to your application...
	});

	Route::filter('csrf', function()
	{
		if (Request::forged()) return Response::error('500');
	});

	Route::filter('auth', function()
	{
		if (Auth::guest()) return Redirect::to('login');
	});
	>>>>>>> origin/master

Between the `<<<<<<< HEAD` and `=======` is what your file looks like, and everything else until `>>>>>>> origin/master` is what the merge found to be conflicting. Let's keep the merged version, so simply delete everything between `<<<<<<< HEAD` and `=======` then remove the `>>>>>>> origin/master`. Then all you need to do is add the file and commit the changes.

<?prettify?>

	$ git add application/routes.php
	$ git commit

Once you commit you'll have the ability to alter the commit message or you can keep the default and save with `:wq`.

Say you did a `git diff` and straight away you know you want to keep the version that's being merged in.

<?prettify?>

	$ git checkout --theirs application/routes.php

Or if you'd like to keep your own version:

<?prettify?>

	$ git checkout --ours application/routes.php

You still need to add the file and commit it.

### Welcome to Laravel

Now that we've finally got our repository sorted we can check to see if our installation is working. Laravel should be right to go out of the box, so open your browser and navigate to your project, e.g., `localhost/laravel-project/public`

You should see the Laravel splash page. Wonderful. You're now ready to begin your new Laravel project. You can add more remotes to your repository, commit and push your changes, fetch and merge updates, and much more.

### Further Reading

- [Laravel Documentation](http://laravel.com/docs)
- [Laravel API](http://laravel.com/api)
- [Laravel Forums](http://forums.laravel.com)
- [#laravel on irc.freenode.net](http://laravel.com/irc)

Until next time!
