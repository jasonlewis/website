title: Building Collections Via Artisan
---
## Building Collections Via Artisan

- [Production Mode](#production-mode)
- [Development Mode](#development-mode)
- [Gzipping Builds](#gzipping-builds)

Artisan is the command line utility that comes with Laravel. Using Artisan you can build your collections into static files to speed up serving times.

### <a name="production-mode"></a> Production Mode

To achieve the best possible serving times you should always build your collections when you deploy your site to a production environment. When a collection is built all assets will have their filters applied and will be combined into a single static file.

<?prettify?>

	$ php artisan basset:build collection --production

This will build the given collection, or you can build all collections.

<?prettify?>

	$ php artisan basset:build --production

> Make sure you have configured your [production environment](/code/basset/4.0/configuration#production-environment) to have collections displayed correctly.

### <a name="development-mode"></a> Development Mode

By default Basset will build a collection for development before every page load if a build is required. You can find out more about this on [Serving Workflow](/code/basset/4.0/serving).

<?prettify?>

	$ php artisan basset:build collection

This will build the given collection in development mode, or you can build all collections.

<?prettify?>

	$ php artisan basset:build

### <a name="gzipping-builds"></a> Gzipping Builds

You can pre-compress builds by using Gzip. You can find out more about this on [Gzip Pre-compression](/code/basset/4.0/gzip).