# ship-lib change history

## 1.0.7: 2018-01-05
- Added new JS dependencies: slideout (for drawer-style menuing), jQuery 1.14.2, and ES Lint to lint builds
- Upgraded Bootstrap dependency to 4.0.0-beta.3
- Added a default 404 template to the blade/complete baseline
- Added sections to the Blade layout for overriding the center content ("wrapper"), and right- and left-hand sidebars ("left-sidebar" and "right-sidebar")
- Fixed some of the annotations in the Underscore templates
- Added Blade directives to alias some common WordPress template tags, like @body_class() 
- Added an @addBodyClass($names) for simply appending body classes from within any template

## 1.0.6: 2018-01-02
- Now loading default theme version from withfatpanda/ship composer.json version

## 1.0.5: 2018-01-02
- Fixed a bug in Setup that was hiding default config values