P2 Integration with Mandigo Theme
-----
- Make sure you have the site layout set to 1024px or P2 templates will look deformed
- Set the post color to #fff for best effects.
- Move the files from this archive to the Mandigo theme folder
- Remove js/jquery.js from the js folder. This file will conflict with P2 functionality.
- In the Mandigo functions php, find:
	include_once('backend/readme.php');
below that line, add the following:
	include_once (TEMPLATEPATH . '/user-functions.php');
- In header php, find: 
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
Add below:
	<link rel="stylesheet" type="text/css" href="<?php echo $dirs['www']; ?>/p2-style.css" />
- In footer php. find:
<?php
	// HTML Inserts: very end of the page
	echo get_option('mandigo_inserts_veryend');
?>

Add the following above:
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

P2 Mod is licensed under the GNU Public License v2, based on P2 1.1.5 by automattic.