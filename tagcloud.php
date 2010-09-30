<?php
/*
Template Name: Tag Cloud Template
*/

	global $mandigo_options, $dirs;
	
	get_header();

	// heading levels for page title (h1, h2, div, ...)
	$tag_page_title = $mandigo_options['heading_level_page_title'];
?>

	<td id="content" class="narrowcolumn"<?php if (mandigo_sidebox_conditions()) { ?> rowspan="2"<?php } ?>>

<?php
	if (have_posts()) {
		while (have_posts()) {
			the_post();
?>
		<div class="post" id="post-<?php the_ID(); ?>">
<?php
			// unless we disabled animations, display the buttons
			if (!$mandigo_options['disable_animations']) {
?>
			<span class="switch-post">
				<a href="javascript:toggleSidebars();" class="switch-sidebars"><img src="<?php echo $dirs['www']['icons']; ?>bullet_sidebars_hide.png" alt="" class="png" /></a><a href="javascript:togglePost(<?php the_ID(); ?>);" id="switch-post-<?php the_ID(); ?>"><img src="<?php echo $dirs['www']['icons']; ?>bullet_toggle_minus.png" alt="" class="png" /></a>
			</span>

<?php
			}
?>
		<<?php echo $tag_page_title; ?> class="posttitle"><?php the_title(); ?></<?php echo $tag_page_title; ?>>
			<div class="entry2">
<?php
			// the content itself
			the_content();
			
			// if wp supports tags
			if (function_exists('wp_tag_cloud'))
				wp_tag_cloud();
?>
				<div class="clear"></div>
			</div>
		</div>
<?php
			edit_post_link(
				'<img src="'. $dirs['www']['scheme'] .'images/edit.gif" alt="" /> '. __('Edit this page','mandigo'),
				'<p>',
				'</p>'
			);
		}
	}
?>
	</td>


<?php
	// if we have at least one sidebar to display
	if ($mandigo_options['sidebar_count']) {
		if (mandigo_sidebox_conditions())
			include (TEMPLATEPATH . '/sidebox.php');
	
		include (TEMPLATEPATH . '/sidebar.php');

		// if this is a 3-column layout
		if ($mandigo_options['layout_width'] == 1024 && $mandigo_options['sidebar_count'] == 2)
			include (TEMPLATEPATH . '/sidebar2.php');
	}

	get_footer();
?>
