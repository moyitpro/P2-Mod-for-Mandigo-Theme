<?php

add_action( 'init', array('P2Options', 'init') );

class P2Options {
	
	function init() {
		add_action('admin_menu', array('P2Options', 'add_options_page'));
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_style( 'farbtastic' );
	}

	function add_options_page() {
  		add_theme_page( __('P2 Options', 'p2' ), __( 'P2 Options', 'p2' ), 'edit_theme_options', 'p2-options-page', array( 'P2Options', 'page' ) );
	}

	function page() {
		
		register_setting('p2ops', 'prologue_show_titles');
		register_setting('p2ops', 'p2_allow_users_publish');
		register_setting('p2ops', 'p2_prompt_text');
		register_setting('p2ops', 'p2_hide_threads');
		
		$prologue_show_titles_val    = get_option( 'prologue_show_titles' );
		$p2_allow_users_publish_val  = get_option( 'p2_allow_users_publish' ); 
		$p2_prompt_text_val          = get_option( 'p2_prompt_text' );
		$p2_hide_threads    	  	 = get_option( 'p2_hide_threads' );

		if ( esc_attr( $_POST[ 'action' ] ) == 'update' ) {

			$prologue_show_titles_val = intval( $_POST[ 'prologue_show_titles' ] );
			$p2_allow_users_publish_val = intval( $_POST[ 'p2_allow_users_publish' ] );
			$p2_hide_threads = $_POST[ 'p2_hide_threads' ];

			if( esc_attr( $_POST[ 'p2_prompt_text' ] ) != __( "Whatcha' up to?" ) )
				$p2_prompt_text_val = esc_attr( $_POST[ 'p2_prompt_text' ] );

			if( !isset( $_POST[ 'p2_hide_threads' ] ) )
				$p2_hide_threads = false;

			update_option( 'prologue_show_titles', $prologue_show_titles_val );
			update_option( 'p2_allow_users_publish', $p2_allow_users_publish_val );
			update_option( 'p2_prompt_text', $p2_prompt_text_val );
			update_option( 'p2_hide_threads', $p2_hide_threads );
			
		?>
			<div class="updated"><p><strong><?php _e( 'Options saved.', 'p2' ); ?></strong></p></div>
		<?php

    	} ?>

		<div class="wrap">
	    <?php echo "<h2>" . __( 'P2 Options', 'p2' ) . "</h2>"; ?>
	    <h3>To modify Mandigo's appearence, use the Theme Options page instead.</h3>
	
		<form enctype="multipart/form-data" name="form1" method="post" action="<?php echo esc_attr( str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ) ); ?>">

			<h3 style="font-family: georgia, times, serif; font-weight: normal; border-bottom: 1px solid #ddd; padding-bottom: 5px">
				<?php _e( 'Functionality Options', 'p2' ) ?>
			</h3>

			<?php settings_fields('p2ops'); ?>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e( 'Posting Access:', 'p2' ) ?></th>
						<td>
				
						<input id="p2_allow_users_publish" type="checkbox" name="p2_allow_users_publish" <?php if( $p2_allow_users_publish_val == 1 ) echo 'checked="checked"'; ?> value="1" />
			
						<?php if ( defined('IS_WPCOM') && IS_WPCOM ) 
								$msg = 'Allow any WordPress.com member to post'; 
							  else 
							  	$msg = 'Allow any registered member to post'; 
						 ?>
			 
						<label for="p2_allow_users_publish"><?php _e( $msg, 'p2' ); ?></label> 

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Hide Threads:', 'p2' ) ?></th>
						<td>
				
						<input id="p2_hide_threads" type="checkbox" name="p2_hide_threads" <?php if( $p2_hide_threads == 1 ) echo 'checked="checked"'; ?> value="1" />
						<label for="p2_hide_threads"><?php _e( 'Hide comment threads by default', 'p2' ); ?></label> 

						</td>
					</tr>
				</tbody>
			</table>

			<p>&nbsp;
			</p>
			
			<h3 style="font-family: georgia, times, serif; font-weight: normal; border-bottom: 1px solid #ddd; padding-bottom: 5px">
				<?php _e( 'Design Options', 'p2' ) ?>
			</h3>
			
			<table class="form-table">
				<tbody>
										<tr valign="top">
						<th scope="row"><?php _e( 'Post prompt:', 'p2' ) ?></th>
						<td>
							<input id="p2_prompt_text" type="input" name="p2_prompt_text" value="<?php echo ($p2_prompt_text_val == __("Whatcha' up to?") ) ? __("Whatcha' up to?") : stripslashes( $p2_prompt_text_val ); ?>" />
				 			(<?php _e('if empty, defaults to <strong>Whatcha up to?</strong>', 'p2' ); ?>)
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Post Titles:', 'p2' )?></th>
						<td>
							<input id="prologue_show_titles" type="checkbox" name="prologue_show_titles" <?php if( $prologue_show_titles_val != 0 ) echo 'checked="checked"'; ?> value="1" />
							<label for="prologue_show_titles"><?php _e('Display titles', 'p2' ); ?></label> 
						</td>
					</tr>
				</tbody>
			</table>

			<p>
			</p>

			<p class="submit">
				<input type="submit" name="Submit" value="<?php esc_attr_e('Update Options', 'p2' ) ?>" />
			</p>

		</form>
		</div>

<?php
	}
}
