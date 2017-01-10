=== Comment Rating Field Pro Plugin ===
Contributors: n7studios,wpcube
Donate link: http://www.wpcube.co.uk/plugins/comment-rating-field-pro-plugin
Tags: comment,field,rating,star,gd,comments,review,amazon,rich,snippets,schema,markup
Requires at least: 3.6
Tested up to: 4.5.2

Adds a 5 star rating field to the end of a comment form in WordPress.

== Description ==

Adds a 5 star rating field to the end of a comment form in WordPress, allowing the site visitor to optionally submit a rating along with their comment. Ratings are displayed as stars below the comment text.

= Support =

Please email support@wpcube.co.uk, with your license key.

= WP Cube =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us:

* <a href="http://www.wpcube.co.uk">Our Plugins</a>
* <a href="http://www.facebook.com/wpcube">Facebook</a>
* <a href="http://twitter.com/wp_cube">Twitter</a>
* <a href="https://plus.google.com/b/110192203343779769233/110192203343779769233/posts?rel=author">Google+</a>

== Installation ==

1. Upload the `comment-rating-field-pro-plugin` folder to the `/wp-content/plugins/` directory
2. Active the Comment Rating Field Pro through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `Comment Rating Field Pro` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Comment Rating Field Plugin on Comment Form
2. Star rating displayed below comment text 

== Changelog ==

= 3.3.6 =
* Fix: Column count SQL error on Field Group creation

= 3.3.5 =
* Added: Option to choose the maximum rating on the scale, instead of always being out of 5
* Added: Option to display rating percentage after star rating on comments output
* Added: Option to display rating number after star rating on excerpt / content output
* Added: Option to display rating percentage after star rating on excerpt / content output
* Added: Option to display rating on Comments RSS feed
* Fix: Improved Admin UI
* Fix: Optimized CSS

= 3.3.4 =
* Added: Option to display rating number after star rating on comments
* Fix: Allow users to leave comments without ratings when Disable on Reply = Yes and Limit Ratings = Yes
* Fix: Use different separator on widget for Post Type and Taxonomy to avoid conflict with Post Types that use underscores in their name

= 3.3.3 =
* Fix: Show and hide options in Field Groups conditionally when they should be displayed

= 3.3.2 =
* Fix: Shortcodes will correctly process in sidebar text widgets
* Fix: Licensing mechanism works correctly with W3 Total Cache and memcache

= 3.3.1 =
* Added: Unique CSS classes to comment rating output and text (.crfp-average-rating, .crfp-rating-breakdown, .crfp-rating-text)
* Fix: Half ratings would wrongly display on average output when a Post has no comments with ratings, and the field group has half ratings disabled

= 3.3.0 =
* Fix: Don't filter query using pre_get_posts when no Post Types are defined for rating sort ordering

= 3.2.9 =
* Fix: Average and total ratings were reset on Post edit
* Fix: Display Average Rating setting in Edit Field Group would not display the saved value
* Fix: Show Breakdown option not displaying on some sections 
* Fix: Compatibility with Akismet to correctly detect spam comments, instead of marking them as Pending

= 3.2.8 =
* Fix: Correctly remove free settings once automatically migrated to Pro.

= 3.2.7 =
* Added: Rating output on RSS Feeds
* Added: get_posts_ordered_by_rating() function (see Documentation)
* Added: sort_posts_by_rating_post_types filter (see Documentation)
* Added: Option to sort comments by rating
* Added: Option to filter comments by rating
* Added: Recipe Schema
* Fix: Use defaults array to populate new field groups and reduce errors
* Fix: Store average rating and total rating meta values on all Posts, so sorting works
* Fix: Upgrade from Free version now working for some installations
* Fix: Added 'displaytotalratingsbefore' and 'displaytotalratingsafter' options to display_average_rating(), per Documentation
* Fix: Reinstated Taxonomies and Taxonomy Terms on Widget
* Fix: Edit Comment Rating Field Display

= 3.2.6 =
* Added: Singleton pattern for classes

= 3.2.5 =
* Fix: Return excerpt text if no rating field group applies to the given section.

= 3.2.4 =
* Fix: Read Post ID from function and shortcode if specified by using $atts instead of $instance

= 3.2.3 =
* Fix: License check takes place outside of admin if required
* Fix: Widget errors
* Fix: Localization loading moved to outside of admin
* Fix: Activation on new multisite activation
* Fix: Upgrade routine from pre 3.0

= 3.2.2 =
* Fix: display_rating_fields() and display_average_rating() now correctly register and function
* Fix: New Posts with no ratings and percentage output enabled no longer display an error

= 3.2.1 =
* Fix: Fatal error on some plugin activations

= 3.2.0 =
* Added: Plugin structure changes and code optimisation for better performance
* Added: Jetpack Comments Support
* Added: Reset Ratings on Post Edit screen (removes comment ratings and resets Post rating metadata)
* Added: Customise "from X ratings" text on excerpts, content and shortcodes
* Fix: Don't recalculate ratings every time for a logged in user
* Fix: Output rating count if settings require it and there are no ratings (fixes issues with Rich Snippets markup validation)

= 3.1.3 =
* Added: Delete rating field option in group
* Fix: Stars sometimes not honoring custom color settings due to CSS minification

= 3.1.2 =
* Added: Minify CSS and JS
* Fix: itemReviewed attribute added to Review schema to fix Google Testing Tool validation errors
* Fix: WooCommerce 2.3.8 compatibility

= 3.1.1 =
* Fix: Removed HTML comments which prevent some third party plugins from working with CRFP when encoding HTML as JSON

= 3.1.0 =
* Bug fixes

= 3.0.9 =
* Bug fixes

= 3.0.8 =
* Fix: Don't die() when no CRFP groups found for custom CSS output

= 3.0.7 =
* Added: CSS reset for better compatibility with themes
* Fix: Use plugin_dir_url() for HTTPS support

= 3.0.6 =
* Added: Option to filter comments by rating when using bar output
* Fix: Symlink support
* Fix: Half ratings on new field groups not always showing
* Fix: Calculations run only when comments are added, edited or deleted for better performance
* Fix: Comments link from Excerpts now loads correct URL
* Fix: Custom styling now applied to Excerpts / Archive screens

= 3.0.5 =
* Fix: Division by zero error

= 3.0.4 =
* Added: Option to choose no schema on a Rating Field Group
* Fix: Cancel button hover state

= 3.0.3 =
* Added: Full WooCommerce Product Support
* Added: Define star sizes (default: 16px)
* Fix: Vertical star alignment with rating labels
* Fix: Paragraph formatting not showing on comments
* Fix: Rating Field Groups and Fields included in JSON export

= 3.0.2 =
* Fix: Better upgrade routine when migrating from 2.4.6

= 3.0.1 =
* Fix: Prevent license key from being overwritten by WordPress nonce when saving a rating field group
* Fix: New installations didn't always setup database tables correctly

= 3.0 =
* Added: Groups - A powerful new way to add Rating Fields to your WordPress web site.
* Added: Rich Snippet Schema Markup - choose a schema (e.g. Review, Product, Place, Person)
* Added: Retina support for stars output (uses SVG)
* Added: Define star / bar colors for background, foreground and selection states
* Added: Average rating breakdown as bars - output the average rating as a 1 - 5 bar chart breakdown instead of stars (i.e. how Amazon does it)
* Added: Limit Ratings - choose to prevent commentors leaving more than one rating per Post / Page / CPT. 
* Fix: Display average rating stars on Widget
* Fix: Only load JS and CSS when needed
* Fix: Only calculate average rating from approved comments (some comments awaiting moderation were wrongly included in calculations previously)

= 2.4.6 =
* Fix: Delete Post Meta rating values when there are no comments with ratings (prevents average rating being wrongly retained)
* Fix: Multiple shortcodes to output average ratings on a single Page

= 2.4.5 =
* Fix: Use $wpdb->prepare() instead of mysql_real_escape_string for better SQL injection compatibility protection.
* Fix: When a rating field is required and replies are disabled, prevent validation checks for ratings on replies.
* Added: Anchor link option from Average Rating to Comments section on Excerpt
* Added: Anchor link option from Average Rating to Comments section on Content
* Added: Anchor link option from Average Rating to Comments section on Shortcode

= 2.4.4 =
* Added: Import + Export Settings, allowing users to copy settings to other plugin installations
* Added: Average Rating + Total Rating keys are set on all Pages, Posts and Custom Post Types, to ensure all Posts are included when using orderby=meta_value_num on WP_Query

= 2.4.3 =
* Fix: License server endpoint

= 2.4.2 =
* Fix: CRFP Top Rating Widget showing too many stars when a half average rating defined
* Fix: CSS styling on rating inputs
* Fix: Ratings not displaying on some comments

= 2.4.1 =
* Fix: Undefined properties on CRFP Top Rating Widget
* Fix: CRFP Top Rating Widget - use siderbar defined heading tag for title
* Fix: Force license key check method to beat aggressive server caching 

= 2.4 =
* Fix: Undefined property: CRFPTopRatedPosts::$plugin
* Fix: Rating fields incorrectly displaying when "Disable on Replies" = Yes and Javascript on WordPress Comments are enabled
* Fix: Ratings in WordPress Admin > Comments not displaying correctly in WordPress 3.9+
* Fix: Ratings in WordPress Admin > Comments > Edit not displaying correctly in WordPress 3.9+
* Fix: Average rating recalculation on comment published -> trash
* Fix: Average rating recalculation on comment trash -> delete
* Fix: Average rating recalculation on comment trash -> restore
* Added: Filter: crfp_display_rating_field
* Added: Filter: crfp_display_comment_rating 
* Added: Filter: crfp_display_post_rating
* Added: Filter: crfp_display_post_rating_excerpt
* Added: Filter: crfp_display_post_rating_content
* Added: Filter: crfp_display_post_rating_shortcode
* Added: Support menu with debug information
* Added: Half ratings option (.5)

= 2.3 =
* Added text-domain to all strings

= 2.2.9 =
* Fix for character encoding issues for Greek + Portugese when using PHP < 5.4

= 2.2.8 =
* Added translation support and .pot file

= 2.2.7 =
* Shortcode supports new `id` parameter to optionally specify which Post ID to get average rating from

= 2.2.6 =
* Changed filter for outputting ratings in comments to prevent duplicate display

= 2.2.5 =
* Better license key transient check / refresh to prevent frontend functionality from not working

= 2.2.4 =
* Fix: Better JS targeting to prevent conflicts with PremiumPress Themes

= 2.2.3 =
* Pro Version: Added display_average_rating() function to manually output average rating.
* Fix: Internationalization support added.

= 2.2.2 =
* Fix: Better jQuery rating integration
* Pro Version: Edit ratings in Administration > Comments

= 2.2.1 =
* Dashboard CSS + JS enhancements

= 2.2 =
* Added: display_rating_field() function to manually output rating fields on custom comment forms, where comment_form() is not used in the theme
* Notice: Output an error message if comment_form() and display_rating_field() are not included in the active theme's comments.php file
* Notice: Output an error message if no Rating Fields have been defined
* Setting: disable rating fields on comment reply forms
* Fix: Zero ratings are ignored in average / rating calculations

= 2.1.1 =
* Fix: Activation routine for DB table creation

= 2.1 =
* Fix: PHP notice messages
* Fix: Better rating field positioning options and code
* Fix: Licensing and update mechanism enhancements

= 2.0 =
* Pro Version: Support for multiple rating fields
* Pro Version: Additional display settings for ratings on excerpts, content + comments
* Fix: admin_enqueue_scripts used to fix in WordPress 3.6+

= 1.5.1 =
* Pro Version: Improvements to Rich Snippets markup for better chance of display in Google Search Results
* Fix: Removed console.log() messages in JS
* Fix: frontend.js enqueues in footer

= 1.5 =
* Pro Version: Overhauled plugin structure to follow best practices
* Pro Version: Comments form rating field added via PHP instead of JS for better compatibility
* Pro Version: Admin UI improvements
* Pro Version: Average Rating on Excerpts
* Pro Version: Rating Field position above or below comments form

= 1.47 =
* Fix: License key save routine

= 1.46 =
* Pro Version: Version 2 of License Update Manager; uses JSON; removes requirements for cURL and SimpleXML

= 1.45 =
* Pro Version: Change of licensing system and branding to WP Cube
* Pro Version: Tidied up the Settings Panel by grouping settings together by their functionality (Post Types, Rating Input, Average Rating)
* Pro Version: Removed cancel button where rating field is required

= 1.44 =
* Fix: WordPress 3.4 compatibility
* Fix: jQuery Rating Javascript updated to 3.14

= 1.43 =
* Pro Version: Setting for defining Cancel Rating hover title
* Fix: Change conditional tag is_single() to is_singular() for better compatibility on some themes
* Fix: in_array datatype error on some Pages
* Fix: Top Rated Widget for correct post rating order

= 1.42 =
* Fix: HTML fix on Settings field to ensure settings save correctly

= 1.41 =
* Pro Version: Display Average Rating: Options changed to Never, When Rating(s) Exist (default), Always (grey stars if no rating / not a 5 star rating)
* Pro Version: Display Style: Yellow Stars only (default), Yellow Stars with Grey Stars (grey stars if no rating / not a 5 star rating)
* Pro Version: Google Rich Snippets microformats markup added to average rating for display within Google search results, per http://support.google.com/webmasters/bin/answer.py?hl=en&answer=146645#Aggregate_reviews
* Pro Version: Minor HTML and CSS changes to support above

= 1.4 =
* Removal of Donate Button
* On Activation, plugin no longer enables ratings on Pages and Posts by default
* Change: Average Rating displayed below content for better formatting and output on themes
* Fix: Language / localisation support
* Fix: Rating only shows on selected categories where specified in the plugin
* Fix: Recalculation of rating when comment removed
* Fix: Multisite Compatibility
* Fix: W3 Total Cache compatibility
* Pro Version: Support: Access to support ticket system and knowledgebase
* Pro Version: Custom Post Types: Support for rating display and functionality on ANY Custom Post Types and their Taxonomies
* Pro Version: Widgets: List the Top Rated Posts within your sidebars
* Pro Version: Shortcodes: Use a shortcode to display the Average Rating anywhere within your content
* Pro Version: Rating Field: Make rating field a required field
* Pro Version: Display Average Rating: Choose to display average rating above content, below content or above the comments form
* Pro Version: Seamless Upgrade: Retain all current settings and ratings when upgrading to Pro

= 1.3 =
* Javascript changes to fix comment rating field not appearing below comment field on some themes.

= 1.2 =
* Enable on Pages Option Added
* Enable on Post Categories Option Added
* Display Average Option Added - will display the average of all ratings at the top of the comments list.
* Donate Button Added to Settings Panel
* Change to readme.txt file for required ID on comment form.

= 1.01 =
* Fixed paths for CSS and Javascript.

= 1.0 =
* First release.

== Upgrade Notice ==
