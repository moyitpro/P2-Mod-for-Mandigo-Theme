<?php
	$theme_data      = get_theme('Mandigo');
	$mandigo_version = $theme_data['Version'];
	
	$mandigo_options = get_option('mandigo_options');
	if (!$mandigo_options || $mandigo_version != $mandigo_options['version']) {
		include_once('backend/upgrade.php');
		mandigo_upgrade($from = $mandigo_options['version'], $to = $mandigo_version);
	}



	// this loads the .mo translation file defined in wp-config.php
	load_theme_textdomain('mandigo');



	// we are going to hold a list of both local ('loc') and remote paths ('www'), which we will need throughout the theme
	$dirs = array();
	$dirs['loc']['theme'] = TEMPLATEPATH .'/';
	$dirs['www']['theme'] = get_bloginfo('template_directory') .'/';

	foreach (array('loc', 'www') as $i) {
		foreach(
			array(
				'images',
				'js',
				'schemes',
				'images/patterns',
				'images/headers',
				'images/icons',
				'backend',
			) as $j
		) {
			$dirs[$i][preg_replace('/.+\//', '', $j)] = $dirs[$i]['theme'] . $j .'/';
		}
	}



	// go through the images/schemes/ folder and build a list of installed schemes
	$schemes = array();
	if (($dir = @opendir($dirs['loc']['schemes'])) !== false) {
		while (($node = readdir($dir)) !== false) {
			if (!preg_match('/^\.{1,2}$/', $node) && file_exists($dirs['loc']['schemes'] ."$node/scheme.css"))
				$schemes[] = $node;
		}
	}
	sort($schemes);



	// some global vars
	$ie      = preg_match("/MSIE [4-6]/", $_SERVER['HTTP_USER_AGENT']);
	$ie7     = preg_match("/MSIE 7/",     $_SERVER['HTTP_USER_AGENT']);
	$wpmu    = function_exists('is_site_admin');



	// some options may have no value, but we can't live without these ones
	$old_options = $mandigo_options;
	// make sure a scheme is selected, and that it's part of the pool of available schemes
	if (!$mandigo_options['scheme'] || !array_search($mandigo_options['scheme'], $schemes))
		$mandigo_options['scheme'] = $schemes[0];

	if (!$mandigo_options['background_color'])           $mandigo_options['background_color']            = '#44484F';
	if (!$mandigo_options['background_pattern_repeat'])  $mandigo_options['background_pattern_repeat']   = 'repeat';
	if (!$mandigo_options['trackbacks_position'])        $mandigo_options['trackbacks_position']         = 'along';
	if (!$mandigo_options['header_navigation_position']) $mandigo_options['header_navigation_position']  = 'right';
	if (!$mandigo_options['date_format'])                $mandigo_options['date_format']                 = 'MdY';
	if (!$mandigo_options['font_family'])                $mandigo_options['font_family']                 = 'sans-serif';
	// colors
	if (!$mandigo_options['color_post_background'])      $mandigo_options['color_post_background']       = '#FAFAFA';
	if (!$mandigo_options['color_post_border'])          $mandigo_options['color_post_border']           = '#EEEEEE';
	if (!$mandigo_options['color_sidebar_background'])   $mandigo_options['color_sidebar_background']    = '#EEEEEE';
	if (!$mandigo_options['color_sidebar_border'])       $mandigo_options['color_sidebar_border']        = '#DDDDDD';
	// SEO title schemes
	if (!$mandigo_options['title_scheme_index'])         $mandigo_options['title_scheme_index']          = '%blogname% - %tagline%';
	if (!$mandigo_options['title_scheme_single'])        $mandigo_options['title_scheme_single']         = '%blogname% &raquo; %post%';
	if (!$mandigo_options['title_scheme_page'])          $mandigo_options['title_scheme_page']           = '%blogname% &raquo; %post%';
	if (!$mandigo_options['title_scheme_category'])      $mandigo_options['title_scheme_category']       = '%blogname% &raquo; Archive for %category%';
	if (!$mandigo_options['title_scheme_date'])          $mandigo_options['title_scheme_date']           = '%blogname% &raquo; Archive for %date%';
	if (!$mandigo_options['title_scheme_search'])        $mandigo_options['title_scheme_search']         = '%blogname% &raquo; Search Results for &quot;%search%&quot;';
	// SEO heading levels
	if (!$mandigo_options['heading_level_blogname'])            $mandigo_options['heading_level_blogname']            = 'h1';
	if (!$mandigo_options['heading_level_blogdesc'])            $mandigo_options['heading_level_blogdesc']            = 'h6';
	if (!$mandigo_options['heading_level_page_title'])          $mandigo_options['heading_level_page_title']          = 'h2';
	if (!$mandigo_options['heading_level_post_title_multi'])    $mandigo_options['heading_level_post_title_multi']    = 'h3';
	if (!$mandigo_options['heading_level_post_title_single'])   $mandigo_options['heading_level_post_title_single']   = 'h2';
	if (!$mandigo_options['heading_level_widget_title'])        $mandigo_options['heading_level_widget_title']        = 'h4';
	// submenu animation speed
	if (!$mandigo_options['header_navigation_speed_appear'])    $mandigo_options['header_navigation_speed_appear']    = 'fast';
	if (!$mandigo_options['header_navigation_speed_disappear']) $mandigo_options['header_navigation_speed_disappear'] = 'slow';
	// sidebar width
	$mandigo_options['sidebar_1_width'] = intval($mandigo_options['sidebar_1_width']);
	$mandigo_options['sidebar_2_width'] = intval($mandigo_options['sidebar_2_width']);
	if (!is_int($mandigo_options['sidebar_1_width']) || !$mandigo_options['sidebar_1_width']) $mandigo_options['sidebar_1_width'] = 210;
	if (!is_int($mandigo_options['sidebar_2_width']) || !$mandigo_options['sidebar_2_width']) $mandigo_options['sidebar_2_width'] = 210;

	// if we have reset some options, save changes
	if ($old_options != $mandigo_options)
		update_option('mandigo_options', $mandigo_options);

	// set some defaults for the theme viewer at wordpress.org
	if (preg_match('/wp-themes.com|wordpress.org/', get_bloginfo('wpurl'))) {
		$old_options = $mandigo_options;
		$mandigo_options['layout_width'] = 1024;
		$mandigo_options['background_pattern_file']     = 'gr.png';
		$mandigo_options['background_pattern_repeat']   = 'repeat-x';
		$mandigo_options['background_pattern_position'] = 'top left';
		if ($old_options != $mandigo_options)
			update_option('mandigo_options', $mandigo_options);
	}

	// now that we have a (valid) current scheme, set its local and remote locations
	$dirs['loc']['scheme'] = $dirs['loc']['schemes'] . $mandigo_options['scheme'] .'/';
	$dirs['www']['scheme'] = $dirs['www']['schemes'] . $mandigo_options['scheme'] .'/';



	// the following two functions search for files matching a regex pattern, recursively
	// based on http://www.php.net/function.opendir#78262
	function m_find_in_dir($root, $pattern) {
		$result = array();
		if (false === m_find_in_dir_i__($root, $pattern, $result))
			return false;
		return $result;
	}
	function m_find_in_dir_i__($root, $pattern, &$result ) {
		$dh = @opendir( $root );
		if (false === $dh)
			return false;
		while ($file = readdir($dh)) {
			if("." == $file || ".." == $file)
				continue;
			if(false !== @eregi($pattern, "{$root}/{$file}"))
				$result[] = "{$root}/{$file}";
			if(is_dir("{$root}/{$file}"))
				m_find_in_dir_i__("{$root}/{$file}", $pattern, $result);
		}
		closedir($dh);
		return true;
	}



	// this function will build links to author archives for us
	function mandigo_author_link($author_id, $author_nicename) {
		// the get_author_posts_url() function is not defined in some translated versions of WP
		// so we double check it exists
		if (function_exists('get_author_posts_url')) {
			return sprintf(
				'<a href="%s" title="%s">%s</a>',
				get_author_posts_url($author_id),
				sprintf(
					__("Posts by %s"),
					attribute_escape($author_nicename)
				),
				$author_nicename
			);
		}
		return $author_nicename;
	}



	// this function builds the 'date stamps'
	function mandigo_date_stamp($date) {
		global $mandigo_options;
		
		// if date position is not set to 'datestamp' or undefined (cause it's a default), stop here
		if ($mandigo_options['date_position'] && $mandigo_options['date_position'] != 'datestamp')
			return;
		
		// split the supplied argument into (year, month_name, month_number, day)
		list($y, $mn, $m, $d) = explode('|', $date);
		// remove the space in japanese month names (hi Atsushi!)
		if (preg_match('/^ja_?/', WPLANG)) {
			$mn = str_replace(' ', '', $mn);
		}
		
		switch ($mandigo_options['date_format']) {
			case 'MdY':
				$l = array($mn, $d, $y);
				$x = array(1, 0, 0);
				break;
			case 'dmY':
				$l = array($d,  $m, $y);
				$x = array(1, 0, 0);
				break;
		}
		
		echo '
						<div class="datestamp">
							<div>
								<span class="cal1'. ($x[0] ? " cal1x" : "") .'">'. $l[0] .'</span>
								<span class="cal2'. ($x[1] ? " cal2x" : "") .'">'. $l[1] .'</span>
								<span class="cal3'. ($x[2] ? " cal3x" : "") .'">'. $l[2] .'</span>
							</div>
						</div>
		';
	}



	// this one tells if the sidebox may be displayed
	function mandigo_sidebox_conditions($single = false) {
		global $mandigo_options;
		return (
			   $mandigo_options['sidebar_count']
			&& $mandigo_options['layout_width'] == 1024
			&& $mandigo_options['sidebar_count'] == 2
			&& $mandigo_options['sidebar_1_position'] == 'right'
			&& $mandigo_options['sidebar_1_position'] == $mandigo_options['sidebar_2_position']
			&& ($single ? $mandigo_options['sidebar_always_show'] : true)
			? true
			: false
		);
	}
	


	// this one adds drop caps the the_content
	function mandigo_drop_caps($data) {
		if (preg_match ('/^\s*</', $data)) {
			return preg_replace('/^\s*(<[^>]+>[\s\r\n]*)*([a-z])/i', '$1<span class="dropcap">$2</span>', $data);
		}
		return preg_replace('/^\s*([a-z])/i', '<span class="dropcap">$1</span>', $data);
	}
	if ($mandigo_options['drop_caps']) {
		add_filter('the_content', 'mandigo_drop_caps');
	}

	
	
	include_once('backend/widgets.php');
	include_once('backend/theme_options.php');
	include_once('backend/html_inserts.php');
	include_once('backend/readme.php');
	include_once (TEMPLATEPATH . '/user-functions.php');
	
?>
