title: "Error After A Composer Update Of The Laravel 4 Beta"
author: "Jason Lewis"
---
When you're developing an application (or anything for that matter) with a product that is deemed "not yet stable" or as it's more widely known, "beta", you should be expecting changes. I'm referring to the Laravel 4 beta but you could probably apply this article to just about any beta phase, ever.

Laravel 4 uses Composer to install its dependencies and other bits and pieces of the framework. That's just bloody wonderful. So all you have to do is `composer update` every now and again and you'll have the latest changes pulled in, right? Wrong! Dead wrong. That's so wrong that I'm having trouble comprehending the wrongness of it.

---more---

The [laravel/framework](https://github.com/laravel/framework) repository is where the core pieces of the framework are located and where `composer update` pulls its changes from. What seems to happen is people are assuming that that's the only place changes will ever take place. Did you ever stop to think that changes also happen within the applications skeleton? If you're asking what the hell this skeleton thing is then this article is directed at you. The `develop` branch of [laravel/laravel](https://github.com/laravel/laravel/tree/develop) is also going to change during the beta phase.

There have been [numerous](http://forums.laravel.io/viewtopic.php?id=6263) [examples](http://forums.laravel.io/viewtopic.php?id=6224) of people [not updating](https://github.com/laravel/framework/issues/605) and [running into](https://github.com/laravel/framework/issues/568) [problems](https://github.com/laravel/framework/issues/580). Generally a configuration item is changed or added or dropped and after a `composer update` an exception like this is thrown.

<?prettify?>

	FatalErrorException: Error: Class 'Illuminate\Html\HtmlServiceProvider' not found

Or even this.

<?prettify?>

	ErrorException: Notice: Undefined index: domain in /path/to/vendor/laravel/framework/src/Illuminate/Session/SessionServiceProvider.php line 124

I might be being a bit harsh but people don't seem to learn (or search). I've identified a number of examples above but there are countless times when the exact same questions are asked in IRC, over and over again. You might think I'm just having a sook, and I am, but for a bloody good reason. You're using a product that is in beta phase so you should have a good understanding of the product and how it works. You should be able to debug an error to a point where you have something useful to say instead of throwing a stack trace up and saying "it doesn't work".

The best piece of advice is when you `composer update` you should `git pull` to grab the latest changes (if any) to the application skeleton. If you're not using Git then have a look through the recent commits and update the appropriate files. This probably solves 99% of problems. Other issues may be related to an outdated Composer in which case you should run `composer self-update`, delete the Composer cache at `~/.composer/cache` or deleting the `vendor` directory and running a fresh `composer install`.

If you already knew this then good job, I salute you sir/ma'am! If you didn't then switch on and get with the program or wait for the official release in May.

Cheers.

### Update (19/03/2013)

As it turns out I'm not the only one offering advice when it comes to installing and updating the beta. Here's a range of articles by other great contrubitors.

- [Cody Covey: Laravel 4 Installation and Updates](http://www.codycovey.com/laravel-4-installation-and-updates/)
- [Niall O'Brien: Installing and Updating Laravel 4](http://niallobrien.me/2013/03/installing-and-updating-laravel-4/)
- [D2N: Keeping Your Laravel 4 Source Up-To-Date](http://d2n.me/posts/laravel-4-update/)
- [Fideloper: The Actual Best Way to Install Laravel 4 Beta](http://fideloper.com/best-way-to-install-laravel4)

### Update (20/03/2013)

A user by the name of "Bulk" from IRC has brought something to my attention about a [recent change](https://github.com/laravel/framework/commit/58df6abe3793d5bb009601090ca3f4aa4ba09ce3) to the `composer.json` file. Now I'm not exactly sure what's going on but for a lot of people using PHP without Mcrypt installed you'll be getting an outdated version of the framework. Why? Well I'd say Composer will notice you don't have the extension then go and look for a tagged version of the framework that didn't depend on the extension. The net result is you end up with older code that's no longer compatible hence you see errors and none of the above solutions work.

The solution in this case is to simply install and enable the Mcrypt extension. I'm sure you can find something on the internet that'll show you how.

### Update (27/03/2013)

Recently a `php artisan optimize` command has been thrown into the mix and is set to run after running a `composer update/install`. This command dumps an optimized Composer autoload file and also compiles all the classes into a single `bootstrap/compiled.php` file.

For some people that have removed the command or are experiencing strange errors deleting this file solved those issues.