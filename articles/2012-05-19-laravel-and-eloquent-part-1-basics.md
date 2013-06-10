title: "Laravel and Eloquent Part 1: Basics"
author: "Jason Lewis"
---
Eloquent is the name given to the super slick ORM that is shipped with [Laravel](http://laravel.com). For those that don't know, ORM stands for [Object-relational Mapping](http://en.wikipedia.org/wiki/Object-relational_mapping). It provides a way of accessing your tables as objects. It's super awesome!

An ORM allows a developer to produce applications quickly and, if the ORM is good, efficiently.

---more---

Let's create our first model. I'm going to create a `Post` model.

<?prettify?>

    // File: application/models/post.php

    class Post extends Eloquent {

    }

That's all there is to it! Thanks for reading.

**BAZINGA!**

Essentially that is all you need to create an Eloquent model. You can now create new records, select records, and update records. However... Eloquent makes a few assumptions because of the lack of details provided. Firstly the table name is taken from the pluralized name of the model, so in this case our table should be named `posts`. Secondly the primary key for the table is assumed to be `id`.

These can be set on the model itself.

<?prettify?>

    // File: application/models/post.php

    class Post extends Eloquent {

        public static $key = 'post_id';
        public static $table = 'blog_posts';

    }

I usually define my table names in each model out of habit really. But if you follow the naming conventions then there's no point.

### Selecting Records

How do we get some posts from our table? Eloquent provides a couple of methods which we'll take a look at.

<?prettify?>

    // Find a single post by ID.
    $post = Post::find(7);

    // Get all posts.
    $posts = Post::all();

    // Use the query builder methods to restrict a select.
    $post = Post::where('deleted', '=', 0)->get();

That's pretty easy. You can use any methods available on the [Fluent Query Builder](http://laravel.com/docs/database/fluent) with Eloquent. We won't go into selecting any further at this stage.

### Inserting Records

Before we select records we need to insert something first. This is where Eloquent begins to shine.

<?prettify?>

    $post = new Post;

    $post->title = 'Laravel is awesome!';
    $post->author = 'Jason';
    $post->body = 'Laravel is awesome, you should use it if you aren\'t already!';

    $post->save();

So we instantiate a new `Post` object. Next we assign some values to some properties and then save it. If you have an array you may want an easier way of assigning the data.

<?prettify?>

    $post = new Post(array(
        'title'  => 'Laravel is awesome!',
        'author' => 'Jason',
        'body'   => 'Laravel is awesome, you should use it if you aren\'t already!'
    ));

    // Or you can use the fill method.
    $post->fill(array(
        'title'  => 'Laravel is awesome!',
        'author' => 'Jason',
        'body'   => 'Laravel is awesome, you should use it if you aren\'t already!'
    ));

    // And don't forget to save!
    $post->save();

Another way of writing the above is as follows.

<?prettify?>

    $post = Post::create(array(
        'title'  => 'Laravel is awesome!',
        'author' => 'Jason',
        'body'   => 'Laravel is awesome, you should use it if you aren\'t already!'
    ));

The `Post::create()` method does exactly the same as instantiating a new object, assigning the values, and calling the save method. The created object is returned, or false on failure.

Lastly we have the standard insert.

<?prettify?>

    Post::insert(array(
        'title'  => 'Laravel is awesome!',
        'author' => 'Jason',
        'body'   => 'Laravel is awesome, you should use it if you aren\'t already!'
    ));

Unlike its counterparts this method simply inserts the data and then returns true or false on failure. The benefit of this method is being able to insert multiple records at once.

<?prettify?>

    Post::insert(array(
        array(
            'title'  => 'Laravel is awesome!',
            'author' => 'Jason',
            'body'   => 'Laravel is awesome, you should use it if you aren\'t already!'
        ),
        array(
            'title'  => 'I love Laravel!',
            'author' => 'Jason',
            'body'   => 'Laravel is the framework love of my life.'
        )
    ));

That's all there really is to inserting records. Just be wary of [mass assignment](http://vimeo.com/38154596) and the security holes it opens.

### Updating Records

We can select and insert, but what about updating? That's just as easy. Like before we need to select a record before we can update it.

<?prettify?>

    $post = Post::find(7);

    $post->author = 'Dayle';

    $post->save();

You also have the earlier available fill method at your disposal if you have an array. Just remember what I said above about mass assignment.

### Conclusion

This is a very quick introduction to the basics of Eloquent. Eloquent is a lot more complex then it's first made out to be. More will be covered soon.