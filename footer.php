<?php
	/*

		Hi,

		Please DO NOT remove the link to my website from the footer. I have
		been working hard to make this theme and you have downloaded it for
		FREE. This is all I ask from you in return for Mandigo which didn't
		cost you a cent.

		Thank you.

		tom

	*/

	global $mandigo_options, $dirs, $wpmu;
?>
</tr>
</table>
<?php
	// HTML Inserts: footer
	echo get_option('mandigo_inserts_footer');
?>
</div>

<div id="footer" class="png">
	<p>
<?php
	// if this is WordPress MU
	if ($wpmu) {
		$current_site = get_current_site();
?>
		<?php _e('Powered by <a href="http://mu.wordpress.org/">WordPress MU</a>', 'mandigo'); ?> &amp; hosted by <a href="http://<?php echo $current_site->domain . $current_site->path ?>"><?php echo $current_site->site_name ?></a><br />
		<a href="http://<?php echo $current_site->domain . $current_site->path ?>wp-signup.php">Create a new blog</a> and join in the fun! <a href="http://www.onehertz.com/portfolio/wordpress/" target="_blank" title="WordPress MU themes">Mandigo theme</a> by tom. P2 Intergration by <a href="http://chikorita157.com">chikorita157</a>.

<?php
	}
	
	// if this is a regular WordPress
	else {
?>
		<?php _e('Powered by <a href="http://wordpress.org/">WordPress</a>', 'mandigo'); ?>, <a href="http://www.onehertz.com/portfolio/wordpress/" target="_blank" title="WordPress themes">Mandigo theme</a> by tom. P2 Intergration by <a href="http://chikorita157.com">chikorita157</a>.

<?php
	}



	// the rss links
?>
		<br /><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo $dirs['www']['scheme']; ?>images/rss_s.gif" alt="" /> <?php _e('Entries (RSS)', 'mandigo'); ?></a>
		<?php _e('and', 'mandigo'); ?> <a href="<?php bloginfo('comments_rss2_url'); ?>"><img src="<?php echo $dirs['www']['scheme']; ?>images/rss_s.gif" alt="" /> <?php _e('Comments (RSS)', 'mandigo'); ?></a>.
<?php


	// if we chose to display statistics at the bottom
	if ($mandigo_options['footer_statistics']) {
?>
		<br /><?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds.
<?php
	}
?>
	</p>
</div>
</div>

<?php
	// WordPress footer
	wp_footer();

	// set the theorical max-width value for the content column
	switch ($mandigo_options['layout_width']) {
		case 1024:
			switch ($mandigo_options['sidebar_count']) {
				case 0:
					$maxw = 915;
					break;
				case 1:
					$maxw = 685;
					break;
				case 2:
					$maxw = 455;
					break;
			}
			break;
		case 800:
			switch ($mandigo_options['sidebar_count']) {
				case 0:
					$maxw = 691;
					break;
				case 1:
				case 2:
					$maxw = 461;
					break;
			}
			break;
	}
?>

<script type="text/javascript">
<!-- // <![CDATA[

	jQuery(document).ready(function() {
		jQuery('#rssicon, #searchsubmit').hover(
			function() { this.src = this.src.replace('.gif','_hover.gif'); },
			function() { this.src = this.src.replace('_hover.gif','.gif'); }
		);

		jQuery('.entry img[align=left]' ).addClass('alignleft' );
		jQuery('.entry img[align=right]').addClass('alignright');
		
		if (jQuery.browser.msie) {
			if (/^[56]/.test(jQuery.browser.version)) {
				jQuery.ifixpng('<?php echo $dirs['www']['images']; ?>1x1.gif');
				jQuery('body, .png').ifixpng();
				jQuery('.commentlist.pre27 .avatar').height(36);
			}

<?php



	// if the 'pre whitespace' fix is enabled
	if ($mandigo_options['fixes_whitespace_pre']) {
?>
			jQuery('*').each(function() { if (this.style.whiteSpace == 'pre') jQuery(this).css({'white-space':'normal'}); });
<?php
	}
?>
		}
		
		jQuery('p:has(.dropcap)').append('<br class="clear"/>');

<?php



	// if the 'strip url in comments' fix is enabled
	if ($mandigo_options['fixes_comments_1']) {
?>
		jQuery('.commentlist p a').each(function() { if (jQuery(this).width() > <?php echo $maxw; ?>) jQuery(this).text(this.innerHTML.replace(/\/([^\/])/g, '/\n$1')); });
<?php
	}



	// if the 'replace url in comments' fix is enabled
	if ($mandigo_options['fixes_comments_2']) {
?>
		jQuery('.commentlist p a').each(function() { if (jQuery(this).width() > <?php echo $maxw; ?>) jQuery(this).text('link'); });
<?php
	}



	// unless we disabled animations
	if (!$mandigo_options['disable_animations']) {
		// if we chose to collapse all posts by default,
		// have jquery hide all containers which have the .entry class
		if ($mandigo_options['collapse_posts'] && (is_home() || is_archive())) {
?>
		jQuery('.entry').hide();
<?php
		}
?>
		togglePost = function(id) {
			if (!id) return;
			icon = jQuery('#switch-post-'+ id +' img');
			icon.attr('src', /minus/.test(icon.attr('src')) ? icon.attr('src').replace('minus', 'plus') : icon.attr('src').replace('plus', 'minus'));
			jQuery('#post-'+ id +' .entry').animate({ height: 'toggle', opacity: 'toggle' }, 1000);
		}

		jQuery('.switch-post img').bind('click', function() {
			id = jQuery(this).parents('li').attr('id').replace(/[^\d]+-/, '');
			icon = jQuery('#comment-'+ id +' .switch-post img');
			src  = icon.attr('src');
			icon.attr('src', /minus/.test(src) ? src.replace('minus', 'plus') : src.replace('plus', 'minus'));
			jQuery('#comment-'+ id +' div.comment').animate({ height: 'toggle', opacity: 'toggle' }, 1000);
			return false;
		});

		toggleSidebars = function() {
			icon = jQuery('.switch-sidebars img');
			icon.attr('src', /hide/.test(icon.attr('src')) ? icon.attr('src').replace('hide', 'show') : icon.attr('src').replace('show', 'hide'));
			jQuery('.sidebars').animate({ width: 'toggle', height: 'toggle', padding: 'toggle', border: 'toggle' }, 1000);
		}

<?php /* there's also "#wp-calendar caption", but it doesn't work too well */ ?>
		jQuery('.widgettitle, .linkcat *:first, .wpg2blockwidget h3').click(function() {
			jQuery(this).siblings().animate({ height: 'toggle', opacity: 'toggle' }, 1000);
		}).css({cursor: 'n-resize'});
<?php
	} // end of 'unless animations are disabled' condition



	// if we chose to apply a drop shadow to blog name & description
	if ($mandigo_options['header_blogname_shadow']) {
?>
		jQuery('#blogname').after('<span class="blogname text-shadow">'+ jQuery('#blogname a').html() +"<\/span>");
		jQuery('#blogdesc').after('<span class="blogdesc text-shadow">'+ jQuery('#blogdesc'  ).html() +'<\/span>');
<?php
	}



	// if we chose to apply a text stroke to blog name & description
	if ($mandigo_options['header_blogname_stroke']) {
?>

		jQuery.each(['tl','tr','bl','br'],function() {
			jQuery('#blogname').after('<span class="blogname text-stroke-'+ this +'">'+ jQuery('#blogname a').html() +'<\/span>');
			jQuery('#blogdesc').after('<span class="blogdesc text-stroke-'+ this +'">'+ jQuery('#blogdesc'  ).html() +'<\/span>');
		});
<?php
	}



	// if the header should be clickable
	if ($mandigo_options['header_clickable']) {
?>

		jQuery('#headerimg').click(function() {
			window.location = '<?php bloginfo('wpurl'); ?>';
		});
<?php
	}
	
	
	
	// if sidebar1 should go to the left
	if ($mandigo_options['sidebar_1_position'] == 'left') {
?>

		jQuery('#sidebar1 script').remove();
		t = jQuery('#sidebar1').clone(true);
		jQuery('#sidebar1').remove();
		jQuery('td#content').before(t);
<?php
	}



	// if sidebar2 should go to the left
	if ($mandigo_options['sidebar_2_position'] == 'left') {
?>

		jQuery('#sidebar2 script').remove();
		t = jQuery('#sidebar2').clone(true);
		jQuery('#sidebar2').remove();
		jQuery('td#content').before(t);
<?php
	}



	// if the 'touch content' fix is enabled
	if ($mandigo_options['fixes_touch_content']) {
?>

		jQuery('#content').css({verticalAlign:'top'});
		window.onLoad = function() { jQuery('#content').css({verticalAlign:'top'}); }
<?php
	}



	// header navigation submenus
?>

		jQuery('#header .page_item:has(ul)').addClass('has_sub, png');
		
		jQuery('#header .page_item').hover(
			function() {
				ul = jQuery(this).find('>ul');
				if (ul.hasClass('sliding')) return;
				a  = jQuery(this).find('>a');
				if (jQuery(this).parent().hasClass('pages')) {
					y = a.position().top + a.height();
<?php
	// set the animation speed variables
	switch ($mandigo_options['header_navigation_speed_appear']) {
		case 'slow':    $appear_speed = 800; break;
		case 'fast':    $appear_speed = 300; break;
		case 'instant': $appear_speed =   1; break;
	}
	switch ($mandigo_options['header_navigation_speed_disappear']) {
		case 'slow':    $disappear_speed = 800; break;
		case 'fast':    $disappear_speed = 300; break;
		case 'instant': $disappear_speed =   1; break;
	}
	
	// apply the same alignment as the top navigation items to submenus 
	if ($mandigo_options['header_navigation_position'] == 'left') {
?>
				x = a.position().left;
<?php
	}
	elseif ($mandigo_options['header_navigation_position'] == 'right') {
?>
				x = a.position().left + a.width() - ul.width();
<?php
	}
	else {
?>
				x = a.position().left + a.width() / 2 - ul.width() / 2;
<?php
	}
?>
				}
				else {
					y = jQuery(this).position().top - 5;
					x = jQuery(this).parent().width();
				}
				jQuery(this)
					.find('>ul')
					.css({left:x, top:y})
					.addClass('sliding')
					.slideDown(<?php echo $appear_speed; ?>, function() { jQuery(this).removeClass('sliding'); });
			},
			function() {
				jQuery(this)
					.find('>ul')
					.addClass('sliding')
					.slideUp(<?php echo $disappear_speed; ?>, function() { jQuery(this).removeClass('sliding').hide(); });
			}
		);
<?php



	// fix the shifted footer in the signup form on WPMU
	if ($wpmu && function_exists('signup_blog')) {
?>
		jQuery('#page').css({marginBottom:0});
<?php
	}



	// fix the gap between the sidebox and the sidebars in IE
	if (mandigo_sidebox_conditions()) {
?>

		if (jQuery.browser.msie) {
			h = jQuery('#content').height()
			  - jQuery('#sidebox .sidebars').height()
			  - jQuery('#sidebar1 .sidebars').height()
			  - parseInt(jQuery('#sidebox').css('padding-bottom'))
			  - parseInt(jQuery('#sidebox .sidebars').css('padding-top'))
			  - parseInt(jQuery('#sidebox .sidebars').css('padding-bottom'))
			  - parseInt(jQuery('#sidebar1 .sidebars').css('padding-top'))
			  - parseInt(jQuery('#sidebar1 .sidebars').css('padding-bottom'));
			if (h > 0)
				jQuery('#sidebar1').append('<div style="height: '+ h +'px"><\/div>');
		}
<?php
	}



	// alternate coloring of comments
?>

		jQuery('.commentlist li:even').addClass('alt');
});
// ]]> -->
</script>
<div id="notify"></div>

<div id="help">
	<dl class="directions">
		<dt>c</dt><dd><?php _e('compose new post', 'p2'); ?></dd>
		<dt>j</dt><dd><?php _e('next post/next comment', 'p2'); ?></dd>
		<dt>k</dt> <dd><?php _e('previous post/previous comment', 'p2'); ?></dd>
		<dt>r</dt> <dd><?php _e('reply', 'p2'); ?></dd>
		<dt>e</dt> <dd><?php _e('edit', 'p2'); ?></dd>
		<dt>o</dt> <dd><?php _e('show/hide comments', 'p2'); ?></dd>
		<dt>t</dt> <dd><?php _e('go to top', 'p2'); ?></dd>
		<dt>l</dt> <dd><?php _e('go to login', 'p2'); ?></dd>
		<dt>h</dt> <dd><?php _e('show/hide help', 'p2'); ?></dd>
		<dt>esc</dt> <dd><?php _e('cancel', 'p2'); ?></dd>
	</dl>
</div>
<?php
	// HTML Inserts: very end of the page
	echo get_option('mandigo_inserts_veryend');
?>
</body>
</html>
