=== Plugin Name ===
Contributors: Marius@Thoora
Donate link: http://thoora.com
Tags: curate, news, content, blog, curation, widget, RSS feed, RSS parser, content aggregator, twitter, image, vertical
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: trunk

Enhance your blog with a fresh, relevant stream of content, including news, blogs, images, and tweets, from any topic created on Thoora.

== Description ==

The Thoora widget allows you to quickly and easily enhance your WordPress blog with a fresh, relevant stream of content, including
news, blogs, tweets and images. This widget helps to keep your site fresh and your users engaged.

Content is pulled in from any topic created on Thoora. It can be your own topic (register for a free account on http://thoora.com to start
creating topics) or a public topic that another user created. Simply enter the topic URL you want to feature. By default, the Thoora topic entered is:

http://thoora.com/Thoora/social-media

You can decide what type of content to feature in your widget – either news, RSS feeds, tweets, images, or your own curated Favorites. See the
Thoora blog for an example of a standard widget with news content: http://blog.thoora.com

What is Thoora? Thoora is a content discovery engine that gives you a highly personalized feed of content on any topic you care about –
anything from Android phones to social media trends to zombie movies. Simply enter keywords to create your topic and the Thoora engine
immediately scours thousands of sources on the web to serve up what’s most relevant. You can save results to your Favorites page, or delete
articles you don’t like. The Thoora engine learns from your actions to continually improve the content it delivers.

You can sign up for a free Thoora account at http://thoora.com

== Installation ==

This section describes how to install the widget **manually** and get it working.

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

= How can I do advanced customization of my widget? =

Things like colors, heights and widths can all be fine tuned with a little bit of PHP and CSS knowledge. Simply edit the enclosed files.

= I don’t like some of the content appearing in my widget.  How do I control what content appears? =

You can control the content by featuring your Favorites page.  Your Favorites page is your own curated selection of content, which can include news, blog posts, 
images and tweets.  Select ‘Favorites’ in the ‘Type’ drop-down menu.

Alternately removing items from your Thoora topic feed will ensure they don't show up in your widget.

= Why is only versions > 3.2.* of Wordpress supported? =
There is very little fancy Wordpress functionality used. Chances are this widget works on older versions. If you get it working on an older version let us know
and we'll update this page!

= I have bugs to report and may have more questions =

Post them here
http://getsatisfaction.com/thoora

== Screenshots ==

1. The widget in action pulling data from http://thoora.com/mcmaxx/pro-minecraft news section
2. The control panel. We recommend allowing links to the Thoora site for the best user experience

== Changelog ==
= 1.9 =
* CSS Improvement
* Better logging

= 1.8 =
* CSS Change
* Added default value for topic

= 1.7 =
* Max Results bug fix
* CSS Change

= 1.6 =
* Updated some readme text, not a big deal

= 1.5 =
* Added prefix on ALL CSS classes
* Fixed weird <?> appearing on page

= 1.4 =
* Added tracking for API calls to see where they're coming from
* Added some error catching

= 1.3 =
* Some CSS changes
* Moved all sanitization to the API rather than this

= 1.2 =
* Removed a function that was causing problems

= 1.1 =
* Fixed title and date in news boxes

= 1.0 =
* First release, fingers crossed

== Upgrade Notice ==
= 1.9 =
Minor. Visual improvement

= 1.8 =
!Important! Bug fix and visual improvements

= 1.7 =
!Important! Bug fix and visual improvements

= 1.6 =
Minor update in readme.txt. Update if you're feeling brave.

= 1.5 =
Update if you're having problems with the visual layout

= 1.4 =
Minor update that should better handle errors

= 1.3 =
!Important! Upgrade for functional and visual improvements

= 1.2 =
!Important! Upgrade if you are receiving an error 

= 1.1 =
Fixes a few visual bugs

= 1.0 =
This is the base version

== Demo ==
[Thoora Blog](http://blog.thoora.com "Thoora Blog")
