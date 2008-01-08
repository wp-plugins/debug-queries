=== Debug Queries ===
Contributors: Bueltge
Donate link: http://bueltge.de/wunschliste/
Tags: queries, database, performance, analyse
Requires at least: 1.5

List querie-actions in html-comment only for admins

== Description ==
List querie-actions in html-comment only for admins.

Please visit [the official website](http://bueltge.de/wordpress-performance-analysieren-plugin/558/ "Debug Queries") for further details and the latest information on this plugin.

== Installation ==
1. Unpack the download-package
1. Upload all files to the `/wp-content/plugins/` directory, without folder
1. Editing file `wp-config.php`: `define('SAVEQUERIES', true); // Debug Queries`
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's it! Now you are all set to go on to thr frontend of your weblog and read the code. You find the querie-analysis on end of syntax. 

See on [the official website](http://bueltge.de/wordpress-performance-analysieren-plugin/558/ "Debug Queries").

== Other Notes ==
This is a example for analysis.

`<!--`
`0.000632047653198        SELECT option_value FROM wp_options WHERE option_name = 'siteurl'`
``
`0.00303101539612         SELECT option_name, option_value FROM wp_options WHERE autoload = 'yes'`
``
`0.00327301025391         SELECT ID FROM wp_users WHERE user_login = 'admin'`
``
`0.00126004219055         SELECT * FROM wp_users WHERE ID = 1 LIMIT 1`
``
`0.000476121902466        SELECT meta_key, meta_value FROM wp_usermeta WHERE user_id = 1`
``
`0.000423192977905        SELECT COUNT(comment_ID) FROM wp_comments WHERE comment_approved = 'spam'`
``
`0.00264286994934         SELECT * FROM wp_posts WHERE ID = 1 LIMIT 1`
``
`. . .`
``
`Total query time: 0.0315816402435``

= Licence =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](http://bueltge.de/wunschliste/ "Wishliste and Donate") for the time I’ve spent writing and supporting this plugin. And I really don’t want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the sitemap.pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) (Windows).


== Frequently Asked Questions ==

= Where can I get more information? =
Please visit [the official website](http://bueltge.de/wordpress-performance-analysieren-plugin/558/ "Debug Queries") for the latest information on this plugin.

= I love this plugin! How can I show the developer how much I appreciate his work? =
Please visit [the official website](http://bueltge.de/wordpress-performance-analysieren-plugin/558/ "Debug Queries") and let him know your care or see the [wishlist](http://bueltge.de/wunschliste/ "Wishlist") of the author.
