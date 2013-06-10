title: Configuration
---
## Configuration

- [Publishing Default Configuration](#publishing-default-configuration)
- [Configuration Options](#configuration-options)

### <a name="publishing-default-configuration"></a> Publishing Default Configuration

Basset ships with some sensible defaults and normally doesn't require much configuration. To overwrite the default configuration you can use Artisan to publish Bassets default configuration.

<?prettify?>

	$ php artisan config:publish jasonlewis/basset

Artisan will publish the default configuration file to `app/config/packages/jasonlewis/basset/config.php` (there is also an `aliases.php` file and `collections.php` file).

### <a name="configuration-options"></a> Configuration Options

- [Application Collection](#application-collection)
- [Production Environment](#production-environment)
- [Build Path](#build-path)
- [Node Paths](#node-paths)
- [Gzip](#gzip)
- [Aliases](#aliases)

#### <a name="application-collection"></a> Application Collection

The first thing that might need changing is the default `application` collection. This collection makes some standard assumptions about your applications setup and when possible it's recommended you stick to using this collection when you can.

#### <a name="production-environment"></a> Production Environment

The production environment is used by Basset to determine how the collection will be served. You can set this option as a string or an array of strings.

> See [Serving Workflow](/code/basset/4.0/serving).

#### <a name="build-path"></a> Build Path

The buld path is where built collections and assets will be stored. This path is relative to the public directory.

#### <a name="node-paths"></a> Node Paths

When using filters you'll sometimes be using Node modules for pre-processing assets. Basset will use these paths to locate any of your installed Node modules. We recommend you install them at the base of your application.

#### <a name="gzip"></a> Gzip

For more experienced users you may want to enable Gzip pre-compression during the build process by default. This is simply a global option for the command lines `--gzip` switch.

> See [Gzip Pre-compression](/code/basset/4.0/gzip).

#### <a name="aliases"></a> Aliases

You can provide aliases for filters and assets. This is incredibly useful for shared assets or filters that have the same arguments and options applied to them. Remember to keep things DRY.