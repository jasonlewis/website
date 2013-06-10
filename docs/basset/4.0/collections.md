title: Collections
---
## Collections

- [Definition](#definition)
- [Adding Assets To A Collection](#adding-assets-to-a-collection)
- [Changing The Working Directory](#changing-the-working-directory)
- [Requiring A Directory](#requiring-a-directory)
- [Requring A Directory Tree](#requiring-a-directory-tree)
- [Excluding Directory Assets](#excluding-directory-assets)
- [Raw Assets](#raw-assets)
- [Default Collection](#default-collection)

### <a name="definition"></a> Definition

In Basset, a collection is a number of stylesheets and/or javascripts grouped together under a common idenfifier. There are a couple of methods available to make a new collection.

<?prettify?>

    Basset::collection('identifier', function($collection)
    {
        // Collection definition.
    });

    Basset::make('identifier', function($collection)
    {
        // Collection definition.
    });

If you're not aliasing the facade you can use the different methods available to get an instance of the Basset environment.

<?prettify?>

    App::make('basset')->collection('identifier', function($collection)
    {
        // Collection definition.
    });

    app('basset')->collection('identifier', function($collection)
    {
        // Collection definition. 
    });

    $app['basset']->collection('identifier', function($collection)
    {
        // Collection definition. 
    });


### <a name="adding-assets-to-a-collection"></a> Adding Assets To A Collection

- [Aliased Assets](#aliased-assets)
- [Assets Outside The Public Directory](#assets-outside-the-public-directory)
- [Remotely Hosted Assets](#remotely-hosted-assets)
- [Package Assets](#package-assets)

Once you're working within a collection definition you can add assets using `$collection->stylesheet()` or `$collection->javascript()`. This method takes a single parameter which should be a relative path to the asset from the public directory.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css');
    });

By explicitly using the `stylesheet` method we're telling Basset that the referenced asset is a stylesheet. You can also use the `javascript` method if you're adding a JavaScript asset. If you'd prefer to let Basset guess the type of asset you can simply use the `add` method.


#### <a name="aliased-assets"></a> Aliased Assets

When an asset is added it's name is first checked against the existing aliases in the configuration. You might like to alias assets that are used in multiple collections. Aliased assets go in the `aliases.php` configuration file.

<?prettify?>

    'assets' => array(
        'jquery' => 'javascripts/jquery.min.js'
    )

Referencing the aliased asset is as easy as using the name of the key.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->javascript('jquery');
    });


#### <a name="assets-outside-the-public-directory"></a> Assets Outside The Public Directory

You can add assets outside the public directory by relatively traversing out and into other directories.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('../app/assets/stylesheets/website.css');
    });

> This is not encouraged and can have unexpected results. Proceed at your own risk.


#### <a name="remotely-hosted-assets"></a> Remotely Hosted Assets

Using an asset that's remotely hosted, e.g., jQuery on the Google CDN, is as simple as providing the URL to the asset.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->javascript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
    });

URLs can also be protocol relative.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->javascript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
    });


#### <a name="package-assets"></a> Package Assets

A packages assets can be added by first registering the package with Basset.

<?prettify?>

    Basset::package('vendor/package');

Or within the `boot()` method of your packages service provider.

<?prettify?>

    $this->app['basset']->package('vendor/package');

You can then use the familiar double colon syntax to reference a packages assets.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('package::stylesheets/website.css');
    });

> Your packages assets must be published to the public directory.


### <a name="changing-the-working-directory"></a> Changing The Working Directory

The default working directory of a collection is the public directory. When adding several assets from a directory it's often easier to change the working directory.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->directory('stylesheets', function($collection)
        {
            $collection->stylesheet('boostrap.css');
            $collection->stylesheet('menu.css');   
            $collection->stylesheet('common.css');
            $collection->stylesheet('website.css');
        });
    });

Working directories can also be nested.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->directory('stylesheets', function($collection)
        {
            $collection->directory('bootstrap', function($collection)
            {
                $collection->stylesheet('boostrap.css');
                $collection->stylesheet('menu.css');
                $collection->stylesheet('common.css');
            });
            
            $collection->stylesheet('website.css');
        });
    });


### <a name="requiring-a-directory"></a> Requiring A Directory

To save adding every asset within a directory you can require the entire directory.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->requireDirectory('stylesheets');
    });

Like assets, the directory is relative to the current working directory. Leave out the parameter to require the current working directory.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->directory('stylesheets', function($collection)
        {
            $collection->requireDirectory();
        });
    });

> This does not recursively require the directory so only assets in the root of the specified directory are required.

### <a name="requiring-a-directory-tree"></a> Requiring A Directory Tree

Requiring a directory tree will recursively traverse a directory and all nested directories and add every asset. The signature for this method is the same as `requireDirectory`.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->requireTree('stylesheets');
    });


### <a name="excluding-directory-assets"></a> Excluding Directory Assets

If you have a directory with several assets you want to require but there a couple of assets you want to exclude you can use the `except` or `only` methods.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->requireDirectory('stylesheets')->except('example.css', 'nested/example.css');
    });

Or only use a selected number of assets.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->requireDirectory('stylesheets')->only('example.css', 'nested/example.css');
    });

### <a name="raw-assets"></a> Raw Assets

Assets can be ignored from a collections build and intead the raw path to the asset will be used. This is useful for assets like Google Prettify which may rely on their location to load in other assets.

<?prettify?>

    $collection->javascript('vendor/prettify.js')->raw();

You can also provide an environment or environments where the raw asset will be served. Any environments not listed will include the asset in any builds.

<?prettify?>

    $collection->javascript('vendor/prettify.js')->rawOnEnvironment('local');

    $collection->javascript('vendor/prettify.js')->rawOnEnvironment('local', 'stage');

    $collection->javascript('vendor/prettify.js')->rawOnEnvironment(['local', 'stage']);

If you have an entire directory of assets that should be served raw then you can use the `raw` and `rawOnEnvironment` methods on the directory itself.

<?prettify?>

    $collection->requireDirectory('raw-javascript')->raw();

### <a name="default-collection"></a> Default Collection

Basset ships with a default collection named `application`. This collection is defined within the configuration file, so to adjust it you should first [publish Bassets configuration](/code/basset/4.0/configuration#publishing-default-configuration).

The directory structure for this default collection is explained in [Asset Organization](/code/basset/4.0/organization). Generally this collection will work out of the box. For some environments some further configuration for filters may be required, e.g., most paths on a Windows environment.