<?php
	global $mandigo_options, $dirs;

	get_header();

	// heading level for page title (h1, h2, div, ...)
	$tag_post_title_single = $mandigo_options['heading_level_post_title_single'];
?>
	<td id="content" class="<?php echo ($mandigo_options['sidebar_always_show'] ? 'narrow' : 'wide'); ?>column"<?php if (mandigo_sidebox_conditions($single = true)) { ?> rowspan="2"<?php } ?>>
<div id="p2main">
<h2><?php the_title(); ?></h2>

<ul id="postlist">
<?php if ( have_posts() ) : ?>
	
	<?php while ( have_posts() ) : the_post() ?>
		<?php p2_load_entry() // loads entry.php ?>
	<?php endwhile; ?>

<?php endif; ?>
</ul></div><!--main-->
	</td>

<?php
	// if we have at least one sidebar to display
	if ($mandigo_options['sidebar_always_show'] && $mandigo_options['sidebar_count']) {
		if (mandigo_sidebox_conditions($single = true))
			include (TEMPLATEPATH . '/sidebox.php');
	
		include (TEMPLATEPATH . '/sidebar.php');

		// if this is a 3-column layout
		if ($mandigo_options['layout_width'] == 1024 && $mandigo_options['sidebar_count'] == 2)
			include (TEMPLATEPATH . '/sidebar2.php');
	}

	get_footer();
?>
