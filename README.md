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
