title: "Laravel: Controller and Route Layouts"
author: "Jason Lewis"
---
In todays application development it's a fairly common practise to have a layout that is used across the vast majority of pages. Most of the time you can just use `View::make()` and bind data to your layout. But that's not a robust solution. Later on you might change your layouts file name from `layouts.core` to `layouts.main` or you might want to make it easy to switch between layouts.

---more---

Hard coding that layout dependency isn't always a good idea, especially when it comes to bigger applications. So how can we make it more flexible so that it grows with our application? With Laravel you have a couple of options. Let's take a look.

### Controller Layouts

Most people will be using controllers over routes. If you're not then don't worry, routes are covered further down. Controllers are great for any application. With Laravel, one of the greatest benefits of using controllers is the baked in layout management. To see what I'm talking about let's take a look at the `Laravel\Routing\Controller` class, which our base controllers extend.

<?prettify?>

    // File: laravel/routing/controller.php

    /**
     * Create a new Controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	// If the controller has specified a layout to be used when rendering
    	// views, we will instantiate the layout instance and set it to the
    	// layout property, replacing the string layout name.
    	if ( ! is_null($this->layout))
    	{
    		$this->layout = $this->layout();
    	}
    }

What's going on here? Well, for starters Laravel is checking to see if we have defined a layout property on our controller. If we have then it'll fire a method. This method does a `View::make()` and assigns the `View` object to our layout property.

So what does this mean for us? Well let's create a new `Users` controller just to see what happens.

<?prettify?>

    // File: controllers/users.php

    class Users_Controller extends Controller {

    	public $layout = 'layouts.main';

    	public function action_index()
    	{
    		$this->layout->nest('content', 'users.index');
    	}

    }

We'll register this controller in our `routes.php` file.

<?prettify?>

    Route::controller('users');

And now we'll create the two views that were used, `layouts.main` and `users.index`.

<?prettify?>

    // File: views/layouts/main.blade.php

    <div class="content">
    	{{ $content }}
    </div>

<?prettify?>

    // File: views/users/index.blade.php

    Welcome back Jason!

And now if you point your browser to `yourwebsite.dev/users` you should see the users index view being nested within the layout. This is made a whole lot more easier by making your controllers extend a base controller. The base controller can set the layout property so you don't have to worry about it in any of your other controllers.

A few important points to remember about controller layouts.

1. You don't actually need to return the layout at the end of the controller. You *do* however need to return a `Redirect` still.
2. Because the layout property is changed to a `View` object in the constructor you'll need to reference the parent constructor if you plan on adding filters to your controllers.

    <?prettify?>

        // File: application/controllers/home.php

        class Home_Controller extends Base_Controller {

            public function __construct()
            {
                parent::__construct(); // Our layout will still be instantiated now.

                $this->filter('before', 'auth')->only('logout');
            }

        }

### Route Layouts

Using flexible layouts within routes isn't as simple or nice as it is with controllers. But don't get down in the dumps yet. There are a number of ways to use layouts with your routes.

#### Named View Layouts

Named views can be defined with the `View::name()` method.

<?prettify?>

    // File: application/start.php

    View::name('layouts.main', 'layout');

This gives our `views/layouts/main.blade.php` view the name `layout`. We can now create views of a named view. Here's how.

<?prettify?>

    Route::get('/', function()
    {
    	return View::of('layout')->nest('content', 'home');
    });

That's pretty easy. But annoying if we wanted to change the name of our view. Another way of doing this is to define a `$layout` variable at the top of your `routes.php` file.

<?prettify?>

    $layout = View::of('layout');

Now with each of our routes we can **use** this variable.

<?prettify?>

    Route::get('/', function() use ($layout)
    {
        return $layout->nest('content', 'home');
    });

Of course you still need to make sure that the `$layout` property is within the scope of the closure. So again, a minor drawback.

#### Filter Layout

A better approach, which was first brought to my attention by [Phill Sparks](https://twitter.com/#!/PhillSparks) in a [Laravel forum topic](http://forums.laravel.com/viewtopic.php?id=531), is a little more elegant and a little less intrusive on your routes. In your `routes.php` you need to define an after filter that Phill has kindly written up for us.

<?prettify?>

    // File: application/routes.php

    Route::filter('layout', function($response, $type = 'html')
    {
        // Redirects have no content and errors should handle their own layout.
        if ($response->status > 300) return;

        switch ($type)
        {
            case 'html':
                // The response has already been prepared so we should
                // render the view ourselves here.
                $response->content = View::make('layout', array(
                    'content' => $response->content,
                ))->render();
            break;
        }
    });

Now we just tell our routes to use this filter as its after filter.

<?prettify?>

    // File: application/routes.php

    Route::get('/', array('after' => 'layout', function()
    {
        return View::make('home');
    });

You'll notice that we no longer return a view of the layout but instead just of what we want nested within the layout. What if you have a lot of routes though, you're still going to have to define this filter for each of them. Unless, of course, you use route groups.

<?prettify?>

    // File: application/routes.php

    Route::group(array('after' => 'layout'), function()
    {
        Route::get('/', function()
        {
            return View::make('home');
        });

        Route::get('about', function()
        {
            return View::make('about');
        });

    });

Now you don't need to worry about setting the filter on each route. Each of the routes within the group will be given the filter. Another handy feature of Phill's approach is adjusting the content type. Let's say that for some routes, such as an API, you wanted to return a JSON encoded response. So in the after filter you set the response type to `json`.

<?prettify?>

    // File: application/routes.php

    Route::get('api/posts.json', array('after' => 'layout:json', function()
    {
        // Fetch and return an array of posts.
        return Posts::fetch_all_as_array();
    });

Now all that's left to do is adjust the filter by adding a `json` type to the `switch` block.

<?prettify?>

    // File: application/routes.php

    Route::filter('layout', function($response, $type = 'html')
    {
        // Redirects have no content and errors should handle their own layout.
        if ($response->status > 300) return;

        switch ($type)
        {
            case 'html':
                // The response has already been prepared so we should
                // render the view ourselves here.
                $response->content = View::make('layout', array(
                    'content' => $response->content,
                ))->render();
            break;

            case 'json':
                // Adjust the response content type for json and
                // json_encode() the content.
                $response->header('content-type', File::mime('json'));
                $response->content = json_encode($response->content);
            break;
        }
    });

How easy is that. Now you don't need to worry about encoding the array before you return it or setting the content type header.

Depending on the size of your application and what your needs are, either of the route based solutions will work for you. You might even be better off not using either if you've only got a couple of routes.

### Conclusion

Personally I use controllers for just about everything, and that includes layouts. Like I've always said, you need to use routes and controllers together to really get the true power of Laravel to show itself.