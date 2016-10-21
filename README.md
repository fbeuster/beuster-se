# Lixter
[![BSD-2-Clause License](https://img.shields.io/badge/License-BSD--2-blue.svg)](https://github.com/fbeuster/beuster-se/blob/master/LICENSE.md)

Since 2010 I developed constantly on my blog software, adding
features, restructuring and so on. I moved it to git as the next
step.

This CMS is still in the process of being transfared into object orientation. However it is completely working out of the box. Check the Installation section for more information.

Code documentation can be found under [doc.beusterse.de](http://doc.beusterse.de)

## Requirements
- PHP 5.5.0
- MySQL 5.6.33

### Build requirements
- [SASS](http://sass-lang.com/)
- [JS Build](https://github.com/fbeuster/js-build)

## Build instructions
After cloning the repository or making a pull, you need to build the `.scss` and `.js` files in some folders. Starting from your base directory, you should run the following commands:
```
$ cd theme/beuster-se-2013/styles
$ sass application.scss application.css
$ cd ../scripts
$ path/to/js-build application.js beusterse.js
```
**Note:** Future plans for this part are to provide a small build script as well as a download version of the CMS.

## Installation

Considering your website is `http://test.com`, navigate to
```
http://test.com/setup/index.php
```
to start the setup routine. Follow the simple instructions to setup the configuration, including database settings, administrator information, themes and more.

Also make sure, that the cache and cache/locale directory are writable for the web page.

### Notice
While there is a setup routine available, it is in its first revision, so be aware of potential bugs (there are a few todos left...). Also, the setup routine is a basic HTML form as of now, no styling or interactive scripts yet.
