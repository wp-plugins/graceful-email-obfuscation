=== Graceful Email Obfuscation ===
Contributors: nicholaswilson
Tags: spam, email
Requires at least: 2.8
Tested up to: 2.9
Stable tag: trunk

Simple, highly secure email obfuscation. In brief: always get a clickable link at the end for the user. The JavaScript fallback is fully accessible.

== Description ==

Hiding email addresses from spammers has a long history. There are some obvious golden goals, like making the process seamless to your visitors, and be practicable to implement. The one that often gets left out is accessibility, but in some areas this is actually a legal requirement.[1]

= The method =
To my knowledge, this plugin implements the best method so far devised. Credit goes to Roel Van Gils for this technique, which he calls Graceful Email Obfuscation.[2] The basic idea is actually very simple: use PHP and JS to hide the address effectively, and make sure the fallback can be used easily by blind or visually impaired users by using a text-based CAPTCHA.

The PHP code takes passed-in emails and generates simply-encoded string using alphabetic characters. It is therefore very hard to imagine what sort of spammer's parser could possibly detect it. The only way is to load the whole DOM and jQuery to run the decryption routine. As far as we know, no bots go to those lengths, but it adds essentially no overhead if the theme or any other plugins already load jQuery; otherwise a fraction of a second might be added to page load.

= Comparisons with other plugins =
Amazingly, no other Wordpress plugins seem to use this method, either offering very little spam protection, or not coping with JavaScript turned off (3-10% of users). [Private Daddy](http://www.privatedaddy.com/) comes closest, but falls at the last hurdle by offering an image CAPTCHA with no non-visual alternative. Clunky though my first code bash is, it seems to the best available at the moment if accessibility is really important to you (of course, if I did not think that, I would not have written this). There is a similar Drupal plugin.[3]

= Comparisons with other methods =
The most viable alternative methods are:

1. Using images (*cf.* [Visagefolio](http://www.facebook.com/ "Facebook")). This requires typing and is a pain for everyone.
1. Poor "test [at] example [dot] com" style text replacements provide no protection and annoy users.
1. Clever things like outputting "moc.elpmaxe@tset" and using CSS unicode rules to reverse it. Unfortunately these cause trouble for the blind and in some browsers make it confusing to copy and paste the link.
1. Using XML comments or CSS `display: none;` to remove elements inside the link text. There is still no clickable link, which makes it hard for the blind to use.

= JavaScript fallback =
If there is no JavaScript, the user is taken to a page on the site using an empty content box where they are asked a simple text question to check whether they are a human. Basic sums are asked at the moment, though a wider range of questions could be added using the [testCAPTCHA.com](testCAPTCHA.com) service.

= References =
1. See particularly '508' legislation in America.
1. Original description on [A List Apart](http://www.alistapart.com/articles/gracefulemailobfuscation "A List Apart: Graceful Email Obfuscation").
1. [Drupal plugin](http://drupal.org/project/geo_filter)

Further reading:

1. [GEO review](http://technology.kencarlson.org/general/graceful-e-mail-obfuscation/) by Ken Carlson
1. Spam obfuscation comparison recommending use of this method for 508 compliance: [.eduGuru article](http://doteduguru.com/id415-e-mail-obfuscation.html) by Michael Fienen

== Installation ==

To use:

1. Upload the folder `geo-spam-prevention` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Use the shortcode `[email]` in your pages and posts.

= Syntax & Details =

The link can either be put inside the tag:

> `[email]test@example.com[/email]`

Or, an alternate link text can be provided by using `href`:

> `[email href="anthony@example.com"]Contact Anthony[/email]`

Further, any `style` or `class` attributes used on `[email]` get passed down to the `<a>` element generated, which also has the `geo-address` class added to it for styling your email links.

== Changelog ==

= 0.2 potential plans =
The choice of human-accessible questions could be opened up. A page scanner to avoid the use of shortcodes could be offered.

= 0.1 =
First release