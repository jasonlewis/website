title: "Laravel: Using Forms and the Validator"
author: "Jason Lewis"
---
Forms are an essential part of any web application. We use them for logging in and registering users, requesting feedback, submitting comments, and many other things. However, building them and then validating the submitted data can become a nuisance. Laravel provides us with some neat classes to make things more intuitive.

---more---

### Forms in Laravel

For the most part standard HTML tags can be used, however Laravel can make your life a lot easier in many ways. For example, Laravel has a `Form::label()` method which allows you to link labels and form elements with corresponding IDs. Let's create a simple register form.

<?prettify?>

	// File: application/views/register.blade.php

	<h2>Register!</h2>

	{{ Form::open('register', 'post') }}

	{{ Form::label('username', 'Username') . Form::text('username', Input::old('username')) }}
	{{ Form::label('email', 'E-mail') . Form::text('email', Input::old('email')) }}
	{{ Form::label('password', 'Password') . Form::password('password') }}

	{{ Form::submit('Register!') }}

	{{ Form::token() . Form::close() }}

We open a POST form which will point to the register route, create some labels and inputs and generate a CSRF token then close the form. Want a secure form instead, just use `Form::open_secure()`. How about a form that accepts file uploads, it's easy, just use `Form::open_for_files()`.

You may have noticed the `Input::old()` as the default value for the username, we'll get to that shortly. For now just remember it's there.

There's a whole range of Form methods at your disposal to generate some nice forms. You can view them all at the [official documentation](http://laravel.com/docs/views/forms).

### Cross Site Request Forgery

I won't go in depth explaining what CSRF is, I'll leave that to [Jeff Atwood's lovely article](http://www.codinghorror.com/blog/2008/09/cross-site-request-forgeries-and-you.html) explaining what it is and how to fix it.

Laravel's `Form::token()` method generates a random token and stores it in the session (this means you need sessions enabled) then displays this token in a hidden form field. On the request handling the form submission we can use the built-in CSRF filter to confirm that there was no tampering and that the request is valid.

Let's take a look at the filter.

<?prettify?>

	// File: application/routes.php

	Route::filter('csrf', function()
	{
		if (Request::forged()) return Response::error('500');
	});

We can customize this however we want, but for this tutorial I'm just going to leave it as is.

Let's begin working on our POST route to handle the user registration.

<?prettify?>

	// File: application/routes.php

	Route::post('register', array('before' => 'csrf', function()
	{
		// Handle the user registration
	}));

That's all there is to handling CSRF. All we do is tell Laravel that before this route is run we want to run through the CSRF filter. If a response is returned from that filter then the route is not run.

### Validating Forms

Now that we can trust the incoming request, we need to validate the data the user submitted data. You should never, ever, trust user input.

*I'm doing my validation in the route, you should actually do this in a model so as it doesn't clutter up your routes.*

<?prettify?>

	// File: application/routes.php

	Route::post('register', array('before' => 'csrf', function()
	{
		$rules = array(
			'username' => array('required', 'unique:users,username'),
			'email'	   => array('required', 'email', 'unique:users,email'),
			'password' => array('required', 'min:7')
		);

		$validation = Validator::make(Input::all(), $rules);

		if ($validation->fails())
		{
			// Validation has failed.
			return Redirect::to('register')->with_input()->with_errors($validation);
		}

		// Validation has succeeded. Create new user.
	}));

1. Define an array of rules. The key for each array element is to correspond with the name of the form input. The values for each array key are the rules for that input, so a username is **required** and it should be **unique**.
2. We make a new Validator instance, the first parameter is an array of input (our POST data), the second parameter are the rules for the input.
3. Now we run the validation, and if it fails we'll redirect back to the GET register route with the input and with the validation errors.
4. If it passes, we'll create the new user and log them in (or something).

The pattern we are adopting here is referred to as the **[Post/Redirect/Get design pattern](http://en.wikipedia.org/wiki/Post/Redirect/Get)** and is the preferred way to prevent duplicate form submissions.

You can see a full list of available rules at the [validation documentation](http://laravel.com/docs/validation#validation-rules).

### Display validation errors

All Laravel views will have a `$errors` variable bound to it automatically, unless you set one yourself. This variable is a `Laravel\Messages` object, the same object used to hold the errors that are generated by the validator. This is particularly useful for checking for errors and not having to first check if the `$errors` variable is set. You may have noticed in the above route when the validation fails, we redirect with **input** and **errors**.

The `$validation->errors` property is also a `Laravel\Messages` object that holds all of the errors Laravel found with the submitted data. Laravel will detect that you redirected with errors and bind this object to your view for you in the `$errors` variable.

Remember the `Input::old()` methods used in our view? When we redirect with input, that method will display the value that was submitted. So if we said our username was `Jason` and registration failed, the username field would be automatically populated with the value `Jason`.

*Passwords don't normally accept a default value, however you can set the value in the attributes array.*

<?prettify?>

	{{ Form::password('password', array('value' => Input::old('password'))) }}

Back to our form, remember that we have the `$errors` variable available to us. Let's make our form look a little nicer by showing error messages if validation failed.

<?prettify?>

	// File: application/views/register.blade.php

	<h2>Register!</h2>

	{{ Form::open('register', 'post') }}

	{{ Form::label('username', 'Username') . Form::text('username', Input::old('username')) }}
	{{ $errors->first('username') }}

	{{ Form::label('email', 'E-mail') . Form::text('email', Input::old('email')) }}
	{{ $errors->first('email') }}

	{{ Form::label('password', 'Password') . Form::password('password') }}
	{{ $errors->first('password') }}

	{{ Form::submit('Register!') }}

	{{ Form::token() . Form::close() }}

When there are errors relating to a field they'll be shown below the input boxes. If you need to wrap your error messages in a HTML tag to adjust the styling you can do so in the second parameter.

<?prettify?>

	{{ $errors->first('username', '<span class="error">:message</span>') }}

Or if you want to just display the first error encountered, regardless of what field it belongs to.

<?prettify?>

	{{ $errors->first() }}

Or you can just display all the errors.

<?prettify?>

	@if($errors->has())

	We encountered the following errors:

	<ul>
		@foreach($errors->all() as $message)

		<li>{{ $message }}</li>

		@endforeach
	</ul>

	@endif

### Custom Rules and Messages

A lot of the time you'll want to have your own rules to validate input with. Before Laravel 3 you would have to extend the `Laravel\Validator` class to add your own rules. With the addition of bundles a more robust solution was needed so that multiple classes weren't created. Laravel 3 allows you to create your own rules using `Validator::register()`

<?prettify?>

	// File: application/start.php

	Validator::register('starts_with', function($attribute, $value, $parameters)
	{
		return starts_with($value, $parameters[0]);
	});

A rule simply returns true if it passes or false if it fails. In my example I'm using a Laravel helper function to see if a field starts with the first parameter. We could use the rule like so.

<?prettify?>

	$rules = array(
		'website' => array('required', 'starts_with:http://')
	);

You'll notice we need to declare the field as required as well. You'll also need an error message defined for the rule.

<?prettify?>

	// File: application/language/en/validation.php

	'custom' => array(
		'website_starts_with' => 'Website must start with http://'
	)

If you'd prefer to set the error message at run-time you can as well.

<?prettify?>

	$rules = array(
		'website' => array('required', 'starts_with:http://')
	);

	$messages = array(
		'website_starts_with' => 'Website must start with http://'
	);

	$validation = Validator::make(Input::all(), $rules, $messages);

### Conclusion

We now have a nice form (although the example is not styled) which POSTs to a route that validates the input, if we encounter errors it redirects with the input and the errors back to our form where the errors are displayed.

That's it for today.