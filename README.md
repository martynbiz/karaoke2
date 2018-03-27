# My Homepage #

This is a Slim 3 application. In addition, it uses martynbiz/slim-module library
to manage modules.

## Installation ##

```
$ composer create-project martynbiz/martynbiz
$ cd martynbiz
$ php -S localhost:8080 -t public/
```

Note: some features (e.g. file uploads) will not run in PHP's built in server
and will require a virtual host.




TODO

merge modules/app into modules/martynbiz-core (rename martynbiz-app?)
write 

build a japantravel port with slim-module-blog
* build homepage
* static files
* prefecture, categories, subcategories, etc
* news, photostory, video,





Slim-module debate:

separate folders (e.g. /templates, /src)
* more consistent with slim skeleton
* easier to find templates
* only one template folder required (less searching)

modules/name
* app module currently has three dirs (in /src/autoload/; /App; templates/)
* simpler settings naming: modules/martynbiz-blog/settings.php
* everything is actually modular, root has only three repo dirs (assets, public, src)
* (use folder IDs? e.g. martynbiz-blog::articles/index)
* if we extend Module, it's in it's correct location
* language can go back in module dir
* copy/remove modules is much simpler


modules/martynbiz-blog/
  templates
  settings.php




delete old repos
documentation
  core - pagination Traits,
  auth - $c['auth.current_user']



auth




qa module
slim-module-contactform (ContactController)


Why is this appearing when running the app? Try removing phpunit and trace it
Fatal error: Class 'PHPUnit_Framework_TestCase' not found in /var/www/martynbiz/vendor/martynbiz/php-mongo/tests/MongoIteratorTest.php on line 26




* cache: homepage, tags, photos
* photo manage page (delete photos), tag photos for carousel (featured)
* tags: admin: delete, paginate, create, portal: /tags, tag page, public/private (private for e.g. featured)
* cache busting (foil?), remove coffeescript,
* home - contact form, portfolio
* docs: installation of modules (module/Name/README): add to config, add to composer.json, add to phpunit.xml
* delete confirmation on articles: "are you sure...?"
* finishing touches: tidy up registration form, login form;
* sort
* only admin can edit tags

self hosted
* hosting my website from powburn (lamp, ssl, dns, mail?)
* sync with live db

experiment
* only pass in dependencies rather than container
* inline translation editing (<span data-translation="hello_world">Hello world</span>)
* ajax load with template inheritance (partials/articles_table)
* composer custom installer
* elastic search - configurable (mongo, elastic search, )
* ckeditor plugin: select from uploaded media, dropzone

v2
* auth : remember me
* simple search
* admin: homepage - side menu?
* testing: library tests, module tests, test 40x when not logged in

* convert panels like homebox
* comments:
* translations: japanese site
* article preview
* Auth_Facebook module facebook login
