# ship-lib change history

## 1.0.12: 2018-01-24
- Fix: filter out any null/empty objects before processing `Bucket::filter`

## 1.0.11: 2018-01-23
- Fix composer license token

## 1.0.10: 2018-01-23
- Addition of global functions in src/functions.php
- Use global `now()` to get a time-zone adjusted instance of [`Carbon\Carbon`](http://carbon.nesbot.com/docs/)
- Added "Bucketing": an API for building complex blog homepages without repetition of posts across overlapping types of lists (recent, taxonomical, staff-selected, etc.) (documentation to follow)

## 1.0.9: 2018-01-11
- Added `the_content()` directive

## 1.0.8: 2018-01-11
- Added directives for `@get_option()`, `@the_post()`, `@wp_reset_postdata()`, and a special version of `@setup_postdata()`, which takes as its only argument any arbitrary variable name and assigns that variable's value to the global `$post` object

## 1.0.7: 2018-01-05
- Added new JS dependencies: slideout (for drawer-style menuing), jQuery 1.14.2, and ES Lint to lint builds
- Upgraded Bootstrap dependency to 4.0.0-beta.3
- Added a default 404 template to the blade/complete baseline
- Added sections to the Blade layout for overriding the center content ("wrapper"), and right- and left-hand sidebars ("left-sidebar" and "right-sidebar")
- Fixed some of the annotations in the Underscore templates
- Added Blade directives to alias some common WordPress template tags, like `@body_class()`
- Added an `@addBodyClass($names)` directive for simply appending body classes from within any template

## 1.0.6: 2018-01-02
- Now loading default theme version from withfatpanda/ship composer.json version

## 1.0.5: 2018-01-02
- Fixed a bug in Setup that was hiding default config values