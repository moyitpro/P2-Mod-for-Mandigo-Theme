<?php 
	global $mandigo_options, $dirs;

	get_header();

	// heading levels for post/page title (h1, h2, div, ...)
	$tag_post_title_single = $mandigo_options['heading_level_post_title_single'];
	$tag_page_title        = $mandigo_options['heading_level_page_title'      ];
?>
	<td id="content" class="<?php echo ($mandigo_options['sidebar_always_show'] ? 'narrow' : 'wide'); ?>column"<?php if (mandigo_sidebox_conditions($single = true)) { ?> rowspan="2"<?php } ?>>
	<div id="p2main">
		<h2><?php printf( __( 'Tagged: %s', 'p2' ), single_tag_title('', false) ); ?> <a class="rss" href="<?php echo get_tag_feed_link( $tag_obj->term_id ); ?>">RSS</a></h2>
	
		<?php if ( have_posts() ) : ?>
			
			<ul id="postlist">
			<?php while ( have_posts() ) : the_post() ?>
				
				<?php p2_load_entry() // loads entry.php ?>
			
			<?php endwhile; ?>
			</ul>
		
		<?php else : ?>
		
			<div class="no-posts">
			    <h3><?php _e( 'No posts found!', 'p2' ) ?></h3>
			</div>
			
		<?php endif ?>
		
		<div class="navigation">
			<p><?php posts_nav_link( ' | ', __( '&larr;&nbsp;Newer&nbsp;Posts', 'p2' ), __( 'Older&nbsp;Posts&nbsp;&rarr;', 'p2' ) ); ?></p>
		</div>
		
</div><!--main-->
</td>

<?php
	// if we have at least one sidebar to display
	if ($mandigo_options['sidebar_always_show'] && $mandigo_options['sidebar_count']) {
		if (mandigo_sidebox_conditions())
			include (TEMPLATEPATH . '/sidebox.php');
	
		include (TEMPLATEPATH . '/sidebar.php');

		// if this is a 3-column layout
		if ($mandigo_options['layout_width'] == 1024 && $mandigo_options['sidebar_count'] == 2)
			include (TEMPLATEPATH . '/sidebar2.php');
	}

	get_footer();
?>
