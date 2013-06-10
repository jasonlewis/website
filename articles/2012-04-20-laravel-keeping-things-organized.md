title: "Laravel: Keeping Things Organized"
author: "Jason Lewis"
---
As an application grows you begin to see things become unorganized. Your `start.php` file is filled with a mixture of filters, composers, named views, events, validations, macros, and autoloadings. Everything begins to become a mess, more so now since Laravel ships with the `start.php` containing a bit of logic. A lot of the above mentioned code can go in `routes.php`, but if you're like me then you'll prefer to keep only routes in that file.

---more---

So how can we keep our code nice and clean? It's rather simple actually, we'll just split our logic over multiple files. Personally, I create a new directory in `application` called `start`. You can name this directory whatever you please.

I have the following files in my `start` directory.

<?prettify?>

	application/start/autoloading.php
	application/start/composers.php
	application/start/filters.php
	application/start/validation.php

The logic each file contains should be fairly self-explanatory. You can add whatever other files you need, such as **events** or **macros**. Let's take a little look what my `application/start/validation.php` file contains.

<?prettify?>

	// File: application/start/validation.php

	/**
	 * Validate that the day supplied is in range of the days in the month selected.
	 */
	Validator::register('dob_days', function($attribute, $value, $parameters)
	{
	    list($field) = $parameters;

	    $month = Input::get($field);

	    return date('t', mktime(0, 0, 0, $month, 1, 0)) >= $value;
	});

	/**
	 * Validate that a timezone is in the timezones array.
	 */
	Validator::register('timezone', function($attribute, $value)
	{
	    $timezones = Config::get('timezones');

	    return array_key_exists($value, $timezones);
	});

The file only has the logic related to registering extra validation rules, and when I want to add more I know exactly where to go.

Now that we have separated our logic we need to load all these files in. We can do this by manually requiring the files in our `start` directory from our `application/start.php` file. This is how I do it.

<?prettify?>

	// File: application/start.php

	/**
	 * Require start scripts.
	 */
	require __DIR__ . DS . 'start' . DS . 'autoloading.php';

	require __DIR__ . DS . 'start' . DS . 'filters.php';

	require __DIR__ . DS . 'start' . DS . 'composers.php';

	require __DIR__ . DS . 'start' . DS . 'validation.php';

Don't like the idea of manually writing it all? Well you can automate the process by using a `FilesystemIterator`.

<?prettify?>

	// File: application/start.php

	/**
	 * Require start files.
	 */
	$files = new FilesystemIterator(__DIR__.'/start');

	foreach ($files as $file)
	{
	    if ($file->isFile())
	    {
	        require $file->getPathname();
	    }
	}

It's entirely up to you how you do it.

### Conclusion

We now have our logic separated and our `start.php` file isn't over crowded. We know exactly where everything is by simply looking in our `start` directory. For a smaller application it might not be necessary, but as applications grow it can be helpful knowing that everything has its own place.