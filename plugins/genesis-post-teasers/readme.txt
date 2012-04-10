=== Genesis Post Teasers ===
Contributors: cochran
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TLKVZFHV64ZS4&lc=US&item_name=Christopher%20Cochran&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: teasers, hooks, genesis, genesiswp, studiopress, genesis grid loop
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

This plugin uses the genesis grid loop function to display posts teasers either on your Homepage, Archives, or Blog Template and gives added options to control them.

== Description ==

This plugin uses the genesis grid loop function to display posts teasers either on your Homepage, Archives, or Blog Template and gives added options to control them. Easily have 1,2,3... as many featured posts at top and teasers below. Enable Thumbnails, Disable the byline or post meta and limit the text for the teasers. The Genesis Theme Framework by studiopress is required.

== Installation ==

1. Upload the entire `genesis-post-teasers` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the `Genesis > Theme Settings` menu
4. Configure Options for 'Homepage Teasers'

== Frequently Asked Questions ==

= What happens when I select Use Plugin CSS? =

This plug in adds additional markup to style the post teasers. Selecting 'Child Theme CSS' will not load any the CSS provided in the plugin and requires you to add your own to your child theme's style.css.  By default The post teasers will be displayed in pairs side by side.

= The plug in seems to have done nothing for my site =

This Plugin will not work with widgetized homepages. If your homepage is widgetized try selecting "Archive" or "Blog Template". 

== Screenshots ==

1. Plug-in options in Genesis theme options


== Changelog ==
= 1.0.3.2 =
* Fixed: Any settings get reset on save.
* Change: Moved the settings back to main genesis theme settings page for now.

= 1.0.3.1 =
* Fixed: Default styles will not show if edit styles is not checked.

= 1.0.3 =
* Put Settings on its own page as a sub menu item of Genesis. ( "Post Teaser Settings" )
* Fixed: Issue with js on admin side that the Edit style area may hide it self on when changing custom widths or heights.
* Fixed: If "Edit teaser styles?" any value for height or width were still loaded.
* Fixed: If no teasers were displayed due to limit of posts div structure would break. ( Props [@GK](http://www.kromhouts.net/) ).

= 1.0.2 =
* Added support for featured posts to have featured images.
* UI updates.
* Fixed: Archive loops would break and show all posts.
* Fixed: Blog template custom category query args would be ignored.

= 1.0.1 =
* Version bump for auto upgrades. See above for added features and fixes. 

= 1.0 =
* Added ability to display teaser boxes on archives.
* Added default settings.
* Cleaned up settings widget.
* Fixed: Meta box didn't display in Genesis 1.6

= 1.0b =
* Now takes advantage of genesis_grid_loop()
* Allows selection of where teasers should be display. ( Home or the Blog Template )
* Allows customization of 'continue reading' text.
* Added option to display teasers full width.
* Small bug fixes.

= 0.9.0.1 =
* Bug fix: Fixes an issue in which plug-in will break site using a mobile theme like WPtouch.

= 0.9 =
* Initial Beta Release


