<?php 
	global $mandigo_options, $dirs;

	get_header();

	// heading levels for post/page title (h1, h2, div, ...)
	$tag_post_title_single = $mandigo_options['heading_level_post_title_single'];
	$tag_page_title        = $mandigo_options['heading_level_page_title'      ];
?>
	<td id="content" class="<?php echo ($mandigo_options['sidebar_always_show'] ? 'narrow' : 'wide'); ?>column"<?php if (mandigo_sidebox_conditions($single = true)) { ?> rowspan="2"<?php } ?>>
<div id="p2main">
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post() ?>
			
				<div class="singlecontrols">
					<a href="#" id="togglecomments"><?php _e( 'Hide threads', 'p2' ); ?></a>
					&nbsp;|&nbsp;
					<a href="#directions" id="directions-keyboard"><?php  _e( 'Keyboard Shortcuts', 'p2' ); ?></a>
				</div>
		
				<ul id="postlist">
		    		<?php p2_load_entry() // loads entry.php ?>
				</ul>
			
			<?php endwhile; ?>
			
		<?php else : ?>
			
			<ul id="postlist">
				<li class="no-posts">
			    	<h3><?php _e( 'No posts yet!', 'p2' ) ?></h3>
				</li>
			</ul>
			
		<?php endif; ?>

		<div class="navigation">
			<p><?php previous_post_link( '%link', __( '&larr;&nbsp;Older&nbsp;Posts', 'p2' ) ) ?> | <?php next_post_link( '%link', __( 'Newer&nbsp;Posts&nbsp;&rarr;', 'p2' ) ) ?></p>
		</div>

</div><!-- main -->
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
