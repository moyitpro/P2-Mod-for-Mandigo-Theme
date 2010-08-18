<?php 
	global $mandigo_options, $dirs;

	get_header();

	// heading levels for post/page title (h1, h2, div, ...)
	$tag_post_title_single = $mandigo_options['heading_level_post_title_single'];
	$tag_page_title        = $mandigo_options['heading_level_page_title'      ];
?>
	<td id="content" class="<?php echo ($mandigo_options['sidebar_always_show'] ? 'narrow' : 'wide'); ?>column"<?php if (mandigo_sidebox_conditions($single = true)) { ?> rowspan="2"<?php } ?>>
	<?php if ( p2_user_can_post() && !is_archive() ) : ?>
		<?php locate_template( array( 'post-form.php' ), true ) ?>
	<?php endif; ?>
	<div id="p2main">
		<h2>
			<?php if ( is_home() or is_front_page() ) : ?>
		
				<?php _e( 'Recent Updates' , 'p2' ) ?> <?php if ( p2_get_page_number() > 1 ) printf( __( 'Page %s', 'p2' ), p2_get_page_number() ); ?>
				<a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a>

			<?php elseif ( is_author() ) : ?>
				
				<?php printf( _x( 'Updates from %s', 'Author name', 'p2' ), p2_get_archive_author() ) ?>
				<a class="rss" href="<?php p2_author_feed_link() ?>">RSS</a>
				
			<?php elseif ( is_taxonomy( 'mentions' ) ) : ?>

				<?php printf( _x( 'Posts Mentioning %s', 'Author name', 'p2' ), p2_get_mention_name() ) ?>
				<a class="rss" href="<?php p2_author_feed_link() ?>">RSS</a>
		
			<?php else : ?>
		
				<?php printf( _x( 'Updates from %s', 'Month name', 'p2' ), get_the_time( 'F, Y' ) ); ?>
		
			<?php endif; ?>
	
			<span class="controls">
				<a href="#" id="togglecomments"> <?php _e( 'Toggle Comment Threads', 'p2' ); ?></a> | <a href="#directions" id="directions-keyboard"><?php _e( 'Keyboard Shortcuts', 'p2' ); ?></a>
			</span>
		</h2>
	
		<ul id="postlist">
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post() ?>
	    		<?php p2_load_entry() // loads entry.php ?>
			<?php endwhile; ?>

		<?php else : ?>
			
			<li class="no-posts">
		    	<h3><?php _e( 'No posts yet!', 'p2' ) ?></h3>
			</li>
			
		<?php endif; ?>
		</ul>
		
		<div class="navigation">
			<p><?php posts_nav_link( ' | ', __( '&larr;&nbsp;Newer&nbsp;Posts', 'p2' ), __( 'Older&nbsp;Posts&nbsp;&rarr;', 'p2' ) ); ?></p>
		</div>
	
	</div> <!-- main -->

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
