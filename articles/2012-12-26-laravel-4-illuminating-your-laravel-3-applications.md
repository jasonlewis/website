title: "Laravel 4: Illuminating Your Laravel 3 Applications"
author: "Jason Lewis"
---
Since you're reading this I'm assuming that you're aware of the upcoming Laravel 4 release. I'm also assuming you've come from some sort of Laravel background, be it version 2 or 3, although for this articles sake I'll only be referring to L3.

At the time of writing (December 2012) Laravel 4 is still under development, although it's at a point where people are beginning to use it and see how it all works. When reading this article take it with a grain of salt as things *might* change in the official release of Laravel 4.

The aim of this article is to give you, a current user of Laravel, an insight into what's changing in Laravel 4 that you'll need to be aware of, especially if you're thinking of upgrading. Before we crack on I'd like to point out that Laravel 3 is a great product and that you shouldn't upgrade your application just for the sake of [keeping up with the Joneses](http://en.wikipedia.org/wiki/Keeping_up_with_the_Joneses). Right then, let's get started.

---more---

### Installation

One of the biggest changes you'll notice is how Laravel is actually installed. In Laravel 3 all we need to do is download a copy of Laravel or clone the repository into the web root and you'd have it working in seconds. Well folks, Laravel 4 takes a different approach.

The bits and pieces that make up Laravel 4 have been decoupled and are now shipped individually. These components are all a part of the [Illuminate repository](http://github.com/illuminate). Luckily for us we don't have to go and download each one and set them all up, that's what [Composer](http://getcomposer.org) is for. If you have no clue on what Composer is I suggest you read up on it and [why it kicks butt](http://net.tutsplus.com/tutorials/php/easy-package-management-with-composer/)!

Composer manages all the dependencies for Laravel 4 so all we need is to clone the application repository and run `composer install`. Once Composer does its thing we'll have a working copy of Laravel 4!

For those of you that don't like all this terminal jibberjabber I'm confident Taylor will be providing a zipball of the latest release. I'm not saying he will I'm just assuming he will so don't hold me to this!

Honestly though using Composer is definitely the way to go. If you're not sure about how to use it I recommend you begin familiarizing yourself with it immediately.

### Routing

From the outside the routing implementation baked into Laravel 4 looks strikingly similar to Laravel 3 (and for a good reason). Routing in Laravel 3 is absolutely amazing, hence why it is pretty much the same in Laravel 4.

While it may look and work the same there are a huge number of improvements and a couple of changes you should be aware of when migrating an application. The most important of these changes is using placeholders for URI segments. In Laravel 3 this might look familiar.

<?prettify?>

	Route::get('user/(:num)', function($id)
	{

	});

In Laravel 4 you use a different form of placeholder.

<?prettify?>

	Route::get('user/{id}', function($id)
	{

	});

I'm going to refer to these as named placeholders. The name doesn't actually have to reflect the variable that's used although in this case it makes the most sense to name it `{id}`.

The biggest change here is that you now have to define the restrictions for each placeholder *after* you've defined the route. `Route::get()` actually returns a new instance of `Illumiante\Routing\Route` if you're interested in digging into the source.

Since we want an ID we'll make sure it only responds to digits.

<?prettify?>

	Route::get('user/{id}', function($id)
	{

	})->where('id', '\d+');

As you can see we use the name of the placeholder and a regular expression pattern to limit it only to digits. Note that `\d+` is the equivalent of `[0-9]+`. Some might welcome this change and others might despair at the loss of the simple helpers in Laravel 3. Look on the bright side, this change has opened up a whole new window of opportunities! The level of flexibility when routing is higher then it's ever been, and that's a good thing.

With Laravel 4 comes the ability to define a default value for your placeholders as well.

<?prettify?>

	Route::get('users/page/{page}', function($page)
	{

	})->where('page', '\d+')
	  ->defaults('page', 1);

Now we're setting the default page to be 1 when no parameter is supplied. Parameters can still be set as optional by using a question mark if you need to. `{page?}` would make the parameter optional and if not supplied the `$page` variable would be `null`.

Filters can still be applied to your routes, but now you don't need to use an annoying array during your declaration of the route.

<?prettify?>

	Route::get('user/profile', function()
	{

	})->before('auth');

As you can probably guess after filters are applied with `after()` instead of `before()`.

You can also continue using placeholders to dynamically build your routes except you reference your actual placeholders name.

<?prettify?>

	Route::get('user/{action}', 'UserController@{action}');

Now `localhost/user/login` will route to `UserController::getLogin()`. Notice that Laravel 4 will prefix the first placeholder with the HTTP verb of the current request, in this case it's `GET`.

`Route::controller()` has also changed a bit, and for the better. For those that like the good old `localhost/controller/method/param1/param2` approach to things you can still do that in Laravel 4.

<?prettify?>

	Route::controller('user', 'UserController');

Now `UserController` will respond to URIs starting with `user`. You can also register an array of controllers with `Route::controllers()`. The methods on the controller should be defined with the HTTP verb prefixed. For example.

<?prettify?>

	public function getProfile()
	{
	    // Would respond to localhost/user/profile if we used the above Route::controller()
	}

Resourceful routes have also been implemented in Laravel 4, however I won't cover off on them here as they weren't around in Laravel 3.

### Controllers

I've touched on these briefly in the previous section on routing but I want to explain a few more things about controllers in Laravel 4 that aren't related to routing. In previous versions of Laravel controllers were named following a convention, `Name_Controller` or `Directory_Name_Controller` or `Bundle_Name_Controller`. While that worked fine there was often requests of allowing controllers to be namespaced. Taylor has heard and he has now provided us with the means!

Here's an example controller that's been given a namespace according to the directories it's residing in.

<?prettify?>

	// File: app/controllers/User/AuthController.php

	namespace Controllers\User;

	use Controllers\BaseController;

	class AuthController extends BaseController {

	}

Note that in my `composer.json` file I'm mapping to these classes with `classmap`, so remember after creating your controllers to run `composer dump-autoload`.

<?prettify?>

	// File: composer.json

	{
	    "autoload": {
	        "classmap": [
	            "app/controllers"
	        ]
	    }
	}

Of course you don't have to use a `Controller` suffix if you don't want to, I've just gone with this as the default controllers that ship with Laravel 4 use the suffix.

Routing to these controllers is the same as normal.

<?prettify?>

	Route::get('user/login', 'Controllers\User\AuthController@getLogin');

Filters can still be applied in the constructor of the controller except you don't have to run the parent constructor anymore. The only difference with filters is it's now `beforeFilter()` and `afterFilter()` instead of `filter('before')` or `filter('after')`.

<?prettify?>

	public function __construct()
	{
	    $this->beforeFilter('csrf');
	}

Filters can also be applied in comment blocks as annotations. I won't get into that here though.

### Bundles

Bundles are now developed as packages for Laravel 4. Some might not be happy with this change but it's crucial for the adoption of Composer. In the grand scheme of things it's a great idea to lose the independent bundle repository and opt for a more widely used solution.

Another big difference with packages is you no longer register them in a file such as `application/bundles.php`. Packages are registered via service providers. I'm not going to get into it too much here as I'll cover off on packages and service providers in another article.

In a nutshell, packages now leave much of the configuration up to the developer. Things like the `handles` key from the old `application/bundles.php` file are no longer around. One big plus is that config files for packages can be published and adjusted in user land instead of users making changes directly to the packages config and possibly losing those changes during an upgrade.

<?prettify?>

	php artisan config:publish jasonlewis/basset

The published config file will be merged with the default config file when the package is run. This all depends on the package developer to have set it up correctly though. I'll go into more details on packages in a future article.

### The loss of HTML and Form

Because Laravel 4 is now Composer based many of the core libraries were no longer needed as the PHP community has already provided a nice selection of libraries we can add as dependencies for our application. Two of the most used libraries that are gone are `HTML` and `Form`.

Don't stress though! Even at the time of writing there are some ports of the original libraries out there as well as some new ones.

- [Form Maker](https://github.com/raftalks/Form) - A different approach to form generation in Laravel 4
- [Meido Form](https://github.com/meido/form) - A port of the Laravel 3 class
- [Meido HTML](https://github.com/meido/html) - A port of the Laravel 3 class

**Update, March 2013:** HTML and Form are now back in the core!

### Tasks and Artisan

Artisan itself has changed quite a bit. Artisan now uses the Symfony console component as a foundation which provides us with a whole range of cool functionality.

Tasks are now called commands. A command can be created anywhere in your application, although it makes sense to create an `app/commands` directory. Here's an example command.

<?prettify?>

	<?php

	use Illuminate\Console\Command;

	class ExampleCommand extends Command {

	  /**
		 * The console command name.
		 *
		 * @var string
		 */
		protected $name = 'example';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = "This is an example command";

		/**
		 * Execute the console command.
		 *
		 * @return void
		 */
		public function fire()
		{
			$this->info('This is a command!');
		}

	}

Commands then need to be registered with the application and resolved with Artisan. Just to quickly show how this is done in your `app/start/global.php` file we'll register the command with the application.

<?prettify?>

	App::instance('command.example', new ExampleCommand);

Now in `app/start/artisan.php` we can resolve the command. Note that this file has `$artisan` within already within its scope.

<?prettify?>

	$artisan->resolve('command.example');

You can also resolve an array of commands.

<?prettify?>

	$artisan->resolveCommands(array(
	    'command.example',
	    'command.another.example'
	));

You can directly add a command instance with `$artisan->add()`.

<?prettify?>

	$artisan->add(new ExampleCommand);

Once a command is resolved correctly you can begin using it from the command line. Typically registering commands can become more involved, especially when injecting dependencies, etc. Please remember these examples are just the absolute basics.

I'll cover commands in more detail in a future article.

**Update:** You can read about commands in my [one stop guide to Laravel commands on Nettuts+](http://net.tutsplus.com/tutorials/php/your-one-stop-guide-to-laravel-commands/).

### Naming Conventions

Laravel 3 used the underscore naming convention for method names.

<?prettify?>

	public function users()
	{
	    return $this->has_many('Users');
	}

In Laravel 4 the method names are now camel cased, as per [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md).

<?prettify?>

	public function users()
	{
	    return $this->hasMany('Users');
	}

A lot of people might find this change unpleasant at first. But for a framework such as Laravel, adopting recognized specifications is important. When migrating an application you'll have to go through and change all your older underscored methods to camel case.

Helper function names are still underscored. This is referred to as `snake_case` in Laravel.

### View Environment and Blade Extensions

The view environment has received a complete overhaul in Laravel 4. It's now a whole lot easier to actually register your own engine and compiler if need be. The biggest change that most people will notice though is that you can no longer directly extend Blade.

To implement your own custom tags when compiling Blade templates you need to actually register your own compiler that extends the Blade compiler. In your `app/start/global.php` you could register it like so.

<?prettify?>

	// Create a new instance of our SwordCompiler. This class just extends
	// Illuminate\View\Compilers\BladeCompiler. Take a look at it for how
	// you can add your own tags.
	$compiler = new SwordCompiler(App::make('files'), App::make('path').'/storage/views');

	// We'll now add our extension using the blade.php file extension and
	// registering our compiler with the CompilerEngine.
	View::addExtension('blade.php', new Illuminate\View\Engines\CompilerEngine($compiler, App::make('files')));

**Update:** You can now use `Blade::extend` to provide custom Blade extensions.

### Helper Functions

A number of helper functions have been renamed or removed entirely in Laravel 4.

- `__()` is now `trans()`
- `url()` is now `path()`
- `set_path()` has been removed
- `str_object()` has been removed
- `root_namespace()` has been removed
- `view()` has been removed
- `render()` has been removed
- `render_each()` has been removed
- `yield()` has been removed
- `get_cli_option()` has been removed
- `get_file_size()` has been removed
- `has_php()` has been removed

Some of these functions may be added back in so I'll try to keep this list up to date.

The most important change here is the `url()` to `path()` name change. Most people in Laravel 3 will be using `path()` to get a path to the application or base directory.

### Conclusion

This article should outline some of the more important changes to Laravel between versions 3 and the upcoming version 4. Bear in mind this isn't *all* of the changes. As the release of Laravel 4 gets closer I'll keep this article up to date.

If you're having any problems with Laravel 4 please jump on to [#laravel](http://laravel.com/irc) on Freenode. At this time we'd like to ask people *not* to post help topics on the forums.
