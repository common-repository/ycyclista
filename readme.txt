=== yCyclista ===
Contributors: yonisink
Donate link: http://www.social-ink.net/donating-to-social-ink
Tags: carousel, gallery, cycle, jquery cycle, jquery, jqcycle, slideshow, transitions, jcarousel, fade, scroll
Requires at least: 2.9
Tested up to: 3.0.3
Stable Tag: 1.2.3

yCyclista overwrites WP's [gallery] with jQuery cycle effects. Without editing, turn your posts into beautiful slideshows or gallery transitions.

== Description ==

yCyclista is a plugin that overwrites WP's gallery feature with jquery cycle effects. You can easily, without any editing, create jQuery slideshows or gallery transitions. Cross-browser tested, fully validates. No need to have your galleries in separate locations; now you can attach them to your post and use away! great for product images, shopping, photo gallery, so on! Now with caption & exclude support.

Other plugins make you upload to specific directories, use FTP, or install additional scripts that you then embed.  We'll have none of that here, thanks!

Our yCyclista plugin overwrites WordPress's basic gallery function with malsup's jQuery Cycle plugin, allowing the lay user to dynamically add slideshows or clickthrough galleries to their site.

For more information visit http://social-ink.net or http://www.social-ink.net/blog/ycyclista-a-plugin-for-your-wordpress-gallery-that-allows-slideshows-transitions-etc

Please note, as with every plugin or theme modification, you should do a backup of your files beforehand.  Although we've tested this across many installs, we are not responsible for anything it does to your system and do not guarantee support.

== Installation ==

1. Upload `ycyclista` directory to your  `/wp-content/plugins/` directory
2. Activate the ycyclista plugin through the 'Plugins' page in WordPress
3. Go to Settings > yCyclista and choose the correct transition effects, time, thumbnail/animation, and next/previous buttons preferences.
5. Add a new page or post to your wordpress, click the upload image button and upload images.  Go to the gallery tab and "insert gallery." You will see a gallery shortcode [gallery] or a big gallery icon (depending on what your editing mode is). Save and close.
6. That's all - yCyclista will take care of the rest.

== Frequently Asked Questions ==

= What do all the transitions look like? =

You can experiment or for a full listing of transitions visit http://www.malsup.com/jquery/cycle/browser.html.

= How do I change the location of things or how they look (eg add an image to the next/previous)? =

CSS is your friend. Add a background image to yc_next and yc_prev... Move margin-tops (ha!).  If you need me to do a custom version of this with everything suited for your needs, please visit us at http://social-ink.net or email me at yoni@social-ink.net.

= Why isn't it animating? =

As stated above, many things don't play well with WP's gallery feature. Visit our support page at http://www.social-ink.net/blog/ycyclista-a-plugin-for-your-wordpress-gallery-that-allows-slideshows-transitions-etc and post your comments.

= Can I click on the image to move to the next one? =

If you disable navigation controls. It's not compatible.  Explaining this to you would absolutely blow your mind apart.

= Can I have more than one gallery on one page; can it work on multiple loops? =

Nope. So if you have a category listing showing many posts' galleries, only the first on the page will work. Can't call it twice. If you have multiple loops on one page, only the first will show the gallery.  If you need it called twice you can contact me because that's a real custom job.

= I have fancycaptions turned on, and the grey background goes across my whole page =

The plugin tries to use fancy jq to divine the appropriate width, but if that doesn't work, go to your backend and navigate to the Settings - Media page in your WP install, and change the max width for the "Large size" to the width of your images.

= The thumbnails look all squishy! =

yCyclista doesn't generate any thumbnails, it only hooks into the WP gallery feature, which in turn generates thumbnails itself based on the settings in the backend, under Settings > Media. The squishiness lies there, friend!  For example, if under there you have thumbnail size to 150x150 (the WP default), the plugin takes these WP-generated thumbs and then compresses them further to the size in the yCyclista backend. for smoothest results, then, the yCyclista settings thumb size AND the WP thumb size should be the same (and you need to reupload your gallery every time you change the WP thumb size so it can regenerate these images).

= My thumbnails are checked, but they're not showing =

Is animation checked? It's not designed to show thumbnails when it animates (kind of redundant).

= Can I exclude any images from the gallery? =

Now you can! Use the general WP shortcode [gallery exclude="ID"] where ID is the picture "attachment ID".  If you know a little HTML/CSS, you can check the id of the image from the frontend (eg "ycycle_pic_12" means attachment 12), and use that.  If you want it through the backend, the easiest way is to go to Media > Library, hover over an image, and look in your statusbar, you'll see the id. It's a bit annoying but its the only way I know to find the ID easily. (eg you hover over an image and see "http://your-site.com/wp-admin/media.php?attachment_id=17&action=edit" in the bar; the ID of that image is 17).  

Use [gallery exclude="17"] or for multiple, [gallery exclude="15,19"] etc. 

== Screenshots ==

1. yCyclista options page.

== Changelog ==

= 1.2.3 = 
* Fixed bugs: alt text, removed "none" FX option, checked for length, hide remainder of images in case JS is disabled
* Updated to latest version of malsup plugin

= 1.2.2 =
* Change commma layout for increased compatibility with IE7
* Change php shorttags to <?php (referenced here here http://wordpress.org/support/topic/ycyclista-not-loading-fx-in-admin) for backwards compatibility
* Added "exclude" option
* Fixed thumbnail PNG problem

= 1.2 =
* Added captioning, rearranged directory and streamlined markups.

= 1.1 =
* Implemented dropdown for choosing Fx, changed some of the JS around to make the plugin tighter.

= 1.0 =
* First version released.

== Upgrade Notice ==

= 1.2.3 =
* As usual, upgrade at your own risk. I'm never quite sure how these updates work if you've done specific style mods, etc...

= 1.2.2 =
* Nada.

= 1.2 =
* By popular request, captions!  May not upgrade as smoothly as we'd want; please backup your old copy or visit WP Extend site (http://wordpress.org/extend/plugins/ycyclista/download) to re-download old version...

= 1.1 =
* Substantially better.

= 1.0 =
* Nothing to see here, folks.
