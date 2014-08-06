Quassel-LogSearch
=================

My fork of m4yer's "quassel backlog search" script.

**Note:** My rewritten version is on the `rewrite` branch!

Original source at http://gitorious.org/quassel-backlog-search

Installation
------------

*At some point I'll create an archive version of this (`.tar.gz`, `.zip`, etc.), but for now you have to clone the repository using git and then use `composer` & `bower` to install the dependencies.*

1. Ensure that you have the required dependencies installed:
   - **git**
   - **node** & **npm**: required for installing **composer** & **bower**
   - **composer**: `npm install -g composer`
   - **bower**: `npm install -g bower`
2. `cd` to an *empty directory* where you want to install the app
3. `git clone -b rewrite https://github.com/aziraphale/Quassel-LogSearch.git .`
4. `composer install`
5. `cp config{.sample,}.ini`
6. `vim config.ini` and edit it appropriately

Compatibility
-------------
I'm currently only developing & testing this in **Chrome ~37**, with **Quassel 0.10** ([**with the unofficial MySQL DB driver port**](https://github.com/kode54/quassel/tree/branch_mysql_support)) alongside **PHP 5.4.29** and **MySQL 5.5.32** on the back-end.

I do plan to support other browsers (recent versions of Firefox, at least IE10+ but maybe IE8 or IE9 as well) and, of course, the PostgreSQL and SQLite backends (though I can't begin to imagine how painfully slow SQLite would be...).

I make no promises regarding support for PHP versions prior to 5.4 or MySQL versions prior to 5.5. Both have been the recommended production versions for years, and PHP 5.3 is already out of support for even security patches(!), so if you're still running 5.3, **stop it!**
