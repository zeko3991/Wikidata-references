=== Wikidata References for WordPress ===
Contributors: zeko3991
Tags: Wikidata, metadata, Schema.org, microdata, microformats, links, tags, categories
Donate link: zeko3991@gmail.com
Requires at least: 3.9
Tested up to: 4.9
Requires PHP: 7.1
Stable tag: 1.0.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Wikidata References is a plugin developed to help WordPress users to add associations among the tags
and categories they use in this WordPress websites and to add some specific metadata and structured 
microdata using Schema.org frame.

== Description ==
Wikidata References will add a new field to tags and categories for adding a Wikidata Item ID to which tags and categories terms will be associated to.

Also it will provide the site admin the option to add a link to those associated Wikidata items to the head metadata section, or to the tag/category archive title.

User will also have the option to frame tag and categories archive pages links into a Schema.org
microformat.

This plugin was developed under the requisites of a Wikimedia phabricator request at 
[https://phabricator.wikimedia.org/T138371](https://phabricator.wikimedia.org/T138371)

== Installation ==
1. Upload `wikidata-references.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Set up your settings at Wikidata References settings page
4. Go to edit and categories term edit screen (edit-tags.php), and start associating your terms with 
   Wikidata items.

== Screenshots ==
1. Wikidata References for WordPress setup
2. Edit-tag.php with Wikidata ID field
3. Disambiguation screen
4. Archive tag title changed by a link to Wikidata Item
5. Links added to head under user's decision

== Changelog ==
= 1.0.1 =
* Now, terms at disambiguation screen are shown in multiple languages. If not found they will be displayed in English.
* Added a \'loading\' animation while getting Wikidata info.
* \'Describedby\' links at archive tags/categories pages priority descended.