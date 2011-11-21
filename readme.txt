=== Plugin Name ===
Contributors: mcmaxx
Donate link: http://thoora.com
Tags: curate, news, content, blog, curation, widget, RSS feed, RSS parser, content aggregator, twitter, image, vertical
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: trunk

This widget provides content from Thoora.com such as news, blogs, tweets and images based on topic verticals that the user curated. 

== Description ==

This widget provides content from Thoora.com such as news, blogs, tweets and images based on topic verticals that the user curated. 

Curate and publish beautiful, authoritative, topical pages on the subjects you care most about. Leverage Thoora's powerful aggregation 
engine to discover and deliver a relevant stream of high quality content; then use powerful curation tools to refine pages to your liking. 
Share them with your friends.

== Installation ==

This section describes how to install the widget and get it working.

1. Unpack and install the Thoora widget folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag and drop the Available Widgets from the Appearance -> Widgets menu in Wordpress to any sidebar available in your template
1. In the Widget's options you MUST include the Thoora URL of the topic you want shown. An example is 'http://thoora.com/mcmaxx/pro-minecraft'.

== Frequently Asked Questions ==

= Why is nothing appearing on the widget? =

1. Make sure your topic URL is correct from Thoora.
2. Make sure your topic is set to PUBLIC
3. Make sure there is data to provide. For example, make sure there are favorites if you decide to show the favorites section

= I'm getting an error when installing the widget =
There were problems with older versions of PHP < 5.3 but update to the newest version and the problem should be resolved. 

== Screenshots ==

1. The widget in action pulling data from http://thoora.com/mcmaxx/pro-minecraft news section
2. The control panel. We recommend allowing links to the Thoora site for the best user experience

== Changelog ==
= 1.3 =
* Some CSS changes
* Moved all sanitization to the API rather than this

= 1.2 =
* Removed a function that was causing problems

= 1.1 =
Fixed title and date in news boxes

= 1.0 =
* First release, fingers crossed

== Upgrade Notice ==
= 1.3 =
!Important! Upgrade for functional and visual improvements

= 1.2 =
!Important! Upgrade if you are receiving an error 

= 1.1 =
Fixes a few visual bugs

= 1.0 =
This is the base version