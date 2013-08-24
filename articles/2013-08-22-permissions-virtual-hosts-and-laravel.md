title: "Permissions, Virtual Hosts, and Laravel"
author: "Jason Lewis"
---
I've recently just [switched to Linux from Windows](/article/switching-from-windows-to-linux) and I'm not looking back. I'm no stranger to Linux but it has taken me a little bit of time to get used to the development process. There were a few hurdles I needed to jump so I'd like to share them here for both my sake and for anyone else who is having these problems.

---more---

## Where To Create Projects

By default Apache will use `/var/www` as your document root. I didn't really want to put everything in there as I usually have a separate directory for each project. So, I created a `/var/sites` directory.

<?prettify?>

    $ sudo mkdir /var/sites

A bit later on I encountered my first problem. Everything belonged to root. Both myself and Apache couldn't write to anything in there. Now, I'm pretty hopeless when it comes to managing permissions on Linux so after some Googling (and help from my friendly neighbourhood #laravel) here is what I did to make it work.

First, I added myself to the `www-data` group.

<?prettify?>

    $ sudo usermod -a -G www-data jason

I then logged out and logged back in. I read that you might need to do this. I can't confirm or deny its helpfullness but it might be worthwhile. Next I needed to change the ownership of the `/var/sites` directory to the `www-data` group.

<?prettify?>

    $ sudo chown -R www-data:www-data /var/sites

This next bit I found out a bit later when I realised the directories and folders I created within belonged to me and me only. This meant I needed to change the group to `www-data` all the time so Apache could read and write. The fix here was to make sure that any files or directories created inherited the `www-data` group from `/var/sites`.

<?prettify?>

    $ sudo chmod g+s /var/sites

From my understanding this is fine to use just for local development. I'm not a professional when it comes to these things so I don't know the implications of using such a command. I do know that now the `www-data` group is inherited which fixes the problem. Hooray!

## Automating Project Creation

On Windows I never truly had an automated process to quickly create a new project. I'd create the required directories, use Notepad to adjust my `hosts` file and create a new virtual host then restart Apache. It probably only took me a couple of minutes but that's beside the point. Now that I'm on Linux creating a script to do it all is actually pretty easy.

Luckily for me someone had already done just that. Nek from Coderwall has created a [bash script to create Virtual Hosts](https://coderwall.com/p/cqoplg) really quickly. This script works fine, however I wanted to tweak it to better suit my needs.

[So I forked it](https://gist.github.com/jasonlewis/6291983).

There's a comment there that explains how to install it. It's pretty easy. Using it is even easier. Let's say I want to create a new Laravel project.

<?prettify?>

    $ create-project --laravel awesome-laravel-project

It will go ahead and create the required directories, use `composer create-project` to install Laravel, create the virtual host, and restart Apache. I can now open my browser and visit `awesome-laravel-project.dev` to see the Laravel welcome message.

![](/assets/images/create-project-example.png)

I'm sure this isn't a "one size fits all" and it certainly isn't perfect. But it's something. I'd love to hear what others are doing to quickly
create a new project. Be sure to let me know in the comments!


### Update: 24/08/2013

Yesterday I was doing a few things in Laravel when I started getting some more permission errors. For some reason when I created directories from within my application and set the permissions to `777` they would always end up as `755` which meant that my user (who belongs to the `www-data` group) could not write to the files.

As it turns out there is a default [umask](http://en.wikipedia.org/wiki/Umask) set which has a value of `022`. Basically what it means is that this number is subtracted from the permissions you set. So, in my case, I was setting the permissions to `777`, subtract `022` from that and you get `755`. The solution in this case was to set the default `umask` of Apache to `002` (this way we can also ensure others do not have write permissions). To do that on Ubuntu we can edit `/etc/apache2/envvars`, and at the bottom set the `umask`.

    umask 002

Save and restart Apache. You'll now be able to continue editing files as your user that were created by Apache.