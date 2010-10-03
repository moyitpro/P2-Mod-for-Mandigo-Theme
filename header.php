<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php
	global $mandigo_options, $mandigo_version, $schemes, $dirs;
	$title = '';

	// get the appropriate title scheme depending on the type of page
	if (is_single())                             $title = $mandigo_options['title_scheme_single'];
	elseif (is_page())                           $title = $mandigo_options['title_scheme_page'];
	elseif (is_archive()) {
		if (is_day() || is_month() || is_year()) $title = $mandigo_options['title_scheme_date'];
		else                                     $title = $mandigo_options['title_scheme_category'];
	}
	elseif (is_search())                         $title = $mandigo_options['title_scheme_search'];
	else                                         $title = $mandigo_options['title_scheme_index'];

	// proceed with tag substitution
	$title = str_replace(
		array(
			'%blogname%',
			'%tagline%',
			'%post%',
			'%search%',
		),
		array(
			get_bloginfo('name'),
			get_bloginfo('description'),
			get_the_title(),
			$s,
		),
		$title
	);

	// category tag substitution
	if (single_cat_title('',false))
		$title = str_replace('%category%', single_cat_title('', false), $title);
	else {
		$categories = str_replace('%category%', get_the_category_list(', '), $title);
		$title = preg_replace('/<[^>]+>/', '', $categories);
	}

	// date tag substitution
	if     (is_day()  ) $title = str_replace('%date%', get_the_time(__('l, F jS, Y','mandigo')), $title);
	elseif (is_month()) $title = str_replace('%date%', get_the_time(__('F, Y','mandigo'))      , $title);
	elseif (is_year() ) $title = str_replace('%date%', get_the_time('Y')                       , $title);

	// output the result
	echo $title;
	
?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="theme"     content="Mandigo <?php echo $mandigo_version; ?>" />

<?php
	// if the "random scheme" option is enabled
	if ($mandigo_options['scheme_randomize']) {
		$mandigo_options['scheme'] = $schemes[array_rand($schemes, 1)];
		update_option('mandigo_options', $mandigo_options);
		$dirs['loc']['scheme'] = $dirs['loc']['schemes'] . $mandigo_options['scheme'] .'/';
		$dirs['www']['scheme'] = $dirs['www']['schemes'] . $mandigo_options['scheme'] .'/';
	}



	// custom header images magic!
	// recursivey find image files named mandigo_header_* in the user's uploaded files folder
	$user_headers = @array_filter(
		m_find_in_dir(
			ABSPATH . get_option('upload_path'),
			'mandigo_header(_.*)?\.(jpe?g|png|gif|bmp)$'
		),
		create_function('$filename', 'return !(preg_match("/-[0-9]+x[0-9]+/", $filename));')
	);
	
	if ($user_headers) {
		// if we're in a category and there's a .jpg file named after it in the headers/ folder
		if (is_category()) {
			$category = array_shift(get_the_category());
			foreach ($user_headers as $file) {
				if (strstr($file, 'mandigo_header_cat_'. $category->cat_ID .'.')) {
					$user_header = $file;
				}
			}
		}

		// if one of the files we found is named after the current post id
		foreach ($user_headers as $file) {
			if (strstr($file, 'mandigo_header_'. $post->ID .'.')) {
				$user_header = $file;
			}
		}
		
		// if we didn't find one but the "random headers" option is enabled
		if (!$user_header && $mandigo_options['header_random_image']) {
			shuffle($user_headers);
			$user_header = array_shift($user_headers);
		}
		
		// if we found a header to display at one step or another,
		if ($user_header) {
			// translate its address
			$user_header = str_replace(
				ABSPATH,
				get_bloginfo('wpurl') .'/',
				$user_header
			);
			// and add it to last minuste css rules
			$no_default_header_img = 1;
			$lastminutecss[] = sprintf(
				'  #headerimg { background: url(%s) bottom center no-repeat; }',
				$user_header
			);
		}
	}
	
	// if we didn't find a custom header in the user's uploaded files
	if (!$no_default_header_img) {
		// if we're in a category and there's a .jpg file named after it in the headers/ folder
		if (is_category()) {
			$category = array_shift(get_the_category());
			if (file_exists($dirs['loc']['headers'] . 'cat_'. $category->cat_ID .'.jpg')) {
				$no_default_header_img = 1;
				$lastminutecss[] = sprintf(
					'  #headerimg { background: url(%s.jpg) bottom center no-repeat; }',
					$dirs['www']['headers'] . 'cat_'. $category->cat_ID
				);
			}
		}

		// if there's a .jpg file named after the current post id in the headers/ folder
		elseif (file_exists($dirs['loc']['headers'] . $post->ID .'.jpg')) {
			$no_default_header_img = 1;
			$lastminutecss[] = sprintf(
				'  #headerimg { background: url(%s.jpg) bottom center no-repeat; }',
				$dirs['www']['headers'] . $post->ID
			);
		}
			
		// if we didn't find one but the "random headers" option is enabled
		if (!$no_default_header_img && $mandigo_options['header_random_image']) {
			// get a list of images in headers/
			$headersdir = opendir($dirs['loc']['headers']);
			while (false !== ($file = readdir($headersdir))) {
				if (preg_match('/\.(?:jpe?g|png|gif|bmp)$/i', $file))
					$headers[] = $file;
			}
	
			// if we have found at least one
			if (count($headers)) {
				$no_default_header_img = 1;
				$lastminutecss[] = sprintf(
					'  #headerimg {   background: url(%s) bottom center no-repeat; }',
					str_replace(
						' ',
						'%20',
						$dirs['www']['headers'] . $headers[array_rand($headers, 1)]
					)
				);
			}
		}
	}

	
	
	
	
	// Windows Live Writer needs this when it updates blog settings for image placement to work
	if (stristr($_SERVER['HTTP_USER_AGENT'], 'Windows Live Writer'))
		$lastminutecss[] = '  .entry img { float: none; }';





	// if favicon is present
	if (file_exists($dirs['loc']['theme'] .'favicon.ico')) {
		$favicon = $dirs['www']['theme'] .'favicon.ico';
?>
<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
<?php
	}


	
	// image & object max-width rules
	$maxwidth = $mandigo_options['layout_width']
		- 144
		- (
			$mandigo_options['sidebar_count'] > 0 && ($mandigo_options['sidebar_always_show'] || (!is_single() && !is_page()))
			? (
				$mandigo_options['sidebar_1_width']+12
				+
				(
					$mandigo_options['layout_width'] == 1024 && $mandigo_options['sidebar_count'] == 2
					? $mandigo_options['sidebar_2_width']+12
					: 0
				)
			)
			: 0
		)
	;
	$lastminutecss[] = sprintf(
		'  .entry img, .entry embed, .entry object { max-width: %spx; width: expression(this.clientWidth > %s ? %s : true); }',
		$maxwidth, $maxwidth, $maxwidth
	);
	$lastminutecss[] = '  .entry img { height: auto; }';
	
	
	
	// whether to load all rules in style.css.php to defeat cache problems
	if ($mandigo_options['fully_dynamic_stylesheet']) {
?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>.php<?php echo ($no_default_header_img ? '?no_default_header_img' : '') ?>" type="text/css" media="screen" />
<?php
	}
	// or to load static rules from style.css and dynamic ones from style.css.php
	else {
?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/p2-style.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/p2-buttons.css" />
<style type="text/css">
<?php
	include 'style.css.php';
?>
</style>
<?php
	}
	
	
	
?>
<link rel="stylesheet" href="<?php echo $dirs['www']['scheme']; ?>scheme.css" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
	// threaded comments js library for wp 2.7+
	if (is_single()) wp_enqueue_script('comment-reply');
	
	// main WordPress headers
	wp_head();
?>

<script type="text/javascript" src="<?php echo $dirs['www']['js']; ?>jquery.js"></script>
<script type="text/javascript" src="<?php echo $dirs['www']['js']; ?>jquery.ifixpng.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>

<?php
	// last minute css rules, if any
	if (count($lastminutecss)) {
?>
<style type="text/css">
<?php
	echo implode("\n", $lastminutecss);
?>
</style>

<?php
	}



	// HTML Inserts: head
	echo get_option('mandigo_inserts_header');
?>
</head>

<?php
	// HTML Inserts: body tag
	$bodytag = get_option('mandigo_inserts_body');
	// if we have a custom body tag set
	if (preg_match('/<body/i', $bodytag))
		echo $bodytag;
	else
		echo "<body>\n";



	// heading levels for blog name & description (h1, h2, div, ...)
	$tag_blogname = $mandigo_options['heading_level_blogname'];
	$tag_blogdesc = $mandigo_options['heading_level_blogdesc'];
?>

<div id="page">

<div id="header" class="png">
	<div id="headerimg">
<?php
	// unless we chose to hide the blogname
	if (!$mandigo_options['header_blogname_hide']) {
?>
		<<?php echo $tag_blogname; ?> class="blogname" id="blogname"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></<?php echo $tag_blogname; ?>>
<?php
	}



	// if blog description is set and unless we chose to hide it
	if (!$mandigo_options['header_blogdesc_hide'] && get_bloginfo('description')) {
?>
		<<?php echo $tag_blogdesc; ?> class="blogdesc" id="blogdesc"><?php bloginfo('description'); ?></<?php echo $tag_blogdesc; ?>>
<?php
	}



	// this is the top navigation:
	// the first <li> tag is the link to the homepage
?>

		<ul class="pages png<?php echo ($mandigo_options['header_navigation_stripe'] ? ' head_overlay' : ''); ?>">
			<li class="page_item"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home', 'mandigo'); ?></a></li>
<?php
	// wordpress pages, minus the ones excluded in the theme options
	wp_list_pages(
		array(
			'sort_column' => 'menu_order',
			'depth'       => ($mandigo_options['header_navigation_no_submenus'] ? 1 : 0),
			'title_li'    => '',
			'exclude'     => @implode(',', $mandigo_options['header_navigation_exclude_pages']),
		)
	);
	// if you want to add custom static links at the end, use something similar to the following
	// <li class="page_item"><a href="http://whatever.com/">Go somewhere</a></li>
	// and put it right before the closing </ul>
?>
		</ul>
	
	</div>
	
</div>

<div id="main" class="png">
<?php
	// HTML Inserts: top of page
	echo get_option('mandigo_inserts_top');
?>
<table>
<tr>
