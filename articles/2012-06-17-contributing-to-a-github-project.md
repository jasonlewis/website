title: "How To: Contributing to a GitHub Project"
author: "Jason Lewis"
---
We've all been there. You're sitting down having an amazing coding session without a care in the world when suddenly an ugly bug rears its head from the depths of Laravel. First thing that comes to mind: god dammit! After a while (sometimes it's longer then a while) you find the source of the bug and luckily for you it's a relatively simple fix. So you patch it up on off you go on your merry way.

"Wait up there, you selfish person you!" says your conscience. "You're just going to leave all those other wonderful, brilliant, and amazing Laravel coders to fend for themselves!? They need you mon!" Wondering why your conscience seems to be Jamaican you decide to [submit an issue](https://github.com/laravel/laravel/issues) on GitHub. But didn't you just fix it yourself? Why not contribute your fix to the community and save others the time. That sounds like a much better idea, ninety nine!

---more---

So you decided to contribute, that's great! That was a rather long winded introduction actually, sorry about that. In this guide I want to cover an important aspect of any open source project: **contributing**. Contributing to Laravel is actually quite easy, but a lot of people don't get it (or don't want to, that was me).

This tutorial assumes you have some knowledge of [Git](http://git-scm.com/). You'll also need to set yourself up with a [GitHub](https://github.com/signup/free) account if you don't already have one.

*This was written about Laravel but can be used for any project, just replace Laravel with the project you want to contribute to!*

### Forking Laravel

To start off you'll need your own fork of Laravel. Login to GitHub and find the [Laravel repository](https://github.com/laravel/laravel). To fork the project click the "Fork" button near the top right of the page.

![Location of the GitHub fork button.](/assets/images/article/contributing-to-a-github-project/fork.png "Location of the GitHub fork button.")

Great you should now be taken to your fork of the project. We need to get a clone of Laravel and add our fork as a remote so we can begin submitting bug fixes and features. Confused? Read on!

### Cloning Laravel

This should be fairly straightforward if you've used Git before. Open up your terminal or client (I'll be using the command line for the examples) and `cd` to where you want to clone Laravel.

<?prettify?>

	$ cd ~/projects
	$  git clone https://github.com/laravel/laravel.git laravel-fork

Now you'll notice I'm cloning Laravel here, you'll see why shortly.

Some things will happen and numbers will be crunched. Afterwards you should now have Laravel cloned into your new directory.

Now it's time to add your fork. The reason I do it like this is that I can easily make sure my repository is up to date with the latest Laravel code yet still push code to my fork. I add the fork by adding a new remote, aptly named `fork`.

Remember to replace `username` with your own GitHub username.

<?prettify?>

	$ git remote add fork git@github.com:username/laravel.git

You can check that the remote was added by simply typing `git remote -v` which should show two remotes, **origin** and **fork**

### Branching for Pulls

This is where the magic happens. There are a couple of things you need to do before submitting a pull.

Firstly you need to make sure you're on the **develop** branch. This is extremely important. Always, always make sure you're on the **develop** branch! Why you ask? Because pulls that have been branched off the master branch generally won't be pulled in. Commits piggy-back there way in and things just look awful. The master branch is considered the stable release of Laravel. Your patch (while it may work and be perfectly stable) won't be merged into the master branch until another stable release of Laravel is tagged. So always make sure you're on the develop branch before continuing, you can switch to the develop branch like so.

<?prettify?>

	$ git checkout develop

If this is the first time you've checked out the develop branch you should get a message.

<?prettify?>

	Branch develop set up to track remote branch develop from origin.
	Switched to a new branch 'develop'

Secondly you need to make sure you are up to date with the origin remote (that's the Laravel repository, remember). This is easy, all you need to do is run a `git pull`.

<?prettify?>

	$ git pull origin develop

Most of the time you should get an already up to date message, sometimes it will pull in the most recent changes that have been merged with the branch.

You should now be ready to branch off and begin fixing bugs and adding features. Let's say we found a bug in Eloquent. Here is how we could branch off the develop branch and begin fixing the bug.

<?prettify?>

	$ git branch bug/eloquent
	$ git checkout bug/eloquent
	Switched to branch 'bug/eloquent'

A shorter way of doing that is using the `-b` switch on the `checkout` command. This creates the branch and checks it out straight away.

<?prettify?>

	$ git checkout -b bug/eloquent
	Switched to a new branch 'bug/eloquent'

You can be somewhat descriptive with your names, append numbers or whatever makes it easier for you. Say you want to add a feature, you might do it like so.

<?prettify?>

	$ git checkout -b feature/your-cool-feature
	Switched to a new branch 'feature/your-cool-feature'

Now that you're on the correct branch you can make any changes you need to fix the bug or add the feature.

### Committing Changes

Before you go committing your snazzy new changes there's something you should be aware of. All commits must use the `-s` switch which will automatically sign off on your commit. This just tells the Laravel team that you agree to your code being used in the Laravel core.

Say we changed the `laravel/html.php` file. We can sign off on it and add a commit message.

<?prettify?>

	$ git add laravel/html.php
	$ git commit -s -m "Added some funky cool stuff to the HTML class."

You may need to setup your [GitHub username and password](https://help.github.com/articles/set-up-git) in Git for signing off to work correctly.

### Pushing to your Fork

Once so you've finished whatever it is you want to submit and committed your code you'll now want to push it to your fork so you can submit a pull request.

Let's use the Eloquent bug example from before. Here is how you would push to your fork.

<?prettify?>

	$ git push fork bug/eloquent

That's so darn easy!

### Submit the Pull Request

The final step to contributing your code is submitting a pull request. From your forked repository on GitHub you need to click the "Pull Request" button.

![Press the Pull Request button.]/assets/images/article/contributing-to-a-github-project/pull-1.png "Press the Pull Request button.")

You'll now be able to select what you want to merge and to where. Remember that you need to select the **develop** branch on the base repo (Laravel), and the branch you made on the head repo (your Laravel fork).

![Select the develop branch and the branch you want to merge from.](/assets/images/article/contributing-to-a-github-project/pull-2.png "Select the develop branch and the branch you want to merge from.")

You'll then be shown the files changed and the commits which you can check to ensure everything is as it should be. Make sure you don't get any piggy backing commits! Only the commits you committed yourself should be there. You can also title your pull. Ensure your title is informative and give a good description, including use cases where applicable. **Remember!** Providing as many details as possible will ensure less questions are asked and will help team members get your changes merged as quickly as possible.

### Now what?

Perhaps now you have another bug you'd like to fix or feature you'd like to add. Remember to always base your branches off the develop branch. So head on back over there and pull down any changes.

<?prettify?>

	$ git checkout develop
	$ git pull origin develop

You're now good to go for your next branch. Rinse and repeat the steps above and you'll be on your way.

*Don't use the same branch for multiple fixes or features. Have a separate branch for each!*

### Conclusion

That's all there is to it. Hopefully this guide has been useful to those who were once unsure or afraid to send pull requests. Don't be afraid! Some may have submitted pulls before but they never got merged into the core. Don't worry about it, the core team is growing and issues/pulls are being taken very seriously.

There are a couple things you can do to increase the chances of your pull being merged.

- Follow the same coding style that Laravel already uses. Make sure you study it thoroughly and ensure that your code conforms. This includes bracket placements, spacing of code, etc.
- Providing good examples and use cases in the description will make the entire process a lot easier.
- If you're submitting a feature, post links to any existing discussions on the feature for reference.

Best of luck!