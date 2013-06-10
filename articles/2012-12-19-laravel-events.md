title: "Laravel: Events"
author: "Jason Lewis"
---
Have you ever heard of **hooks**? If yes then you should instantly know what an **event** is. But let me explain.

**Definition**
e•vent
*noun*

1. Something that happens or is regarded as happening; an occurrence, especially one of some importance.
2. The outcome, issue, or result of anything: The venture had no successful event.
3. Something that occurs in a certain place during a particular interval of time.

Basically an event is something that happens at a given point in time, or in our case, at a given point in our code. Laravel comes with a very easy to use and powerful event system that you can take full advantage of.

---more---

### Terminology

Before I cover off on events and how they may be useful for you and the applications you develop, let me explain some of the terminology around events.

- **Event**: As above, something that happens at a given point in our code.
- **Listener**: A listener attaches itself, or *listens*, for a particular event.
- **Fire**: The action of calling any listeners for a particular event. Later on I'll explain some other methods of firing events.

### System Events

If you've been using Laravel for a while now or you've read through the documentation then you may have noticed that Laravel itself fires off some events internally. These events are useful to capture output of queries being run, when Laravel itself is starting up or closing down, or even extending the configuration with your own custom loader. Here's a list of events fired, originally compiled by myself and then later updated by TLGreg!

<?prettify?>

	Event::fire('laravel.done                [Response $response]');
	Event::fire('laravel.log                 [String $type, String $message]');
	Event::fire('laravel.query               [String $sql, Array $bindings, String $time]');
	Event::fire('laravel.resolving           [String $type, Mixed $object]');
	Event::fire('laravel.composing: {view}   [View $view]');
	Event::fire('laravel.started: {bundle}   [String $bundle]');
	Event::first('laravel.controller.factory [String $controller]');
	Event::first('laravel.config.loader      [String $bundle, String $file]');
	Event::first('laravel.language.loader    [String $bundle, String $language, String $file]');
	Event::until('laravel.view.loader        [String $bundle, String $view]');
	Event::until('laravel.view.engine        [View $view]');
	Event::first('laravel.view.filter        [String $content, String $path]');
	Event::fire('eloquent.saving             [Eloquent $model]');
	Event::fire('eloquent.saving: {model}    [Eloquent $model]');
	Event::fire('eloquent.updated            [Eloquent $model]');
	Event::fire('eloquent.updated: {model}   [Eloquent $model]');
	Event::fire('eloquent.created            [Eloquent $model]');
	Event::fire('eloquent.created: {model}   [Eloquent $model]');
	Event::fire('eloquent.saved              [Eloquent $model]');
	Event::fire('eloquent.saved: {model}     [Eloquent $model]');
	Event::fire('eloquent.deleting           [Eloquent $model]');
	Event::fire('eloquent.deleting: {model}  [Eloquent $model]');
	Event::fire('eloquent.deleted            [Eloquent $model]');
	Event::fire('eloquent.deleted: {model}   [Eloquent $model]');
	Event::first('500');
	Event::first('404');

I'm not going to go into detail here on each of the events as they should be reasonably self-explanatory.

### Listening for an Event

Listening for an event is easy as all you need to do is use `Event::listen()` with the name of an event and a closure for the actual code of your listener.

<?prettify?>

	Event::listen('404', function()
	{
	    // Show a cool 404 page!
	});

`Event::listen()` will append to other registered listeners on your event. This is fine in most cases, but there are times when you only want it to fire your event. In this case you can override any other listeners.

<?prettify?>

	Event::override('404', function()
	{
	    // Provide a different, but just as awesome, 404 page!
	});

Try listening for some of Laravel's system events and dumping some text, or even dumping one of the parameters, this will give you a bit of insight into how it works.

### How Events are Fired

Once you know how to listen for events you need to know how to fire them. There are a couple of methods available to us to finely tune how our applications respond to events. The most basic way of firing events is using the `Event::fire()` method.

<?prettify?>

	$responses = Event::fire('post.created');

I'm just firing an event I've made up, but let's assume for a moment that this event fires when a blog post is created. You may have noticed I'm actually assigning the response to a variable, `$responses`. When you fire an event it will fire *all* of the listeners on this event and return an array of responses. Why is this useful? Well Laravel itself uses methods like this to adjust the response of a route based on the filters. If you return something in a before filter it will override the response of a route.

There's a lot more to firing events. We can actually fire an array of events if we wanted.

<?prettify?>

	$responses = Event::fire(array('post.created', 'blog.updated'));

Now both of the events above will be fired. What about passing parameters to an event? Easy, just give an array of parameters as the second argument.

<?prettify?>

	$responses = Event::fire('post.created', array($post));

	// And you can pass the same parameters to multiple events.
	$responses = Event::fire(array('post.created', 'blog.updated'), array($post));

Now your listener will receive an instance of the newly created post.

<?prettify?>

	Event::listen('post.created', function(Post $post)
	{
	    // Do something to the new post!
	});

Now you decide you only want to get the response of the first event. Sure you could just shift the first response off the array, however Laravel comes with an `Event::first()` method. This method is identical to `Event::listen()` except it will only return the first response. For that reason I'd avoid calling multiple events with it, especially if you are expecting responses from both.

**Remember** that all events are still fired but only the first response is returned.

The last method available is `Event::until()`. This will fire an event until the first **non null** response is returned.

### Events in the Real World

You might be thinking that events sound wonderful but where exactly do they fit in your application? Let me just say that for some applications they won't have much use. Events are something you should consider when you’re developing an application that can be extended, either by you or someone else.

Let’s consider my above example. You might fire events when posts are created, updated, and deleted. Your application might allow 3rd party extensions, and a developer might come up with a cool extension that generates a short introduction for a post.

<?prettify?>

	Event::listen('post.created', function(Post $post)
	{
	    $introduction = strlen($post->body) > 200 ? substr($introduction, 0, 200).'...' : $post->body;

	    $post->introduction = $introduction;

	    $post->save();
	});

So when a post is created our listener will check the length of the body, if it's greater than 200 characters it reduces it to 200 characters then adds a `...` suffix. We can then set the property on the model and save it.

This obviously assumes that your extensions have been setup to allow the creation of columns on your database table, etc.

Other real world usages could be firing events at points inside a view. This gives extensions the ability of injecting content inside your views without them ever having to edit the original view. You could then fire more events during validation so they can add more rules and error messages to your forms.

### Conclusion and Further Reading

In a nutshell, events are just pure awesome. They take the heavy lifting out of making your application extendable. An application that is easy to extend is gods gift to many developers. So if your application will be extendable, be sure to give events a thought as they may be your ticket to success.

- [Official Documentation: Events](http://three.laravel.com/docs/events)
- [Code Happy: Events](http://codehappy.daylerees.com/events)
- [Laravel Forums: Complete List of Laravel Events](http://forums.laravel.com/viewtopic.php?id=3705)