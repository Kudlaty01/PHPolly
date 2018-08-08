Simple poll application
===================

The fron- and backend app for poll creation and voting

INSTALLATION
------------
* install all required JS libraries with [Yarn][1]
* in console, in main app directory run `yarn run setup`
* rename `private.php.dist` to `private.php`, configure it with data you prefer
* by default there are only 3 users
* run the app with `php -S localhost:<port>` where `<port>` is the port of your choice (I set it to 8000)
* go to `<domain>/install` and push the button
* to hard reset the app, just remove file db/database.sqlite


FEATURES
--------
* MVC _pseud0-framework_ written from scratch
* Session based authentication
* written in PHP 7.0
* Dependency injection
* KnockoutJS library


TODO
----
* ~~db models implementation~~
* add ajax request type validation
* implement MANY_TO_MANY relationship
* separate modules of front and backend
* grid pagination
* some methods are still being used on instances where they shouldn't
* stop giving model class to repository every time
* prevent redundant model declaration within poll management scripts
* add more consistent validation to forms and dates especially
* clean the mess db queries in the PollService
* perform more security tests


[1]: https://yarnpkg.com
