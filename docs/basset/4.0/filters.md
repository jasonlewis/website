title: Filters
---
## Filters

- [Applying Filters](#applying-filters)
- [Chaining Filters](#chaining-filters)
- [Filter Constructor Arguments](#filter-constructor-arguments)
- [Filter Requirements](#filter-requirements)
- [Interacting With Filter Instance](#interacting-with-filter-instance)
- [Node Filters](#node-filters)
- [Filter Executables](#filter-executables)

### <a name="applying-filters"></a> Applying Filters

Filters can be applied to an individual asset, a directory of assets, or an entire collection.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter');

        $collection->requireDirectory('javascripts')->apply('ExampleDirectoryFilter');
    })->apply('ExampleCollectionFilter');
    ~~~


### <a name="chaining-filters"></a> Chaining Filters

You can chain multiple filters to an individual asset, a directory of assets, or an entire collection.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->andApply('SecondExampleAssetFilter');
    });

When chaining multiple filters it's often easier on the eyes to assign the asset or directory to a variable.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $asset = $collection->stylesheet('stylesheets/website.css')
        $asset->apply('ExampleAssetFilter');
        $asset->apply('SecondExampleAssetFilter');

        $directory = $collection->requireDirectory('javascripts');
        $directory->apply('ExampleDirectoryFilter');
        $directory->apply('SecondExampleDirectoryFilter');
    });

### <a name="filter-constructor-arguments"></a> Filter Constructor Arguments

Most filters will accept a couple of arguments for the constructor. You can set these arguments yourself or let Basset find the missing arguments.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->setArguments('/path/to/sass', 'path/to/node');
    });

If you're unsure of what constructor arguments are required you can let Basset attempt to find the constructors arguments. When possible, it's recommended that you supply the paths to any arguments.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->findMissingConstructorArgs();
    });


### <a name="filter-requirements"></a> Filter Requirements

- [Class Exists](#class-exists)
- [Asset Is](#asset-is)
- [Environment Is](#environment-is)
- [Production Build](#production-build)
- [Development Build](#production-build)
- [Asset Is Stylesheet](#asset-is-stylesheet)
- [Asset Is Javascript](#asset-is-javascript)

Requirements can be set on filters so that they're only applied when a certain condition is met.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->when(function($asset)
        {
            return $asset->isRemote();
        });
    });

The closure given to `when()` receives an instance of the asset that it's being applied to. The closure should return `true` when the filter is to be applied and `false` when it should be skipped for this particular asset. In the above example only assets that are being remotely hosted will have the filter applied. In this case the filter will be skipped because the asset `stylesheets/website.css` is on our local machine.

#### <a name="class-exists"></a> Class Exists

Apply the filter to an asset when a given class exists.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->whenClassExists('ExampleFilterClass');
    });

> This is useful for filters such as `CssMinFilter` that depend on the `CssMin` class.

#### <a name="asset-is"></a> Asset Is

Apply the filter to an asset when its name matches the given pattern. The pattern supplied should be a valid regular expression without the delimiters.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->requireDirectory('stylesheets')->apply('ExampleAssetFilter')->whenAssetIs('.*\.css');
    });

#### <a name="production-build"></a> Production Build

Apply the filter to an asset when running a production build.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->whenProductionBuild();
    });

#### <a name="development-build"></a> Development Build

Apply the filter to an asset when running a development build.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->whenDevelopmentBuild();
    });

#### <a name="environment-is"></a> Environment Is

Apply the filter to an asset when the application environment is one of the given environments.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->whenEnvironmentIs('production', 'prod');
    });

#### <a name="asset-is-stylesheet"></a> Asset Is Stylesheet

Apply the filter to an asset that is a stylesheet.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->whenAssetIsStylesheet();
    });

#### <a name="asset-is-javascript"></a> Asset Is Javascript

Apply the filter to an asset that is a javascript.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->javascript('javascripts/website.js')->apply('ExampleAssetFilter')->whenAssetIsJavascript();
    });

### <a name="interacting-with-filter-instance"></a> Interacting With Filter Instance

Some filters such as the `CoffeeScriptFilter` have public methods that adjust filter specific options. You can adjust these options by using the `beforeFiltering()` method.

<?prettify?>

    Basset::collection('name', function($collection)
    {
        $collection->stylesheet('stylesheets/website.css')->apply('ExampleAssetFilter')->beforeFiltering(function($filter)
        {
            $filter->setSomeOption(true);
        });
    });

### <a name="node-filters"></a> Node Filters

Many of the filters used Node modules to perform the filtering on an asset. For these filters it's recommended that you install your modules at the root of your Laravel application, that's the same level as your `composer.json` file.

If you'd like to install modules to a different location be sure to add that path to the `node_paths` configuration key.

### <a name="filter-executables"></a> Filter Executables

- [Windows Compatibility](#windows-compatibility)

When using the `findMissingConstructorArgs()` method on filters Basset will attempt to locate the executables for filters.

#### <a name="windows-compatibility"></a> Windows Compatibility

On a Windows machine it can be quite difficult for Basset to determine the path to things like Ruby or Node. The quickest way to get the default collection working is to add the paths to your machines environment variables.

1. Open the Environment Variables dialogue box. On Windows 7/8 this is done by either going into the Start Screen and typing "environment variables" or by right-clicking "My Computer", clicking on "Properties", then from the left sidebar clicking "Advanced system settings" and then "Environment Variables..." at the bottom.
2. New variables should be added to "System variables" so that they're available globally to applications such as Wamp.
3. Click "New..." and, using Ruby as an example, type `RUBY_PATH` as the variable name.
4. The variable value should by the path to the executable with some exceptions (detailed below).
5. Once done click on "OK" and if using Wamp you'll need to restart the services for the environment variables to be loaded.

Typically the path will be something like `C:\bin\ruby\bin\ruby.exe` or `C:\bin\nodejs\node.exe`, however for some Node modules that are used as exectuables you'll need to provide a different path. As an example let's say you use CoffeeScript and you've used Node to install the package globally so that it's available from the command line. In this instance the environment variable will be named `COFFEE_BIN` and its value would be something like `C:\Users\Jason\AppData\Roaming\npm\node_modules\coffee-script\bin\coffee`. In even rarer cases you may need to give the exact path to the `.js` file.

The best method of determing the correct suffix to use is to check the source of the filter you're using and take note of the constructor. If the variable name is `$coffeeBin` then the environment variable will be `COFFEE_BIN`, if it's `$nodePath` then you'll use `NODE_PATH`.

> For a guide on Windows environment variables you can watch my [Introduction To Windows Environment Variables](https://vimeo.com/67982085).