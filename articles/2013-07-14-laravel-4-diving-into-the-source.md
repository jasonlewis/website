title: "Laravel 4: Diving Into The Source"
author: "Jason Lewis"
---
Those of you that are currently using Laravel in your projects will be aware that when you make a static call like `Route::get()` you're actually hitting a [facade](http://laravel.com/docs/facades). In nearly every article about facades you'll see the example of what is happening in the background, in our case it's be something like `$app['router']->get()`.

In this article I want to talk about how you can find your way around the source. For many this can be a bit troublesome as you'll go to where you think it is but you end up somewhere else entirely.

---more---

## The Facade Root

The quickest way to find where our static methods are being routed to is to dump out the facade root. Let's use the router as an example again. Using Laravel's [`dd`](http://laravel.com/docs/helpers#miscellaneous) helper we'll dump the facade root and kill the execution of our application.

<?prettify?>

    // app/start/global.php
    
    dd(Route::getFacadeRoot());

Reloading our application should print out an `Illuminate\Routing\Router` object. That's the class hiding behind the facade. Back in Sublime hit `Ctrl + P` to find anything and type `Router` (you'll probably find the Symfony `Router` as well, make sure to open up the `Illuminate` one) and hit enter to open the file. Within this class we can now see all those methods that we've been using.

We could also use the PHP function [`get_class`](http://php.net/get_class) to retrieve just the class name of the facade root instead of dumping the entire object.

<?prettify?>

    dd(get_class(Route::getFacadeRoot()));
    
## A Manager? What Now?

For the most part the above technique should suffice. There are, however, a few exceptions. Many facade roots are actually managers. A manager allows a provider to register a number of drivers where each driver implements a common interface. An ideal example of this are the different authentication drivers that Laravel provides, Eloquent and Fluent.

If we were to dump out the facade root of `Auth` we'd get an instance of `Illuminate\Auth\AuthManager`. Inspecting this class you'll notice that none of the methods you actually use on the `Auth` facade are on this class. So where are they? They're actually on the `Illuminate\Auth\Guard` class.

In the `Illuminate\Auth\AuthManager` class look for the following few methods that are of interest to us, `createDatabaseDriver` and `createEloquentDriver`. Here they both are just for reference.

<?prettify?>

    /**
     * Create an instance of the database driver.
	 *
	 * @return \Illuminate\Auth\Guard
	 */
	protected function createDatabaseDriver()
	{
		$provider = $this->createDatabaseProvider();

		return new Guard($provider, $this->app['session']);
	}

    /**
     * Create an instance of the Eloquent driver.
     *
     * @return \Illuminate\Auth\Guard
     */
    public function createEloquentDriver()
    {
        $provider = $this->createEloquentProvider();
    
	    return new Guard($provider, $this->app['session']);
    }

You'll notice that both of these methods are returning an instance of `Illuminate\Auth\Guard`. The only difference between the two is the `$provider` variable that's being injected as each driver has a slightly different way of authentication users.

`Cache` is another example where a manager will be returned via the facade.

## Debugging The Core

It's often handy to venture into the core and dump out variables here and there when you've hit a snag and you're not quite sure if it's a bug. If you find yourself dumping out a variable and not seeing the output then it's more then likely that Laravel is instead using the classes in `bootstrap/compiled.php`. You can safely delete this file and when you're ready to speed things up again run `php artisan optimize`.

If you've deleted the `bootstrap/compiled.php` file and you're still not seeing anything dumped then check your `workbench` directory for any packages you're working on. If those packages depend on any of the Illuminate packages then they'll be used in favor of those in your main `vendor` directory.

## Conclusion

This article is intended as a quick run down of how you can easily browse and debug the source of Laravel. As with anything it will be slightly confusing at first. You'll eventually reach the point where you don't even need to dump out the facade root. You'll just know where to go.

As always if you have any questions hit me up on Twitter or in #laravel on Freenode.