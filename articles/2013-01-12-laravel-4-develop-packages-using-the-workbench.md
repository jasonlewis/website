title: "Laravel 4: Develop Packages Using The Workbench"
author: "Jason Lewis"
---
In the early days of Laravel we were blessed with modules. Modules allowed us to separate parts of our applications into more manageable pieces. This meant that modules could contain their own controllers, routes, libraries, configuration, composers, filters, etc. When Laravel 3 was released modules were revamped and renamed to bundles. A Laravel specific bundle repository was setup and developers could publish their bundles so that others could install them with Artisan. It was a great way to easily drop code in to an existing application.

With Laravel 4 hitting beta soon many are wondering what will happen to bundles and how they'll need to be changed to be of any use. There won't be any Laravel specific bundle repository for Laravel 4 since it's now making use of Composer to handle package dependencies.

---more---

### Beware Laravel 4

Please keep in mind that at the time of writing Laravel 4 is still in a pre-beta stage (soon to be in beta). Remember that some things may not work or some things may be buggy. Support for Laravel 4 is limited due to the limited amount of people who have used or are using it. If you're stuck, the best place for help is [#laravel](http://laravel.com/irc) on Freenode.

If you have a request or a propsal for Laravel 4 make sure you read the notes on [contributing](http://laravel.com/docs/contributing) before opening an issue on [laravel/framework](http://github.com/laravel/framework).

Now, let's crack on!

> Laravel 4 is now stable and readily available. This article is and will remain up to date.

### What about my bundles?

This will probably be the question on the tip of everybody's tongue. Your bundles will need to be tinkered with. Out of the box they probably won't work with a copy of Laravel 4. You might be able to drop a bundle into the `app` directory and `classmap` to them or even autoload them with `psr-0` via Composer. For most bundles they'll need to be written as packages and published to Packagist.

### Consider the PHP community

When you're developing a package please take into consideration the wider PHP community. There are a lot of people out there who either don't use Laravel or don't use any framework at all. They might roll their own code or just whack a bunch of packages from Packagist together. Keep this in mind when you're depending on other packages as well, especially pieces of [Illuminate](http://github.com/illuminate).

The idea here is to give back to the wider PHP community. Tailoring packages for Laravel only is fine, but you're preventing your awesome package from being used by a whole lot of other people. That's why when I developed [ExpressiveDate](https://github.com/jasonlewis/expressive-date) I made sure that it would work outside of Laravel 4 as well.

It also goes without saying that you should write your code using some accepted standards. Laravel 4 (and a bucket load of others) follows [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md). There's also [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) for a complete coding style if you wish to have a look. More information on the PHP-FIG can be found at the [official PHP-FIG website](http://www.php-fig.org).

### Developing A Simple Package

In this article I'm going to run through creating a profiler package for a Laravel 4 application.

#### Using The Workbench

Developing a package is easy when you use the workbench provided by Laravel. Clone and install a fresh copy of Laravel 4. Open up `app/config/workbench.php` and fill out your name and e-mail address as it will appear in your packages `composer.json` file. Now you can use the terminal to create your package.

    $ php artisan workbench solitude/profiler

The vendor name in my case is just a random name that I thought up. Ideally you'll be wanting to use your GitHub username as it will generally make things a lot easier.

You should now see a new `workbench` directory. This directory will hold all of your packages while you develop them.

Laravel will also autoload your packages within your `workbench` directory for you. Remember to run `composer install` first and `composer dump-autoload` when you make any changes.

> You can create this `workbench` directory manually if you don't like using the command line.

#### Structure Of The Package

If you browse the `workbench` directory you'll see how our package has been setup. Here's what my directory structure looks like.

<?prettify?>

    /workbench
       /solitude
          /profiler
             /public
             /src
                /config
                /Solitude
                   /Profiler
                      ProfilerServiceProvider.php
                /lang
                /migrations
                /views
             /tests
             .travis.yml
             composer.json
             phpunit.xml


As you can see a lot of the package foundation is in place for us already. Our profiler isn't going to use language files or migrations, so I'm going to delete these now.

#### The composer.json File

The `composer.json` file that was generated will generally suffice. Sometimes you might not want migrations for your package so you'll want to dump the migrations from the `classmap`. Other times you might want to add other things.

For most packages you will want to add a description. This description will be displayed on the Packagist website when people are browsing. You might also want to add extra authors if a few of you are developing the package.

Remember earlier that I deleted the migrations directory. This is the only directory in the `classmap` so I'm going to remove the entire key. Here's my updated `composer.json`:

<?prettify?>

    // File: workbench/solitude/profiler/composer.json

    {
        "name": "solitude/profiler",
        "description": "A simple yet awesome profiler for Laravel 4.",
        "authors": [
            {
                "name": "Jason Lewis",
                "email": "jason.lewis1991@gmail.com"
            }
        ],
        "require": {
            "php": ">=5.3.0",
            "illuminate/support": "4.0.x"
        },
        "autoload": {
            "psr-0": {
                "Solitude\\Profiler": "src/"
            }
        },
        "minimum-stability": "dev"
    }

We can now validate our `composer.json` via the command line. Make sure you're within the packages directory.

<?prettify?>

    $ composer validate
    ./composer.json is valid, but with a few warnings
    See http://getcomposer.org/doc/04-schema.md for details on the schema
    No license specified, it is recommended to do so

It’s valid, but we could add a license to remove that pesky warning. You can have a look at the [supported licenses](http://getcomposer.org/doc/04-schema.md#license) on the Composer docs. I'm going to go with the 2 clause BSD license.

<?prettify?>

    // File: workbench/solitude/profiler/composer.json

    {
        "name": "solitude/profiler",
        "description": "A simple yet awesome profiler for Laravel 4.",
        "license": "BSD-2-Clause",
        "authors": [
            {
                "name": "Jason Lewis",
                "email": "jason.lewis1991@gmail.com"
            }
        ],
        "require": {
            "php": ">=5.3.0",
            "illuminate/support": "4.0.x"
        },
        "autoload": {
            "psr-0": {
                "Solitude\\Profiler": "src/"
            }
        },
        "minimum-stability": "dev"
    }

For more information on the `composer.json` schema see the [official documentation](http://getcomposer.org/doc/04-schema.md).

#### The Service Provider

For those of you coming from Laravel 3 think of service providers as the start file of a bundle. The service provider is responsible for bootstrapping your package. This may involve the binding of classes within the IoC container or setting up commands and configuration for the package.

This is the service provider that the `workbench` generated for us.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    <?php namespace Solitude\Profiler;

    use Illuminate\Support\ServiceProvider;

    class ProfilerServiceProvider extends ServiceProvider {

        /**
         * Indicates if loading of the provider is deferred.
         *
         * @var bool
         */
        protected $defer = false;

        /**
         * Bootstrap the application events.
         *
         * @return void
         */
        public function boot()
        {
            $this->package('solitude/profiler');
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register()
        {
            //
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return array();
        }

    }

By convention service providers are prefixed with the package name, which in this case is `Profiler`. Service providers also extend the `Illuminate\Support\ServiceProvider` class. Every service provider must provide a `register()` method as it's defined as `abstract` on the `ServiceProvider` class. The `register()` method is run as soon as a service provider is registered with the application.

The other method you can use for bootstrapping is the `boot()` method. This method is run just before the request is dispatched. By default the workbench will bootstrap package configuration, language files, and views inside the `boot()` method with `$this->package(‘solitude/profiler’)`.

#### The Profiler

Now we can begin to code our profiler. Let's create a `Profiler` class.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Profiler.php

    <?php namespace Solitude\Profiler;

    class Profiler {

    }

Profilers are useful when you want to track how long it takes for a particular task to complete. To achieve this the profiler will allow you to set checkpoints during your applications execution.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Profiler.php

    <?php namespace Solitude\Profiler;

    class Profiler {

        /**
         * Added checkpoints.
         *
         * @var array
         */
        protected $checkpoints = array();

        /**
         * Add a new checkpoint.
         *
         * @return void
         */
        public function addCheckpoint()
        {
            $checkpointTime = microtime(true);

            // Grab a debug backtrace array so we can use the line and file name being used to add
            // a checkpoint.
            $trace = debug_backtrace();

            // Build the variables to be used in our checkpoint message.
            $number = count($this->checkpoints) + 1;

            $line = $trace[0]['line'];

            $file = $trace[0]['file'];

            $executionTime = round($checkpointTime - $this->getStartTime(), 4);

            $this->checkpoints[] = compact('number', 'line', 'file', 'executionTime');
        }

        /**
         * Get the checkpoints.
         *
         * @return array
         */
        public function getCheckpoints()
        {
            return $this->checkpoints;
        }

        /**
         * Get the start time.
         *
         * @return int
         */
        protected function getStartTime()
        {
            if (defined('LARAVEL_START'))
            {
                return LARAVEL_START;
            }

            return microtime(true);
        }

    }

Looking at the above code we've defined a protected array of checkpoints. You can add a checkpoint to this array by running the `addCheckpoint()` method. This method does a couple things. First it gets the time of the checkpoint. It then grabs a backtrace array which contains some details we'll use for our message. Lastly we build some variables and then add the checkpoint to the array.

The reason I'm using a `getStartTime()` method is because when you run Artisan it doesn't create a `LARAVEL_START` constant. This method will avoid any ugly exceptions being thrown.

Now we can bind our profiler to the container in the service provider.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    <?php namespace Solitude\Profiler;

    use Illuminate\Support\ServiceProvider;

    class ProfilerServiceProvider extends ServiceProvider {

        /**
         * Indicates if loading of the provider is deferred.
         *
         * @var bool
         */
        protected $defer = false;

        /**
         * Bootstrap the application events.
         *
         * @return void
         */
        public function boot()
        {
            $this->package('solitude/profiler');
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->app['profiler'] = $this->app->share(function($app)
            {
                return new Profiler;
            });
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return array('profiler');
        }

    }

Each service provider has an `$app` property which is an instance of the application container. In the `register()` method we bind our profiler to the container using the array notation. The closure accepts a single parameter which is an instance of the application container. This is useful if you need to inject other bindings into your new instance. For example, if you wanted to have the profiler log to the filesystem you might inject an instance of `Illuminate\Filesystem\Filesystem` that you can use.

<?prettify?>

    return new Profiler($app['files']);

The profiler is also going to be a shared binding so we’ll get the same instance of the profiler throughout the application. If you wanted a new instance of your package every time you can bind an instance of it and Laravel will handle the rest.

<?prettify?>

    $this->app['package'] = new Package;

Lastly we add our bindings to the array returned by the `provides()` method on the service provider.

#### Registering The Service Provider

The service provider must now be registered with the application. Open up `app/config/app.php` and look for the `providers` key of the array. You'll see a bunch of the Illuminate service providers. At the bottom add the `ProfilerServiceProvider`.

<?prettify?>

    // File: app/config/app.php

    'providers' => array(

            ...

            'Solitude\Profiler\ProfilerServiceProvider',

    ),

Let's try it out. In your `app/routes.php` file add the following line at the top.

<?prettify?>

    $app['profiler']->addCheckpoint();

Remember earlier how we bound our profiler in the service provider? Well, now we're grabbing that instance from within the container and calling the `addCheckpoint()` method. Because `routes.php` has `$app` within its scope we can use that. If, however, you don't have an instance of the application container you can use `App::make()`.

<?prettify?>

    App::make('profiler')->addCheckpoint();

Both methods do the exact same thing. You can check if it's working by dumping the checkpoints afterwards.

<?prettify?>

    var_dump($app['profiler']->getCheckpoints());

#### Generating A Report And Using Views

Now that we can track checkpoints within our profiler let's allow a report to be generated. We'll use a view to make it look pretty as well. To use a view we could import the `View` facade alias into our namespace using `use View;`. Instead of doing it that way I'll inject an instance of the view environment.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['profiler'] = $this->app->share(function($app)
        {
            return new Profiler($app['view']);
        });
    }

Now we'll set a property on the `Profiler` class from the constructor so that we can make views.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Profiler.php

    <?php namespace Solitude\Profiler;

    use Illuminate\View\Environment;

    class Profiler {

        /**
         * Added checkpoints.
         *
         * @var array
         */
        protected $checkpoints = array();

        /**
         * Illuminate view environment.
         *
         * @var Illuminate\View\Environment
         */
        protected $view;

        /**
         * Create a new profiler instance.
         *
         * @param  Illuminate\View\Environment  $view
         * @return void
         */
        public function __construct(Environment $view)
        {
            $this->view = $view;
        }

        /**
         * Add a new checkpoint.
         *
         * @return void
         */
        public function addCheckpoint()
        {
            $checkpointTime = microtime(true);

            // Grab a debug backtrace array so we can use the line and file name being used to add
            // a checkpoint.
            $trace = debug_backtrace();

            // Build the variables to be used in our checkpoint message.
            $number = count($this->checkpoints) + 1;

            $line = $trace[0]['line'];

            $file = $trace[0]['file'];

            $executionTime = round($checkpointTime - $this->getStartTime(), 4);

            $this->checkpoints[] = compact('number', 'line', 'file', 'executionTime');
        }

        /**
         * Generate and return a report.
         *
         * @return Illuminate\View\View
         */
        public function generateReport()
        {
            $checkpoints = $this->checkpoints;

            $totalExecutionTime = round(microtime(true) - LARAVEL_START, 4);

            return $this->view->make('profiler::report', compact('checkpoints', 'totalExecutionTime'));
        }

        /**
         * Get the checkpoints.
         *
         * @return array
         */
        public function getCheckpoints()
        {
            return $this->checkpoints;
        }

        /**
         * Get the start time.
         *
         * @return int
         */
        protected function getStartTime()
        {
            if (defined('LARAVEL_START'))
            {
                return LARAVEL_START;
            }

            return microtime(true);
        }

    }

Lastly we need to set the new dependency in the `composer.json` file in the `require` key.

<?prettify?>

    "illuminate/view": "4.0.x"

Notice that we're depending on only this component and not `illuminate/foundation` or `laravel/framework`. I **strongly** recommend you avoid depending on the entire framework for any package (where possible). Even when you're package is going to be tailored for Laravel itself I'm urging you all to avoid depending on the entire framework.

Now, back on track. To generate the report we calculate the total execution time and return a view with an array of bound data. Here's what our `report.blade.php` view file looks like.

<?prettify?>

    // File: workbench/solitude/profiler/src/views/report.blade.php

    <div id="profiler">

        @foreach ($checkpoints as $checkpoint)

        <div class="checkpoint">
            <span class="number">Checkpoint #{{ $checkpoint['number'] }}</span>

            <div class="details">
                From line {{ $checkpoint['line'] }} in {{ $checkpoint['file'] }}
            </div>

            <span class="execution-time">{{ $checkpoint['executionTime'] }} sec.</span>
        </div>

        @endforeach

        <div class="checkpoint">
            <strong>Total execution time:</strong> {{ $totalExecutionTime }} sec.
        </div>

    </div>

In the default route we can now return the report.

<?prettify?>

    // File: app/routes.php

    Route::get('/', function() use ($app)
    {
        return $app['profiler']->generateReport();
    });

But that looks a little boring and we have to either return it on a route or add it to each of our views. Let's make it more robust and pretty.

#### Assets Within A Package

Within the `public` directory of our package we can add assets that can then be published to the applications `public` directory. Let's add some basic styling to our profiler by creating a `styles.css` in the `public` directory.

<?prettify?>

    // File: workbench/solitude/profiler/public/styles.css

    #profiler {
        position: absolute;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.8);
        width: 100%;
        max-height: 250px;
        overflow: auto;
    }

    #profiler > .checkpoint {
        padding: 8px;
        margin: 8px;
        color: #fff;
        font-size: 12px;
        font-family: Monaco, Consolas, monospace;
        text-shadow: 0 1px 0 #111;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
    }

    #profiler > .checkpoint:hover {
        background: rgba(0, 0, 0, 0.2);
    }

    #profiler > .checkpoint > .number {
        display: block;
        margin: 0 0 5px;
        font-weight: bold;
    }

    #profiler > .checkpoint > .execution-time {
        margin: 5px 0 0;
        display: inline-block;
        padding: 2px 4px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        background-color: #ccc;
        box-shadow: 2px 2px 0 #222;
        color: #333;
        text-shadow: 0 1px 0 #fff;
    }

To publish a packages assets we use Artisan's `asset:publish` command.

<?prettify?>

    $ php artisan asset:publish --bench solitude/profiler

> Assets are published to `public/packages/<vendor>/<package>`.

Because our package is being built within the workbench we use the `--bench` switch. If the package is in the `vendor` directory simply omit the switch.

Now that the assets have been published we can add the stylesheet to our `report.blade.php` view.

<?prettify?>

    // File: workbench/solitude/profiler/src/views/report.blade.php

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/solitude/profiler/styles.css') }}" />

#### Using Filters To Append Content

Instead of returning our report from a route we can use filters to append the report to each of our views. The following filters are available to us: `before`, `after`, `close`, `finish`. For our purposes we'll use the `finish` filter which is fired after the response has been sent.

We'll register the filter in the service providers `boot()` method.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('solitude/profiler');

        // Bring the application container instance into the local scope so we can
        // import it into the filters scope.
        $app = $this->app;

        $this->app->finish(function() use ($app)
        {
            echo $app['profiler']->generateReport();
        });
    }

Now the profiler will be displayed on all of your pages without you having to manually add it in.

#### Using Configuration To Disable The Profiler

A package can have as many config files as it needs. To get an item from a packages configuration we prefix the package name or the custom namespace that you can define in the service provider.

<?prettify?>

    Config::get('package::file.key');

If, however, you create a `config.php` file then you do not need to specify the filename.

<?prettify?>

    Config::get('package::key');

This will load the provided key from the `config.php` file.

Let's create a `config.php` file.

<?prettify?>

    // File: workbench/solitude/profiler/src/config/config.php

    <?php

    return array(

        'enabled' => true

    );

Now in our service provider we'll check if the profiler is enabled before we echo the report.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('solitude/profiler');

        // Bring the application container instance into the local scope so we can
        // import it into the filters scope.
        $app = $this->app;

        $this->app->finish(function() use ($app)
        {
            if ($app['config']->get('profiler::enabled', true))
            {
                echo $app['profiler']->generateReport();
            }
        });
    }

If you prefer to use the `Config::get()` approach you can, just remember to import the class with `use Config;` above the class declaration.

Now we can enable and disable the profiler. What if we wanted to enable or disable it during runtime? We could do a `Config::set()` whenever we wanted to enable or disable it, however that would mean that if the configuration key were to change all occurances of `Config::set()` would need to be changed. Instead let's inject an instance of `Illuminate\Config\Repository` to our `Profiler` class.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/ProfilerServiceProvider.php

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['profiler'] = $this->app->share(function($app)
        {
            return new Profiler($app['view'], $app['config']);
        });
    }

As before we now need to set the property in the constructor.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Profiler.php

    <?php namespace Solitude\Profiler;

    use View;
    use Illuminate\Config\Repository;

    class Profiler {

        /**
         * Added checkpoints.
         *
         * @var array
         */
        protected $checkpoints = array();

        /**
         * Illuminate view environment.
         *
         * @var Illuminate\View\Environment
         */
        protected $view;

        /**
         * Illuminate config repository.
         *
         * @var Illuminate\Config\Repository
         */
        protected $config;

        /**
         * Create a new profiler instance.
         *
         * @param  Illuminate\View\Environment  $view
         * @param  Illuminate\Config\Repository  $config
         * @return void
         */
        public function __construct(Environment $view, Repository $config)
        {
            $this->view = $view;
            $this->config = $config;
        }

        /**
         * Add a new checkpoint.
         *
         * @return void
         */
        public function addCheckpoint()
        {
            $checkpointTime = microtime(true);

            // Grab a debug backtrace array so we can use the line and file name being used to add
            // a checkpoint.
            $trace = debug_backtrace();

            // Build the variables to be used in our checkpoint message.
            $number = count($this->checkpoints) + 1;

            $line = $trace[0]['line'];

            $file = $trace[0]['file'];

            $executionTime = round($checkpointTime - $this->getStartTime(), 4);

            $this->checkpoints[] = compact('number', 'line', 'file', 'executionTime');
        }

        /**
         * Generate and return a report.
         *
         * @return Illuminate\View\View
         */
        public function generateReport()
        {
            $checkpoints = $this->checkpoints;

            $totalExecutionTime = round(microtime(true) - LARAVEL_START, 4);

            return $this->view->make('profiler::report', compact('checkpoints', 'totalExecutionTime'));
        }

        /**
         * Get the checkpoints.
         *
         * @return array
         */
        public function getCheckpoints()
        {
            return $this->checkpoints;
        }

        /**
         * Get the start time.
         *
         * @return int
         */
        protected function getStartTime()
        {
            if (defined('LARAVEL_START'))
            {
                return LARAVEL_START;
            }

            return microtime(true);
        }

    }

All that's left to do now is create the `enable()` and `disable()` methods.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Profiler.php

    /**
     * Enable the profiler.
     *
     * @return void
     */
    public function enable()
    {
        $this->config->set('profiler::enabled', true);
    }

    /**
     * Disable the profiler.
     *
     * @return void
     */
    public function disable()
    {
        $this->config->set('profiler::enabled', false);
    }

You can now enable or disable the profiler at any stage during runtime.

<?prettify?>

    // File: app/routes.php

    Route::get('user/login', function() use ($app)
    {
        $app['profiler']->disable();

        // Or with App::make();

        App::make('profiler')->disable();

        // Rest of your code...
    });

#### Publishing A Packages Configuration

With Laravel 4 you can now publish a packages configuration file and have changes cascade back down to the original configuration file. While you develop a package there is really no need to have its configuration published, however, for packages living in the `vendor` directory you can publish them like so.

<?prettify?>

    $ php artisan config:publish solitude/profiler

If our package was in the `vendor` directory it's config files would be published to `app/config/packages/solitude/profiler`.

#### Facades And A Static Interface

Laravel 4 has changed a lot under the hood, but on the outside these changes aren't as noticeable because we can still do things like `View::make()`, `Config::get()`, and `Validator::make()`. What a lot of people don't realise is that these aliases are actually pointing to a facade.

What happens is when you make a static call the facade pulls the bound instance out of the application container and calls the method. Say for example you call `Config::get()`, behind the scenes it does something like `static::$app['config']->get()`, although it's slightly more complex then that.

In our profiler package we want to be able to do `Profiler::addCheckpoint()` wherever we want. To do that we're going to create a facade and then alias it.

<?prettify?>

    // File: workbench/solitude/profiler/src/Solitude/Profiler/Facades/Profiler.php

    <?php namespace Solitude\Profiler\Facades;

    use Illuminate\Support\Facades\Facade;

    class Profiler extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'profiler'; }

    }

The most important part here is the facade accessor. Remember when we bound the profiler to the application container? That's what the facade accessor is, the name we used to bind our instance. In this case it was `profiler`.

Let's alias this facade in `app/config/app.php`. Find the `aliases` key in the array and add in our `Profiler` alias.

<?prettify?>

    'aliases' => array(

        ...

        'Profiler'   => 'Solitude\Profiler\Facades\Profiler',

    ),

Now we can use a static call to add a new checkpoint.

<?prettify?>

    // app/routes.php

    Profiler::addCheckpoint();

    Route::get('user/login', function()
    {
        Profiler::disable();

        // Rest of your code...
    });

### Conclusion

The change from an independent bundle repository to using Composer and Packagist is one of the best moves for Laravel 4. Some will disagree, but embracing the future is very important. It's a well-known fact that a lot of the bundles on the repository are simply wrappers for packages that are already on Packagist. This means that bundles can get out-dated quickly and you're depending on the author to update the bundle.

Remember that packages aren't limited to what I've shown you here. You might also like to have controllers and commands within your package, just as you do with your application. You just need to make sure they're loaded correctly with Composer by either using `psr-0` or the `classmap`.

Hopefully you’ve learnt something from this article. If you have any questions I’ll gladly field them. Remember you can normally catch me in [#laravel](http://laravel.com/irc) on Freenode.

For more information refer to the [official documentation on packages](http://laravel.com/docs/packages).