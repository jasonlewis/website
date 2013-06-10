title: Installation
---
## Installation

- [Basset on Packagist](https://packagist.org/packages/jasonlewis/basset)
- [Basset on GitHub](https://github.com/jasonlewis/basset)

To get the latest version of Basset simply require it in your `composer.json` file.

<?prettify?>

	"jasonlewis/basset": "dev-master"

You'll then need to run `composer install` to download it and have the autoloader updated.

> Note that once Basset has a stable version tagged you should use a tagged release instead of the master branch.

Once Basset is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

<?prettify?>

	'providers' => array(
		
		'Basset\BassetServiceProvider'

	)

Basset also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

<?prettify?>

	'aliases' => array(

		'Basset' => 'Basset\Facade'

	)