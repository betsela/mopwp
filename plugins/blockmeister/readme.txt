=== BlockMeister - Block Pattern Builder  ===
Contributors: blockmeister, bvl
Author URI: https://wpblockmeister.com
Tags: block-editor, patterns, blocks, builder, "custom block patterns", "block pattern builder", Gutenberg
Donate link: https://wpblockmeister.com/
Requires at least: 5.8
Tested up to: 5.9
Requires PHP: 5.6
Stable tag: 3.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Visually create custom block patterns. No coding skills needed!
Categorize them easily and use keywords for easy discoverability.

== Description ==

With BlockMeister creating custom block patterns becomes easy. The patterns can be designed just like you design a blog post or a page with the block editor.
You can assign any (custom) category or keywords. By doing so, your patterns are categorized in a way that makes sense to your users and keywords make it easier to find a pattern. Your custom patterns will be available from the block patterns tab in the inserter panel.

**Features**

- Visual design custom block patterns using the pattern builder
- Or create a custom pattern from one or more selected blocks in the post editor
- Import patterns made/shared by trusted Pro users [new in 3.0]
- Assign your patterns to one or more categories
- Optionally add any keywords (makes them easy to find in the block inserter)
- Create custom pattern categories
- Set a viewport width to optimize the scaled width of the preview in the block inserter


**Pro Features**
If you would like even more features like: group locking, cloning, activating or deactivating individual patterns, exporting, importing, controlling which core/third party pattern sets are allowed to load, setting block styles and more, please check out our premium versions here:
<a href="https://wpblockmeister.com/#info-pro" target="_blank">Professional Version</a>

== Installation ==

**Installation directly from your site

1. Log in and navigate to **Plugins → Add New**.
2. Type "BlockMeister" into the Search and hit Enter.
3. Locate the BlockMeister plugin in the list of search results and click **Install Now**.
4. Once installed, click the **Activate** link.


== Getting Started ==

1. Locate and click on the '**Block Patterns**' menu item in the sidebar admin menu.
2. Click on the '**Add New**' button.
3. Give your pattern a name.
4. Start writing or choose any blocks you want to be part of your pattern.
5. Publish the pattern
6. Go to a test page and locate your pattern in the Block Inserter under the pattern tab and click on it.
7. The block pattern will now be added to your page. All contained blocks are now independent of the original pattern and can be edited as any regular block.


== Frequently Asked Questions ==

= Can I add custom CSS? =

Yes, select the block you want to style:

- Open the 'Advanced' panel
- Add a 'Additional CSS class'
- Add the styles for that class to your (child) theme stylesheet or add them via the customizer's 'Additional CSS' section.

OR upgrade to a premium version which comes with a built-in block level CSS editor

= Why did we integrate Freemius? =
Freemius is a managed eCommerce platform for selling WordPress plugins and themes.

When you activate or upgrade to v3.0 or higher you will be asked if you would like to opt in to the freemius functionality. To be clear: this is 100% optional!

With Freemius, we have more time to focus on our products and deliver better features for your website while making strategic business decisions that are based on the data you are willing to share as a consumer.

We can also use the data to figure out optimum pricing strategies for our customers and their companies. This promotes business sustainability and offers you long-term support and reliability for your plugin or theme purchase.

We would be thankful when you opt in to this but if not, don't worry, all plugin features will remain 100% functional!

You can read more about this at the <a href="https://freemius.com/privacy/data-practices/" target="_blank">Freemius privacy data practices page</a>.

= What happens to my patterns, after I deactivate the plugin? =

- The patterns will no longer be available from the inserter.
- Patterns inserted in posts and pages will not be affected.
- As soon as you re-activate the plugin the patterns will be available again from the inserter.

= What happens to my patterns, after I uninstall the plugin? =

- By default all data including your custom patterns will remain in the database, unless you prefer a complete data removal. This can be set on the settings page.
- Patterns already inserted in posts and pages will not be removed, regardless your uninstall preference.
- For test/debug purposes we advise to temporarily deactivate the plugin instead.

== Screenshots ==

1. Block patterns list table screen.
2. Categories list table screen.
3. Block pattern settings sidebar.

== Changelog ==

= [3.0.4] - 2022-01-31 =
- Fixed: missing return bug

= [3.0.3] - 2022-01-26 =
- Fixed: WP 5.9 incompatibility bug, related to _disable_block_editor_for_navigation_post_type (not yet loaded in 5.9 when we applied related filter)

= [3.0.2] - 2021-10-09 =
- Fixed: regression of old excerpt related bug
- Fixed: inserter not showing custom and default (generated) block pattern category on draft post (until first reload)

= [3.0.1] - 2021-10-01 =
- Fixed: unreadable properties of undefined in reusable block editor

= [3.0.0] - 2021-10-01 =

**All versions**

- made compatible with WP 5.8
- bumped required WP version to 5.8

- Added: Freemius SDK
- Added: User can now control (via a setting) whether all data is deleted during uninstall
- Added: Import patterns made/shared by trusted Pro users
- Added: Bulk action for trashing custom patterns

**Premium versions **
- New: first release of premium vers
- Added: Settings to control the loading of local and remote core block patterns and theme and plugin based block patterns
- Added: Activate/deactivate custom/core and third party block patterns (individual or by source via settings)
- Added: Clone patterns (including core and third party patterns).
- Added: Export/download (for backup or sharing) patterns.
- Added: Add custom styles to block patterns and blocks.
- Added: Lock pattern group or column (so users cannot move/remove/add child blocks).

**All versions**
- Improved: default block pattern category name will now be auto updated after user renames the site
- Improved: no longer (core/theme/plugin) registered categories are now auto removed as a custom block pattern taxonomy term

= [2.0.8] - 2021-04-16 =

**Fixed**
- Allow unfiltered pattern HTML for users who have the 'unfiltered_html' capability

= [2.0.7] - 2021-03-06 =

**Fixed**
- Too strict run context check excluded post-new screen

= [2.0.6] - 2021-02-27 =

**Fixed**
- Custom categories missing in inserter due to failed run context check; WP core test method not yet loaded

= [2.0.5] - 2021-02-19 =

**Fixed**
- Unnecessary term updates in synchronization method (leading to unintended cache purging in cache plugins)

**Improved**
- syncing and re-translating of dynamically registered categories and custom category terms
- run context

**Optimized**
- code run contexts

= [2.0.4] - 2021-01-19 =

**Fixed**
- prevention of term deletion of third party custom taxonomy

= [2.0.3] - 2020-09-18 =

**Fixed**
- excerpt screen option and control panel unintentionally removed from other post type edit screens

= [2.0.2] - 2020-09-12 =

**Fixed**
- path normalizing issue

= [2.0.1] - 2020-09-03 =

**Fixed**
- keywords taxonomy's edit capability

= [2.0.0] - 2020-09-01 =
Initial public release.

**Improved**
- completely refactored v1.0

**Added**
- Block settings menu item to add selected blocks to a new block pattern
- Categories
- Keywords
- Viewport width setting
- Added pattern editing related capabilities
- Limit pattern building to administrators by default
- Made translatable
- Added Dutch translation

== Upgrade Notice ==

= 3.0.2 =

Make sure you are running WP 5.8 or higher before upgrading.

