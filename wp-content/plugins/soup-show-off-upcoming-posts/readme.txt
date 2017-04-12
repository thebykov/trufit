=== SOUP - Show off Upcoming Posts ===
Contributors: thewanderingbrit
Donate link: https://www.theukedge.com/donate/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=donate
Tags: upcoming, posts, future, scheduled, widget, sidebar, list, number, title, interest, readers, newsletter, rss feed, feedburner, drafts, soup
Requires at least: 3.8
Tested up to: 4.5.2
Stable tag: 2.3
License: GPLv2

Displays your upcoming posts in a sidebar widget to tease your readers

== Description ==

**Like this plugin?** Consider [leaving a quick review](https://wordpress.org/support/view/plugin-reviews/soup-show-off-upcoming-posts "Review SOUP") or writing about how you've used it on your site - [send me a link](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact Dave") to that write up if you do.

This plugin is maintained on [GitHub](https://github.com/theukedge/soup-show-off-upcoming-posts), so feel free to use the repository for reporting issues, submitting feature requests and submitting pull requests.

SOUP creates a widget in your sidebar which allows you to display your upcoming posts (scheduled, drafts, or both) to your readers. The idea is to entice your readers to come back and read the article once it gets published, or better yet, subscribe to your RSS feed and/or newsletter.

Here are the configurable options for the widget:

* Title of sidebar widget
* Number of upcoming posts to display (always in ascending order - newest first)
* Choose whether to show date of upcoming post
* Display order of the posts (next post first or random order)
* Message to display for when there are no scheduled posts or drafts
* Show/hide newsletter link
* Include link to sign up to newsletter

I've got plans to continue developing and updating this plugin. If you have any suggestions on revisions that you'd like to see made, please [get in touch](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact Dave") or [find me on Twitter](http://www.twitter.com/daclements "Dave on Twitter").

I also run [Do It With WordPress](https://www.doitwithwp.com/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=my-other-sites "WordPress Tutorials"), which has an array of tutorials for managing, modifying and maintaining your WordPress sites, as well as [The WP Butler](https://www.thewpbutler.com/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=wordpress-services "WordPress Maintenance Services"), a service for keeping your site maintained, backed up, updated and secure.

== Installation ==

1. Upload `soup-show-off-upcoming-posts` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance > Widgets to put the widget in your selected sidebar.
1. Set your settings, hit Save and go to your site to see it in action.

== Frequently Asked Questions ==

= How do I modify WP_Query to get more specific results =

There is a filter (`soup_query`) for the [WP_Query](https://codex.wordpress.org/Class_Reference/WP_Query) args, so you can add and modify args as needed to get the result you need.

For example, to limit your results to posts in category 3, you might add something like the function below to your [functionality plugin](https://github.com/theukedge/functionality-plugin):

`function limit_soup( $args ) {
    $args['cat'] = 3;
    return $args;
}

add_filter( 'soup_query', 'limit_soup' );`

= Are there more features planned in future? =

Absolutely. I'll be providing more options and upgrades in the near future. You can stay up to date by following me on [Twitter](http://www.twitter.com/daclements "Dave on Twitter"). If you have an idea for a new feature, you can create a new feature request on [GitHub](https://github.com/theukedge/soup-show-off-upcoming-posts/issues). If you don't have a GitHub account, then [tell me](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact The UK Edge") what this widget should do that it doesn't currently.

= I don't have a newsletter. Is this functionality lost on me? =

Many WordPress users send their RSS feed through FeedBurner, which has the option to deliver your latest posts by email to anyone who subscribes. Just log in to FeedBurner, go to the Publicize tab and select Email Subscriptions where you'll be able to grab the link.

= I'm having issues. What do I do? =

The best thing to do is to submit an issue on [GitHub](https://github.com/theukedge/soup-show-off-upcoming-posts/issues). If you don't have a GitHub account, then [get in touch with me](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact The UK Edge") and I'll see about getting it fixed.

== Screenshots ==

1. An example of the widget in use.
2. The widget control panel.

== Changelog ==

= 2.3 =

Release date: May 23, 2016

* **Breaking changes for sites using < v2.2.1** See changelog for changes in previous versions.
* Improve coding standards
* Properly implemented i18n
* Removed RSS icon image and replaced with dashicon

= 2.2.1 =

Release date: May 20, 2016

* **Breaking change**: args for category, post status and post type removed from WP_Query. If you wish to continue using these, you need to use the `soup_query` filter. See the [FAQ tab](https://wordpress.org/plugins/soup-show-off-upcoming-posts/faq/) for details on how to use this.
* Added support for wordpress.org language packs

= 2.1 =

Release date: January 26, 2016

* Removed settings for category, post status and post types from the widget control panel. **To modify the category, post status or post types that SOUP displays, you now need to use the filter provided.** See the [FAQ tab](https://wordpress.org/plugins/soup-show-off-upcoming-posts/faq/) for details on how to use this, or [file a support ticket](https://wordpress.org/support/plugin/soup-show-off-upcoming-posts).

= 2.0 =

Release date: October 1, 2015

* Refactored query in widget to use WP_Query class. **This will allow for the deprecation of certain settings in the widget in the next release (2.1)**. The intent is to use the soup_query filter instead (see FAQs for example). Settings will be removed in 2.1 and the args will be removed from the query in 2.2.1.

== Upgrade Notice ==

= 2.3 =
* **Breaking changes for sites using < v2.2.1** See changelog for changes in previous versions.
* Coding standards and translation improvements.

= 2.2.1 =
* **Breaking changes included**. See changelog for more details.
* Removed args for category, post types and post status from WP_Query.
* Prepared for use of wordpress.org language packs.

= 2.1 =
* Removed settings for category, post types and post status from widget settings in favour of filter.
* **Breaking changes included and more forthcoming. See changelog**

= 2.0 =
* Refactored code to use WP_Query
* **Breaking change forthcoming. See changelog**
