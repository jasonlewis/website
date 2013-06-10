title: "Laravel: Advanced Validation"
author: "Jason Lewis"
---
Today I'll expand on what I've already covered in my [previous tutorial on forms and validation](/article/laravel-using-forms-and-the-validator). Hopefully this will spark some cool ideas that you can develop and use.

---more---

If you haven't already I recommend you read the previous tutorial before continuing with this one.

### Validation Best Practices

You hear the words "Best Practises" a lot, don't you? I do. Some nameless people have had some crazy discussion about the *best* way to go about solving a certain problem. That's all well and good, but in the end how you implement it is really up to you. When I think about best practices I think about what will solve the problem the best way for *me*, so keep that in mind.

It's often said that validation should **not** be done in the controller (or routes!) but instead in the model. I agree with that, as I like to keep my controllers as minimal as possible. This is often referred to as [separation of concerns](http://en.wikipedia.org/wiki/Separation_of_concerns). I also prefer using exceptions instead of returning false if validation failed. The code looks nicer, and it's more robust.

### Validation Services

To keep things separated we're going to create a validation service. This class will be responsible for handling our rules, messages, and validating. It'll be very simple.

<?prettify?>

    // File: application/models/services/validation.php

    namespace Services;

    use Exception;
    use Validator;
    use ValidateException;

    abstract class Validation {

        /**
         * Validator object.
         *
         * @var object
         */
        protected $validator;

        /**
         * Array of extra data.
         *
         * @var array
         */
        protected $data;

        /**
         * Array of validating input.
         *
         * @var array
         */
        protected $input;

        /**
         * Array of rules.
         *
         * @var array
         */
        public $rules = array();

        /**
         * Array of messages.
         *
         * @var array
         */
        public $messages = array();

        /**
         * Create a new validation service instance.
         *
         * @param  array  $input
         * @return void
         */
        public function __construct($input)
        {
            $this->input = $input;
        }

        /**
         * Validates the input.
         *
         * @throws ValidateException
         * @return void
         */
        protected function validate()
        {
            $this->validator = Validator::make($this->input, $this->rules, $this->messages);

            if($this->validator->invalid())
            {
                throw new ValidateException($this->validator);
            }
        }

        /**
         * Sets a data key/value on the service.
         *
         * @param  string  $key
         * @param  mixed   $value
         * @return void
         */
        public function __set($key, $value)
        {
            $this->data[$key] = $value;
        }

        /**
         * Gets a data key from the service.
         *
         * @param  string  $key
         * @throws Exception
         * @return mixed
         */
        public function __get($key)
        {
            if ( ! isset($this->data[$key]))
            {
                throw new Exception("Could not get [{$key}] from Services\Validation data array.");
            }

            return $this->data[$key];
        }

    }

Pretty simple. It's an abstract class, so we can't create new instances of it, we must *extend* it. You may have noticed we use a `ValidateException` when validation fails. We need to create this class. I like to have an `exceptions.php` file that I store any custom exceptions in.

<?prettify?>

    // File: application/libraries/exceptions.php

    class ValidateException extends Exception {

        /**
         * Errors object.
         *
         * @var Laravel\Messages
         */
        private $errors;

        /**
         * Create a new validate exception instance.
         *
         * @param  Laravel\Validator|Laravel\Messages  $container
         * @return void
         */
        public function __construct($container)
        {
            $this->errors = ($container instanceof Validator) ? $container->errors : $container;

            parent::__construct(null);
        }

        /**
         * Gets the errors object.
         *
         * @return Laravel\Messages
         */
        public function get()
        {
            return $this->errors;
        }

    }

Because of the naming of this class and its file location we'll need to map to this class with the autoloader.

<?prettify?>

    // File: application/start.php

    Autoloader::map(array(
        'ValidateException' => path('app').'libraries/exceptions.php'
    ));

And that's our exception handler setup. What we are trying to achieve is when we call a validation method and the validation fails it will throw a `new ValidateException` which we will then catch and use to redirect our errors. The end result is the same as the last tutorial, except this time we're maintaining a good separation of concerns and keeping our code DRY.

### The Missing Pieces

We've just about got everything in place, we just need something to validate. I'm just going to use some dummy data but yours will be coming in from a form. Let's pretend we're validating a comment before publishing it. Let's create our comments validation service to keep things organized.

<?prettify?>

    // File: application/models/services/comments/validation.php

    namespace Services\Comments;

    use Services\Validation as ValidationService;

    class Validation extends ValidationService {

        /**
         * Validate a comment before publishing it.
         *
         * @throws ValidateException
         * @return void
         */
        public function publish()
        {
            $this->rules = array(
                'name'    => array('required'),
                'email'   => array('required', 'email'),
                'comment' => array('required', 'max:200')
            );

            $this->validate();
        }

    }

If we needed to perform other validations we could create a method for each one, say for example we wanted to validate the input before we edit a comment. This is still quite basic but you can take it even further and define shared rules in a constructor and call the parent constructor afterwards. Your creativity is the only limitation.

Now when we go to post our comment we can use a lovely little try/catch block, very nice indeed!

<?prettify?>

    // File: application/routes.php

    Route::post('posts/(:num)/comment', array('before' => 'csrf', function($id)
    {
        try
        {
            $validation = new Services\Comments\Validation(Input::all());

            $validation->publish();
        }
        catch (ValidateException $errors)
        {
            return Redirect::to('posts/'.$id)->with_errors($errors->get());
        }

        // We can insert the comment now. We could even have a comments creator service.
        Services\Comments\Creator::create(Input::all(), $id);
    });

Now when we *try* to validate and it fails we can *catch* the thrown exception and *get* the errors object from it. Pretty slick.

You could take it a step further and perform the try/catch in the `Services\Comments\Creator::create()` method and when you catch the `ValidateException` simply re-throw it.

<?prettify?>

    // File: application/models/services/comments/creator.php

    namespace Services\Comments;

    class Creator {

        /**
         * Posts a new comment.
         *
         * @param  array  $input
         * @param  int    $post_id
         * @throws ValidateException
         * @return void
         */
        public static function create($input, $post_id)
        {
            try
            {
                $validation = new Validation($input);

                $validation->publish();
            }
            catch (ValidateException $errors)
            {
                throw $errors;
            }

            // Continue with creation of comment.
        }

    }

And now your route looks a lot cleaner.

<?prettify?>

    // File: application/routes.php

    Route::post('posts/(:num)/comment', array('before' => 'csrf', function($id)
    {
        try
        {
            Services\Comments\Creator::create(Input::all(), $id);
        }
        catch (ValidateException $errors)
        {
            return Redirect::to('posts/'.$id)->with_errors($errors->get());
        }


        // Creation of comment was successful!
        return Redirect::to('posts/'.$id);

    });

### Conclusion

Hopefully this article will give you some ideas on how you can go about solving different problems. I'm not saying this is the best solution available, but for some it might be what they're after.