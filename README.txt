=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: zeko3991@gmail.com
Tags: Wikidata, Wordpress, metadata, Schema.org, microdata, microformats, links
Requires at least: 4.3
Tested up to: 4.8
Stable tag: 4.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Wikidata References is a plugin developed to help WordPress users to add associations among the tags
and categories they use in this WordPress websites and to add some specific metadata and structured 
microdata using Schema.org frame.

== Description ==

Wikidata References will add a new field to tags and categories for adding a Wikidata Item ID to which 
tags and categories terms will be associated to.

Also it will provide the site admin the option to add a link to those associated Wikidata items 
to the head metadata section, or to the tag/category archive title.

User will also have the option to frame tag and categories archive pages links into a Schema.org
microformat.

This plugin was developed under the requisites of a Wikimedia phabricator request at 
https://phabricator.wikimedia.org/T138371



== Installation ==

This section describes how to install the plugin and get it working.


1. Upload `wikidata-references.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set up your settings at Wikidata References settings page
4. Go to edit and categories term edit screen, and start associating your terms with 
   Wikidata items.
   

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`