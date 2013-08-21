title: "Switching From Windows To Linux"
author: "Jason Lewis"
---
In the past I've always done my development on Windows. At one stage I had planned to do a series on my Windows development environment but I never got around to it. What a shame.

Don't get me wrong, I never truly *hated* Windows, although it did have a couple of shortcomings that annoyed me but I always worked around them. Recently though I've just become tired of it. A few days ago I was getting weird results with some unit tests. No matter what I did it just wasn't working, yet at the same time it was working fine on my CentOS server.

So, I did what any sane person would do. I begun trialling a few different Linux distributions to find one I really liked.

---more---

![](/assets/images/ubuntu-gnome.png)

## So Many Choices

As we all know, there are a *lot* of Linux distributions out there. Many are simply based on a prominent distribution but have their own twists packaged in. I wanted something that looked nice and was still pretty customizable. In the end I only tested 3 distros:

1. [Ubuntu](http://ubuntu.com/)
2. [Elementary OS](http://elementaryos.org/)
3. [Ubuntu Gnome](http://ubuntugnome.org/)

Ubuntu is arguably the most popular Linux distribution because of its user friendliness. You can easily install Ubuntu and never really have to open a terminal. I like Ubuntu, it allows me to have a fully featured desktop but still use the terminal for all the things. But, like others, I don't really like the whole Unity thing. It's a mood killer.

Next I tried out Elementary OS and I must say I was visually impressed. But on the whole it didn't suck me in. Sure it looked nice out of the box but it felt like it was missing something. Also, it was too... OS X.

Finally I arrived at Ubuntu Gnome. Now, I did actually attempt an install of GNOME 3 over Unity on Ubuntu, but ended up killing it and staring at a black screen. After an hour of hair pulling I gave it the flick. This time I went with the [official Ubuntu Gnome flavor](http://ubuntugnome.org) and it worked an absolute treat. Once it was installed I knew this was the one I wanted.

## Making It Look Pretty

Now I'm a sucker for making things look nice. I must say that out of the box Ubuntu Gnome is pretty sleek. But I know it can be made to look even better. I ended up stumbling upon a Gnome Shell theme called [Elegance Colors](https://github.com/satya164/elegance-colors). I won't go into detail on how to install it as the readme covers it all really well, just know that you need the "User Themes" extension (listed below).

I'm also running a handful of useful extensions. You can install extensions through the **Tweak Tool**. Here are the extensions that I have installed.

- [Remove Accessibility](https://extensions.gnome.org/extension/112/remove-accesibility/)
- [User Themes](https://extensions.gnome.org/extension/19/user-themes/)
- [AlternateTab](https://extensions.gnome.org/extension/15/alternatetab/)
- [Dash to Dock](https://extensions.gnome.org/extension/307/dash-to-dock/)
- [Quit Button](https://extensions.gnome.org/extension/156/quit-button/)
- [Status Area Horizontal Spacing](https://extensions.gnome.org/extension/355/status-area-horizontal-spacing/)
- [Impatience](https://extensions.gnome.org/extension/277/impatience/)
- [TopIcons](https://extensions.gnome.org/extension/495/topicons/)
- [Show Desktop From Overview](https://extensions.gnome.org/extension/496/show-desktop-from-overview/)
- [Media Player Indicator](https://extensions.gnome.org/extension/55/media-player-indicator/)

There's so many more extensions though. Also take note that if you happen to be on Gnome 3.8 some of the above extensions probably aren't required.

You'll also want to configure your extensions through the Tweak Tool.

All that's left is to go find yourself a [sexy looking wallpaper](http://www.vladstudio.com/wallpapers/).

## A Terminal Fit For A King

As developers we do spend a lot of our time in a terminal. So it goes without saying that our terminal should be very attractive. Now, I don't mind `gnome-terminal` so I haven't switched, but if you want something like [Terminator](https://launchpad.net/terminator) then by all means go for it. The only thing I suggest is that you switch to `zsh`, or [Z Shell](http://www.zsh.org/). It's dead easy to install as well.

<?prettify?>

    $ sudo apt-get install zsh

Then you just have to make `zsh` your default shell.

<?prettify?>

    $ chsh -s /bin/zsh

Restart your machine and your terminal should now be using zsh, confirm with `zsh --version`. Now, I'm personally using a framework for zsh called [oh-my-zsh](https://github.com/robbyrussell/oh-my-zsh). I used the `wget` installer mentioned in the readme and it worked a treat. I then went ahead and threw together my own theme which is also available on [GitHub](https://github.com/jasonlewis/jcl-zsh-theme).

![](/assets/images/ubuntu-gnome-terminal.png)

## Introducing A Development Environment

One of the first things I went and grabbed was a copy of [Sublime Text 2](http://www.sublimetext.com/2). If you'd prefer to use an installer then you might consider using [Sublime Text 3](http://www.sublimetext.com/3). Installing ST2 is pretty simple though.

<?prettify?>

    $ cd /opt && sudo wget http://c758482.r82.cf2.rackcdn.com/Sublime%20Text%202.0.2%20x64.tar.bz2
    $ tar xf Sublime\ Text\ 2.0.2\ x64.tar.bz2
    $ sudo ln -s Sublime\ Text\ 2/sublime_text /usr/local/bin/sublime_text

To create a shortcut to ST2 we need to create a `.desktop` file within `/usr/share/applications`.

<?prettify?>

    [Desktop Entry]
    Type=Application
    Version=1.0
    Name=Sublime Text 2
    Comment=Sublime Text 2
    Exec=sublime_text
    Icon=/opt/Sublime Text 2/Icon/128x128/sublime_text.png
    Terminal=false
    Categories=Programming;Languages;

Save the file as `sublime_text.desktop`. If you now view all your applications you should see the Sublime Text 2 icon. You can also pin the icon as a favorite, which I have done.

I'm actually starting to use [Vagrant](http://www.vagrantup.com/) a bit more these days, however I still like having a local environment where I can quickly throw something together. I'm using PHP 5.4, MySQl 5.5 and Apache 2. These are all really easy to install from our sexy looking terminal.

<?prettify?>

    $ sudo apt-get update
    $ sudo apt-get install php5 php5-mcrypt mysql-server apache2

Once you've installed everything and set up MySQL our development environment is pretty much right to go. To make creating a new site as simple as possible we can use a [bash script that creates our Virtual Hosts](https://gist.github.com/jasonlewis/6291983). I won't go over the installation instructions here as I've covered them on the Gist.

Now from our terminal we can get a new site up and running in no time at all! Heck, you could even modify that bash script to run a `composer create-project` command as well to get a Laravel installation up and running.

Now that we have the roots of our development environment we can install other things like Composer and PHPUnit.

<?prettify?>

    $ curl -sS https://getcomposer.org/installer | php
    $ mv composer.phar /usr/local/bin/composer

And now for PHPUnit.

<?prettify?>

    $ wget http://pear.phpunit.de/get/phpunit.phar
    $ chmod +x phpunit.phar
    $ mv phpunit.phar /usr/local/bin/phpunit

Our environment is pretty much set up now. You might like to install a few other things like Ruby, Node, Redis, etc. I'll leave those things up to your to figure out.

## Other Bits And Pieces

I've finally made the switch to [Irssi](http://irssi.org) for my IRC client. It was a toss up between that and [WeeChat](http://www.weechat.org), but I decided on the former. I have a few scripts installed with Irssi that make life a whole lot easier.

- [adv_windowlist.pl](http://anti.teamidiot.de/static/nei/*/Code/Irssi/adv_windowlist.pl)
- [hilightwin.pl](http://scripts.irssi.org/scripts/hilightwin.pl)
- [nickcolor.pl](http://scripts.irssi.org/scripts/nickcolor.pl)
- [notify.pl](https://code.google.com/p/irssi-libnotify/)
- [trackbar.pl](http://scripts.irssi.org/scripts/trackbar.pl)

I just want to quickly cover off on `hilightwin` and `notify`, as I initially had a bit of trouble with these two.

### hilightwin

Installing this script is easy, simply follow the usual instructions of downloading it to `~/.irssi/scripts` and symlinking it to the `autorun` directory. To actually make use of it you need to create the window yourself (this I was completely unaware of).

<?prettify?>

    /window new split
    /window name hilight
    /window size 8

This will create a split window, name it "hilight", and give it a size of 8. You'll then need to hit `Alt+0` to get back to your status window and allow yourself to continue switching between windows. But if you close Irssi and re-open it you'll lose that "hilight" window. You actually need to save the layout and then save the configuration to retain it.

<?prettify?>

    /layout save
    /save

You'll now have the window open when you close and reopen Irssi.

### notify

I had a lot of trouble with this script at first. Everything was running but it wasn't sending me any notifications and for the life of me I couldn't figure it out. Then I came across [this installer for Ubuntu](https://gist.github.com/theirishpenguin/3872398). I had actually installed the `notify-listener.py` script in `/usr/local/bin` and not in `~/bin`. This isn't a problem, but because I was running `sudo notify-listener.py` it was being run as the `root` user and not as me. Apparently this causes it to break. Simply killing the running process and running `notify-listener.py` without `sudo` was enough to get things back on track.

I then had a problem where I'd get notified a dozen times about the same thing. Rebooting my machine fixed this issue.

## Conclusion

So, that's it! That pretty much sums up my switch from Windows to Linux. It wasn't painless, but hopefully by documenting this process it'll save someone else some time and it will most likely save me time in the future. I'm interested to hear what everyone else is running. Let me know in the comments. I'd also love to hear if there's something I should've done differently.
