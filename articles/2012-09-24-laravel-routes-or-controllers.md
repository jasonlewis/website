title: "Laravel: Routes or Controllers"
author: "Jason Lewis"
---
This question pops up quite a bit from (mainly) newcomers to [Laravel](http://laravel.com). It's understandable too, especially if coming from another framework that only offers the choice of controllers. The documentation doesn't explain which is best to use, only how to use them. So it often leaves the reader wondering what's the best? What are the pros and cons? What do others use?

---more---

### Team Routes

Generally speaking routes are the go-to for smaller web applications or a simple API. They provide a wonderful RESTful interface, you don't *need* a heap of files, and they're just a pleasure to use! Routes can respond to extremely complex and expressive URLs and don't require specific ordering of parameters. This is usually the biggest seller for routes.

They do have a couple drawbacks though. You need to take care with your variables scope. Routes use closures (anonymous functions) to handle the response, this means that you can't *share* variables across routes as easily as you can across controller actions. That said you can make use of the `use` keyword to make variables available with the routes scope. Here's an example.

<?prettify?>

	$layout = View::of('layout');

	Route::get('login', function() use ($layout)
	{
	    return $layout->with('content', 'user.login');
	});

The layout variable is an instance of our named view called 'layout'. We can then make use of this variable within routes so we don't have to keep manually writing `View::of('layout')`.

*[See my article on layouts within controllers and routes.](/article/laravel-controller-and-route-layouts)*

Routes, as of Laravel 3, can no longer be nested within a `routes` directory. Back in the olden days of Laravel 2 there was this wonderful directory called `routes`. This magical directory full of awesomeness allowed you to nest routes much the same way as controllers can be nested in Laravel 3. Then along comes Mr. Otwell and destroys it! But we still love him!

Back on track. Even though Laravel won't automatically detect routes that are nested within directories anymore, with [a bit of code](http://forums.laravel.com/viewtopic.php?id=719) slapped into your `start.php` you won't have to worry!

### Team Controllers

Just about everyone understands controllers or has heard of them before. If you haven't I suggest you [have a read of the documentation](http://laravel.com/docs/controllers) before you go on. Controllers are great for any sized application, and they even come in a RESTful flavor, delicious!

Technically speaking there are no real downfalls to controllers. They feature easy organization and maintenance, you can make your controllers RESTful, custom properties and methods can be set, custom base controllers and inheritance, and dynamic property accessibility just to name a few.

The fact that you can adjust the base controller to suit your needs or mock up your own one is just perfect. Run bootstrapping methods in the constructor, setup filters to apply to all controllers that inherit the base controller, or overload some of the default controller methods to add even deeper functionality. The possibilities are amazing.

But there is an aesthetics downfall. Sad, but true. Controllers respond to URLs in the following manner.

<?prettify?>

	http://localhost/controller/method

This means that parameters given to controllers, by default, will always come at the end. This isn't always a big deal, but it means you end up with URLs like this.

<?prettify?>

	http://localhost/discussion/edit/1/discussion-slug-here

You might think that looks fine. It does. But it doesn't even begin to compare to this!

<?prettify?>

	http://localhost/discussion/1-discussion-slug-here/edit

Now that... that is a damn fine looking URL! But we can't do that with a controller. At least not without the help of a very good friend of ours.

### Routes + Controllers = Super Mega Awesome

It's true, combining the flexibility of routes and the power of controllers gives you the best experience possibly imaginable for ANY sized application. But how does one combine the two? Easy.

Routes are able to *use* controller actions. And they're able to pass parameters in, too. Let's use the example I had above.

<?prettify?>

	Route::get('discussion/(:num)-(:any)/edit', array('uses' => 'discussion@edit'));

	// You can also drop the array and just pass in the uses string. Useful if you don't need to name your route.
	Route::get('discussion/(:num)-(:any)/edit', 'discussion@edit');

And bingo! This route now points to the **edit** action on the **discussion** controller. There's a tonne of flexibility with routing to controllers as well. Take a look at this.

<?prettify?>

	Route::get('discussion/(:num)-(:any)/(edit|delete)', 'discussion@(:3)');

See that `(:3)` in there. That basically says "insert whatever the value of the 3rd parenthesis match is". So in this case it can be either **edit** or **delete**. How handy is that. You can use whatever you want, and where ever you want. Here's another, more complex, example.

> Be aware that the above backreferencing is only available in Laravel 3 and not in Laravel 4.

<?prettify?>

	Route::get('api/(v1|v2)/(:any)/(:any).(json|xml)', 'api.(:1).(:2)@(:3)');

Now we're routing our API to controllers with version specifications as well. How handy is that. An example URL might be `http://localhost/api/v2/users/profile.json`

**Note:** In the above examples I'm explicitly using **GET**, if your controller uses both GET and POST or GET and PUT you'd be better off using `Route::any()` instead of defining the same route for all possible verbs.

### Conclusion

I've kind of summed it up already but I'll say it again. To really develop powerful, expressive, and gorgeous applications you need to make use of both routes and controllers. By themselves they're great for simple things, but together they transform into like... a [Super Saiyan](http://images2.wikia.nocookie.net/__cb20120620232811/dragonball/images/2/22/GokuSuperSaiyanVsCooler.png) or a [badass F22 Raptor](http://www.kitsune.addr.com/Rifts/Rifts-Earth-Vehicles/F-22_Raptor.jpg).

You know what, you get the idea.