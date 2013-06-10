title: Using Collections In Views
---
## Using Collections In Views

- [Custom Formats](#custom-formats)

Basset provides a number of helper functions to display your collections within your views. You can use the `basset_stylesheets()` and `basset_javascripts()` functions to display as many collections as you want.

<?prettify?>

	{{ basset_stylesheets('application', 'admin') }}

	{{ basset_javascripts('admin') }}

You can also use the static `Basset::show` method to display your collections, although you must remember to include the extension so Basset can display the stylesheets or javascripts of that collection.

<?prettify?>

	{{ Basset::show('application.css', 'admin.css') }}

This is the same as the `basset_assets()` helper function.

<?prettify?>

	{{ basset_assets('application.css', 'admin.css') }}

If you're using Blade you might prefer to use the Blade extensions provided by Basset.

<?prettify?>

	@stylesheets('application', 'admin');

	@javascripts('admin')

	@assets('application.css', 'admin.css', 'admin.js')


### <a name="custom-formats"></a> Custom Formats

You can use custom formats to display collections with different attributes such as a `media` attribute for stylesheets.

<?prettify?>

	{{ basset_stylesheet(['application' => '<link rel="stylesheet" type="text/css" media="print" href="%s" />']) }}

When using Blade.

<?prettify?>

	@stylesheets(['application' => '<link rel="stylesheet" type="text/css" media="print" href="%s" />'])

The replacement is done with `sprintf` so you need to include the `%s` placeholder which will be replaced with the assets URL.