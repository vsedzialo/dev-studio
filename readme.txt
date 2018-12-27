=== Dev Studio ===
Contributors: solidbunch
Tags: dev studio, debug, debug-bar, debugging, development, developer, queries, query monitor, utilities
Requires at least: 4.0
Tested up to: 5.0
Stable tag: 1.0.0
License: GPLv2 or later
Requires PHP: 7.0

Development environment for Wordpress developers

== Description ==

Dev Studio is a development enviroment for Wordpress developers.

It contains a lot of features that help to examine and analyze code.

Dev Studio based on checkpoints. You can add checkpoint to any hook(s) and analyze application condition at any point of the script execution.

It also includes Status Bar that contains any useful information such as: DB queries count, Execution time, Queries execution time, Conditionals etc.

= Advantages =

 * Fully built on AJAX technology
 * Doesn't add any html code to result page
 * Friendly interface
 * Status Bar
 * Flexible settings

= Modules =
Now, it contains four modules: PHP, Wordpress, WooCommerce, MySQL.

= PHP =

 * Variables (SERVER, GET, POST, COOKIE, SESSION, FILES, ENV, GLOBAL)
 * Constants
 * Files (Included Files, Components)
 * PHPInfo

= Wordpress =

 * Overview (Conditionals, Constants)
 * Variables (Browser, Server)
 * Actions
 * Filters
 * Theme (Menu Locations, Menus, Sidebars, Widgets)
 * Shortcodes
 * Styles (Enqueued, Registered, WP_Styles)
 * Scripts (Enqueued, Registered, WP_Scripts)
 * Rewrite (Rules, WP_Rewrite)
 * Locale
 * Roles

= WooCommerce =

 * Conditionals
 * Constants
 * Options

= MySQL =
 * Tables
 * Variables
 * Queries

== Screenshots ==

1. The admin toolbar menu showing an overview
2. Aggregate database queries by component
3. User capability checks with an active filter
4. Database queries complete with filter controls
5. Hooks and actions
6. HTTP requests (showing an HTTP error)
7. Aggregate database queries grouped by calling function

== Frequently Asked Questions ==

= Who can see Query Monitor's output? =

By default, Query Monitor's output is only shown to Administrators on single-site installs, and Super Admins on Multisite installs.

In addition to this, you can set an authentication cookie which allows you to view Query Monitor output when you're not logged in (or if you're logged in as a non-administrator). See the bottom of Query Monitor's output for details.

= Does Query Monitor itself impact the page generation time or memory usage? =

Short answer: Yes, but only a little.

Long answer: Query Monitor has a small impact on page generation time because it hooks into WordPress in the same way that other plugins do. The impact is low; typically between 10ms and 100ms depending on the complexity of your site.

Query Monitor's memory usage typically accounts for around 10% of the total memory used to generate the page.

= Are there any add-on plugins for Query Monitor? =

[A list of add-on plugins for Query Monitor can be found here.](https://github.com/johnbillion/query-monitor/wiki/Query-Monitor-Add-on-Plugins)

In addition, Query Monitor transparently supports add-ons for the Debug Bar plugin. If you have any Debug Bar add-ons installed, just deactivate Debug Bar and the add-ons will show up in Query Monitor's menu.

= Where can I suggest a new feature or report a bug? =

Please use [the issue tracker on Query Monitor's GitHub repo](https://github.com/johnbillion/query-monitor/issues) as it's easier to keep track of issues there, rather than on the wordpress.org support forums.

= Is Query Monitor available on WordPress.com VIP Go? =

Yep! You just need to add `define( 'WPCOM_VIP_QM_ENABLE', true );` to your `vip-config/vip-config.php` file.

(It's not available on standard WordPress.com VIP though.)

= I'm using multiple instances of `wpdb`. How do I get my additional instances to show up in Query Monitor? =

You'll need to hook into the `qm/collect/db_objects` filter and add an item to the array with your connection name as the key and the `wpdb` instance as the value. Your `wpdb` instance will then show up as a separate panel, and the query time and query count will show up separately in the admin toolbar menu. Aggregate information (queries by caller and component) will not be separated.

= Do you accept donations? =

No, I do not accept donations. If you like the plugin, I'd love for you to [leave a review](https://wordpress.org/support/view/plugin-reviews/query-monitor). Tell all your friends about the plugin too!

== Changelog ==

For Query Monitor's changelog, please see [the Releases page on GitHub](https://github.com/johnbillion/query-monitor/releases).
