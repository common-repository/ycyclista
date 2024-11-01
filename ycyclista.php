<?php
/* 
Plugin Name: yCyclista
Plugin URI: http://www.social-ink.net/blog/ycyclista-a-plugin-for-your-wordpress-gallery-that-allows-slideshows-transitions-etc
Author URI: http://yonatanr.net
Version: 1.2.3
Author: Yonatan Reinberg
Description: Simple plugin to replace WP gallery with <a href="http://www.malsup.com/jquery/cycle/">JQCycle</a>. Validates fully... and stuff. <a href="options-general.php?page=yCyclista">Goto settings</a>.
 
Copyright 2010/11  Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - http://social-ink.net
*/

	//defaults for the options
		$twidth=get_option('thumbnail_size_w');$theight=get_option('thumbnail_size_h');
		add_option('ycyclista_fx', 'scrollLeft');			
		add_option('ycyclista_speed', '1000');	
		add_option('ycyclista_fx_anim', 'false');		
		add_option('ycyclista_thumbs_display', 'true');
		add_option('ycyclista_thumb_width', $twidth);		
		add_option('ycyclista_thumb_height', $theight);	

	//captions 
		add_option('ycyclista_captions', 'true');	
		add_option('ycyclista_captions_fancy', 'true');	
		
	//add navigation defaults
	
		add_option('ycyclista_nav_buttons', 'true');		
		add_option('ycyclista_nav_position', 'above');			
		add_option('ycyclista_nav_prev_text', 'previous');		
		add_option('ycyclista_nav_next_text', 'next');	
		add_option('ycyclista_nav_sep_text', '/');	
	
	//options/admin stuff
	add_action('admin_menu', 'ycyclista_add_menu');		//options page

		function ycylista_admin() {  
			 include('ycyclista_admin.php');  
		 }  
		 
		function ycyclista_add_menu() {  
		  add_options_page("yCyclista", "yCyclista", 'manage_options', "yCyclista", "ycylista_admin");  
		}  

	//script inits

		function yc_enqueue_scripts() {
			$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
			wp_enqueue_script('jquery-cycle', $plugin_url . '/js/jquery.cycle.all.min.js', array('jquery'), '1.0' );
		}
		if (!is_admin()) {
			add_action('init', 'yc_enqueue_scripts');
		}	
		
	//theme stuff
	add_action('wp_head', 'ycyclista_jquery_header');	//header

	add_filter('post_gallery', 'ycyclista_gallery',10,2);	//override WP's gallery

		function ycyclista_jquery_header()
		{
			$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
			
			echo "\n\n<!-- Beginning of scripting by yCyclista by Yonatan Reinberg/Social Ink (c) 2010 - http://social-ink.net - yoni@social-ink.net -->\n\n";
			
			echo '<link href="' . $plugin_url . '/ycyclista.css" rel="stylesheet" type="text/css" />' . "\n";	
			
			//build JS script here?
				//get options for animations
				$option_effect = get_option('ycyclista_fx');
				$option_speed = get_option('ycyclista_speed');
				$option_animate = get_option('ycyclista_fx_anim');
				$show_captions = get_option('ycyclista_captions');
				$show_captions_fancy = get_option('ycyclista_captions_fancy');
				
				//get options for desired thumbnail size
				$show_thumbnails = get_option('ycyclista_thumbs_display');
				$thumbheight =get_option('ycyclista_thumb_height');
				$thumbwidth = get_option('ycyclista_thumb_width');	

				//load the default widths to grab the thumbnail images. 
				$wp_thumbheight = get_option('thumbnail_size_h');
				$wp_thumbwidth = get_option('thumbnail_size_w');
				$wp_thumbdefaults = '-' . $wp_thumbwidth . 'x' . $wp_thumbheight;

				//navigation buttons??
				$nav_on = get_option('ycyclista_nav_buttons');
				if($nav_on) { //navigation is on
					$nav_pos = get_option('ycyclista_nav_position');				
					$nav_prev = get_option('ycyclista_nav_prev_text');
					$nav_next = get_option('ycyclista_nav_next_text');	
					$nav_separate = get_option('ycyclista_nav_sep_text');	
					$nav_string = '<div id="yc_nav"><a id="yc_prev" href="#">' . $nav_prev . '</a><span class=\"yc_nav_separator\">' . $nav_separate . '</span><a id="yc_next" href="#">' . $nav_next . '</a></div>';
				}					
				
				//start the script here
				$scriptoutput = "<script type=\"text/javascript\">\n";
				
				$scriptoutput .= "//<![CDATA[\n\n"; //cdata to validate XHTML
				
				$scriptoutput .= "var \$j = jQuery.noConflict();\n";
				
				$scriptoutput .= "\$j(function(){\n
								\n";
								
				
				if(!$option_animate) {				//next/prev functions\n
				$scriptoutput .= "function addNavigations(curr, next, opts) {\n
									var index = opts.currSlide;\n
									\$j('#yc_prev')[index == 0 ? 'hide' : 'show']();\n
									\$j('#yc_next')[index == opts.slideCount - 1 ? 'hide' : 'show']();\n
								}\n
								\n";
				}
				
				$scriptoutput .= "//start script\n";
				
			$scriptoutput .= "if(\$j('#ycyclista_image_gallery').length > 0 ) {\n\n";	//added 1.2.3 to check for nonexistent
				
				$scriptoutput .= "\$j('#ycyclista_image_gallery')";						//begin image gallery declaration
				
				if(!$option_animate) {													//not animating so build complex script...
					if($nav_on) {
						if($nav_pos == 'above') { 										//we want to show it above or below? if above, do it below manually...
							$scriptoutput .= ".before('" . $nav_string . "').after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div><ul id=\"yc_thumbnail_frame\">')";
						}
						else { //below
							if($show_thumbnails && $show_captions) 						//we're showing thumbnails & captions
								$scriptoutput .= ".after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div>" . $nav_string . "<ul id=\"yc_thumbnail_frame\">')";
							elseif($show_thumbnails)									//thumbnails, no captions
								$scriptoutput .= ".after('" . $nav_string . "<ul id=\"yc_thumbnail_frame\">')";													
							elseif($show_captions)										//captions, no thumbnails
								$scriptoutput .= ".after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div>" . $nav_string . "')";								
							else														//just navigation
								$scriptoutput .= ".after('" . $nav_string . "')";					
						}
					}
					else {
						if($show_thumbnails && $show_captions) 							//we're showing thumbnails & captions
							$scriptoutput .= ".after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div><ul id=\"yc_thumbnail_frame\">')";
						elseif($show_thumbnails)										//thumbnails, no captions
							$scriptoutput .= ".after('<ul id=\"yc_thumbnail_frame\">')";													
						elseif($show_captions)											//captions, no thumbnails
							$scriptoutput .= ".after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div>')";								
					}
				}
				elseif($option_animate && $show_captions) { 							//animating so nothing but the show itself
					$scriptoutput .= ".after('<div id=\"gallery_captions\"><p id=\"captions\"></p></div>')";
				}
								
				$scriptoutput .= ".cycle({\n";											//choose cycle features
					
					$scriptoutput .= "fx:\t'" . $option_effect . "',\n";
					//$scriptoutput .= "speed:\t" . $option_speed . ",\n"; disabled for IE7 jquery comma problem
					$scriptoutput .= "speed:\t" . $option_speed;
					
					if(!$option_animate) { 												//we're not animating, so we'll need more stuff

						$scriptoutput .= ",\ntimeout:\t0,\n";			

						if($nav_on) {
							$scriptoutput .= "prev:\t'#yc_prev',\n";					
							$scriptoutput .= "next:\t'#yc_next'";	
						}
						else
							$scriptoutput .= "next:\t'#ycyclista_image_gallery'";
						//$scriptoutput .= "after:\taddNavigations,\n";									// uncomment if you want funky navigations, they disappear when there's no forward or backward to go			
										
									
						if($show_captions) {
							$scriptoutput .= ",\nafter:\tfunction() { 
												\$j('#captions').html(this.alt);
												}";
						}
				
						if($show_thumbnails) {
						
							$scriptoutput .= ",\npager:\t'#yc_thumbnail_frame',\n";
							
							$scriptoutput .= "pagerAnchorBuilder: function(idx, slide) {\n
																var slideurl = slide.src;\n 
																slideurl= slideurl.split(\".jpg\").join(\"" . $wp_thumbdefaults . ".jpg\");\n
																slideurl= slideurl.split(\".png\").join(\"" . $wp_thumbdefaults . ".png\");\n
																return '<li><a href=\"#\"><img class=\"yc_img_thumbs yc_images\" style=\"height:" . $thumbheight . "px; width:" . $thumbwidth . "px;\" src=\"' + slideurl + '\" /></a></li>';\n
															}\n"; 		
						
						}
					
					
					}
					elseif($option_animate && $show_captions) {
							$scriptoutput .= ",\nafter:\tfunction() { 
												\$j('#captions').html(this.alt);
												}\n";					
					}

				$scriptoutput .= "\n});\n";	//close cycle
			
			$scriptoutput .= "}\n";			//added 1.2.3
			
				//change the width of the gallery caption output
				if($show_captions) {
					if($show_captions_fancy) {	//we need to get the width of the first image so we can set the appropriate padding, etc...
						$scriptoutput .= "\$j(\"#gallery_captions\").addClass(\"yc_captions_fancy\");\n";					
						$scriptoutput .= "var caption_width =\$j(\".yc_img_fullsize:first\").width();\n";
						$scriptoutput .= "caption_width = caption_width - 50;\n";
						
						// ok now set the width of the div to the accurately padded version
						$scriptoutput .= "\$j(\"#gallery_captions\").width(caption_width);\n\n";
						}
				}
			
			$scriptoutput .= "});\n";	//close JQuery ouput
			
			$scriptoutput .= "	//]]>"; //close cdata to validate XHTML
			
			$scriptoutput .= "\n</script>";
				
			echo $scriptoutput;
			
			echo "\n\n<!-- End of scripting by yCyclista by Yonatan Reinberg/Social Ink (c) 2010 - http://social-ink.net - yoni@social-ink.net -->\n\n";
		}

		function ycyclista_gallery($null, $attr = array( )) {
		
			extract(shortcode_atts(array(	//get the exclude attributes if any
				'exclude' => ''
			), $attr)); 			
			
			$output .= '<div id="ycyclista_frame">';

			$option_animate = get_option('ycyclista_fx_anim');	
			
			$output .=	"<div id=\"ycyclista_image_gallery\">\n";
						global $wp_query;	global $post;					//preserve old post/Loop
						$tmp_post = $post;	$tmp_query = $wp_query;			//preserve old post/Loop
						
						$thePostID = $wp_query->post->ID;	//we are weirdly outside the loop even though its overriding the gallery, which is inside... anyway, we need to get this ID.

						$args = array(
							'post_type' => 'attachment',
							'orderby' => 'menu_order ID',
							'order' => 'ASC',
							'numberposts' => -1,
							'post_status' => null,
							'post_parent' => $thePostID
							); 
						$attachments = get_posts($args);	//get all attachments
						global $attachment;
						if ($attachments) {
						
							$attach_id = 0;
								
							foreach ($attachments as $attachment) {
								$img_title = apply_filters('the_title', $attachment->post_title);
								$img_full = wp_get_attachment_image_src($attachment->ID, 'full');
								$attach_id++;
								
								//are we excluding anything?
								$my_id = $attachment->ID;
								$excludes = explode(",", $exclude);	//create array from excludes								
								if (in_array($my_id,$excludes))
									continue;
								
								$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);		
								
								if(empty($alt))
									$alt=":-( every image deserves a alt text (its good SEO too!)";
								
								
								//$output .= "<div id=\"yc_slide" . $my_id . "\" class=\"yc_slide\">";		//uncomment to make everything a slide; can help with image centering, but screws up automatic thumbnail generator.

								if($attach_id == 1)
									$output .= "<img src=\"" . $img_full[0] . "\" id=\"ycycle_pic_" . $my_id . "\" alt=\"" . $alt . "\" class=\"yc_img_fullsize yc_images\" style=\"display:block;\" />\n";
								else
									$output .= "<img src=\"" . $img_full[0] . "\" id=\"ycycle_pic_" . $my_id . "\" alt=\"" . $alt . "\" class=\"yc_img_fullsize yc_images\" />\n";
									
								//$output .= "</div>";									//uncomment to make everything a slide; can help with image centering, but screws up automatic thumbnail generator.

								$output .= "\n";
							}
						}
						
						$post = $tmp_post; $wp_query = $tmp_query;			//return old post/Loop


				$output .=	"\n</div>\n";

			$output .= "</div>" . $content;
							
			return $output;
		}
?>
