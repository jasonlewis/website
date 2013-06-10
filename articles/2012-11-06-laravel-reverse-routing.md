title: "Laravel: Reverse Routing"
author: "Jason Lewis"
---
In your applications you often need to create a link to another page, it's a standard thing really. Laravel offers a few ways to do this.

<?prettify?>

	URL::to('/users/login');
	URL::to_action('users@login');
	URL::to_route('login');

You may have seen the first method used and you may be using this method yourself. If you are I urge you to continue reading as it's not a great solution, especially if you find yourself changing a whole bunch of links because you changed the route for your login page.

---more---

### Named Routes

When you define routes in your `routes.php` you can also give them a name. This name should be unique to your application, if developing bundles you should also prefix it with your bundles name, so that you avoid naming conflicts. This is what a named route looks like.

<?prettify?>

	Route::get('users/login', array('as' => 'login', 'uses' => 'users@login'));

*For these examples I'll be routing to a controller, you can still use closures for named routes.*

In our views we can now use `HTML::link_to_route()` to generate a link to our login page. This `HTML` method actually makes use of the `URL::to_route()` method that was shown at the beginning of this article however we can now supply some anchor text.

<?prettify?>

	{{ HTML::link_to_route('login', 'Login to your Account') }}

The benefits should be apparent. Say you want to change your login URL to `users/signin`, if you aren't using named routes you'd have to go through and manually change all URLs to point to the new URL. That's a lot of wasted time. With named routes you simply change the route in `routes.php` and all of your links will now point to the new URL.

### Action Based Routes

For applications that use controllers there is another option available for generating links and URLs. Action based routing works much the same as named routes, however instead of giving our routes names we just use the controller and action. Let's take a look at our `Users` controller.

<?prettify?>

	<?php

	class Users extends Controller {

		/**
		 * Controller is RESTful.
		 *
		 * @var bool
		 */
		public $restful = true;

		/**
		 * Show the users login form.
		 *
		 * @return string
		 */
		public function get_login()
		{
			return 'Login to your account.';
		}

	}

In `routes.php` we need to tell the router of this controller so that we can browse to it.

<?prettify?>

	Route::controller('users');

Now we can begin generating links to our login page with `HTML::link_to_action()`. Again, this method makes use of the `URL::to_action()` method.

<?prettify?>

	{{ HTML::link_to_action('users@login', 'Login to your Account') }}

The result will be the same as using named routes. But something doesn't seem right. We can't change the actual route without changing the controller and action. Let's change how we tell the router of our controller to using an actual route instead.

<?prettify?>

	Route::get('users/login', 'users@login');

Refreshing should show you the same result as before. However now we can change the route to `users/signin` and the action doesn't need to change.

### Reverse Routing

What is reverse routing? The two methods I've described above are referred to as "reverse routing". Reverse routing makes your application so much more flexible. But there's more to it then just creating links to routes or controllers. Reverse routing allows you to pass in parameters that you've defined on your routes.

Let me show you what I mean.

<?prettify?>

	Route::get('users/(:any)/about', 'users@about');

This route will take requests such as `yoursite.com/users/jason/about` and map it to the `get_about()` method on the `Users` controller.

Using reverse routing we can create a link to it and pass in any parameters that we have defined. Optional parameters, if not supplied, are removed from the generated link.

<?prettify?>

	{{ HTML::link_to_action('users@about', 'About Jason', array('jason')) }}

And now the link created should be something like `yoursite.com/users/jason/about`. Pretty cool, right? If you are using actions and your parameters are all being placed on the end then it's because you're using `Route::controller()`. Laravel is unable to reverse route controllers like that, as it automatically assumes all parameters will be on the end.

So if you are getting a URL like `yoursite.com/users/about/jason` then make sure you define a route that *uses* the controller and action.

The same can be applied to name routes.

<?prettify?>

	Route::get('users/(:any)/about', array('as' => 'about', 'uses' => 'users@about'));

And now just use `HTML::link_to_route()`.

<?prettify?>

	{{ HTML::link_to_route('about', 'About Jason', array('jason')) }}

### Taking it Further

You can take reverse routing even furhter if you use it a lot within your application.

#### URL::name\_of\_route()

[@machuga](https://twitter.com/machuga) shared this in [#laravel](http://laravel.com/irc), a way of easily creating URLs to named routes.

Most of you will be familiar with Laravel's intuitive way of creating a `where()` in Fluent.

<?prettify?>

	User::where_username('Jason')->first(); // SQL: SELECT * FROM `users` WHERE `username`='Jason' LIMIT 1

	// As opposed to...
	User::where('username', '=', 'Jason')->first();

Using PHPs `__callStatic()` magic method we can apply the same principal to named routes.

1. Create a `application/libraries/url.php` file.
2. Use the following `__callStatic()` method to map any unmatched static calls to the `URL::to_route()` method.

    <?prettify?>

	    <?php

	    class URL extends Laravel\URL {

	    	/**
	    	 * Map unmatched static calls to a possible named route.
	    	 *
	    	 * @param  string  $route
	    	 * @param  array   $parameters
	    	 * @return string
	    	 */
	    	public static function __callStatic($route, $parameters)
	    	{
	    		return static::to_route($route, $parameters);
	    	}

	    }

3. Open up `application/config/application.php` and find the `URL` alias at the bottom. Remove it and save this file.
4. You should now be able to generate URLs to your named routes with a simpler syntax: `URL::about('jason')`

#### HTML::link\_to\_name\_of\_route()

Following the steps as above you can extend the HTML class so that creating links is a whole lot nicer. Here's what your `application/libraries/html.php` class will look like.

<?prettify?>

	<?php

	class HTML extends Laravel\HTML {

		/**
		 * Map unmatched static calls.
		 *
		 * @param  string  $method
		 * @param  array   $parameters
		 * @return string
		 */
		public static function __callStatic($method, $parameters)
		{
			if (starts_with($method, 'link_to_'))
			{
				array_unshift($parameters, substr($method, 8));

				return call_user_func_array('HTML::link_to_route', $parameters);
			}

			return parent::__callStatic($method, $parameters);
		}

	}

So if the method that was called starts with `link_to_` then we'll prepend the `$parameters` array with the name of the route. As before, you can now use a simpler syntax: `HTML::link_to_about('About Jason', array('jason'))`

You'll also notice that the parent `__callStatic()` method is returned if the method name did match a link to a named route. This will preserve any macros that you write. Thanks to **Vilhjálmur Magnússon** for pointing this out in the comments.

### Conclusion and Further Reading

There's been quite a bit covered in this article so don't worry if you don't understand it all at once. There's not a whole lot of difference between named routes and action based routes. Just remember that you need to actually route to an action to get true reverse routing, using `Route::controller()` won't cut it.

- [Laravel Documentation: Named Routes](http://three.laravel.com/docs/routing#named-routes)
- [Laravel Documentation: Controller Routing](http://three.laravel.com/docs/routing#controller-routing)
- [A Nerdy Mom: Automatically Generate Controller Names](http://nerdmom.wordpress.com/2012/06/23/laravel-auto-generate-named-routes-for-easy-reverse-routing/) &ndash; This is a nice idea, however it requires the modification of core Laravel files. You've been warned!
- [Laravel.IO: Reverse Routing with Controller Actions](http://laravel.io/topic/2/reverse-routing-with-controller-actions)