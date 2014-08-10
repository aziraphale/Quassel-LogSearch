Quassel-LogSearch
=================

My fork of m4yer's "quassel backlog search" script.

**Note:** My rewritten version is on the `rewrite` branch!

Original source at http://gitorious.org/quassel-backlog-search

Installation (Linux/Mac/etc.)
-----------------------------

*At some point I'll create an archive version of this (`.tar.gz`, `.zip`, etc.), but for now you have to clone the repository using git and then use `composer` & `bower` to install the dependencies.*

1. Ensure that you have the required dependencies installed:
   - **git**
   - **nodejs** & **npm**: required for installing **bower**
      - **NOTE:** There's an oddity with Ubuntu/Debian whereby the `node` package is NOT node-js. Instead you should install `nodejs-legacy` and, assuming you have nothing else using the `node` package, uninstall it (or the `node` package's binary may take precedence over the `nodejs-legacy` package's binary) [[ref](https://github.com/joyent/node/issues/3911)].
   - [**composer**](https://getcomposer.org/doc/00-intro.md#installation-nix): `curl -sS https://getcomposer.org/installer | php` then `sudo mv composer.phar /usr/local/bin/composer`
   - **bower**: `npm install -g bower`
2. `cd` to an *empty/new directory*, somewhere within your web root, where you want to install the app (or another alternative way of pointing your web server - Apache, etc. - at the app)
3. `git clone -b rewrite https://github.com/aziraphale/Quassel-LogSearch.git .`
4. `composer install`
5. If the `composer install` command resulted in a `bower` error, run `bower install` manually.
6. `cp config{.sample,}.ini`
7. `vim config.ini` and edit it appropriately
8. `cp .htaccess{.dist,}`
9. `vim .htaccess` and edit it appropriately
10. Common issues:
   - If you're running this from a subdirectory of your domain (e.g. `example.com/quassel`, instead of `example.com/` or `quassel.example.com/`) you need to uncomment and edit the `RewriteBase` line in `.htaccess`.
   - You need to have `.htaccess` files enabled in your Apache config, which is done via the `AllowOverride all` directive, usually in your vhost configuration.

Compatibility
-------------
I'm currently only developing & testing this in **Chrome ~37**, with **Quassel 0.10** ([**with the unofficial MySQL DB driver port**](https://github.com/kode54/quassel/tree/branch_mysql_support)) alongside **PHP 5.4.29** and **MySQL 5.5.32** on the back-end.

I do plan to support other browsers (recent versions of Firefox, at least IE10+ but maybe IE8 or IE9 as well) and, of course, the PostgreSQL and SQLite backends (though I can't begin to imagine how painfully slow SQLite would be...).

I make no promises regarding support for PHP versions prior to 5.4 or MySQL versions prior to 5.5. Both have been the recommended production versions for years, and PHP 5.3 is already out of support for even security patches(!), so if you're still running 5.3, **stop it!**
