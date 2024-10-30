<?php
/*
Plugin Name: Lex's Last Update Widget
Plugin URI: http://le-boxon-de-lex.fr
Description: A plug-in to show the date of the last blog update as a widget.
Version: 1.2.0
Author: Lex
Author URI: http://le-boxon-de-lex.fr
Text Domain: lexs-last-update-widget
License: GPL2
*/


/**
 * Store the text domain in a constant.
 */
define( "LEX_LU_TEXT_DOMAIN", "lexs-last-update-widget" );


/**
 * A widget to display the date of the last blog update.
 **/
class Lex_Last_Update_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	function Lex_Last_Update_Widget() {
		$widget_ops = array( 'description' => __( 'A widget to display the date of the last blog update', LEX_LU_TEXT_DOMAIN ));
		$control_ops = array( 'id_base' => 'lex-last-update-widget' );
		$this->WP_Widget( 'lex-last-update-widget', __( 'Last Update', LEX_LU_TEXT_DOMAIN ), $widget_ops, $control_ops );
	}
	
	
	/**
	 * Blog display.
	 */
	function widget( $args, $instance ) {
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance[ 'title' ]);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		// Search criteria
		$criteria = 'created';
		if( isset( $instance[ 'criteria' ]))
			$criteria = $instance[ 'criteria' ];
			
		$post_types = array();
		if( $this->isActivated( $instance, "post" ))
			$post_types[] = "post";
		
		if( $this->isActivated( $instance, "page" ))
			$post_types[] = "page";
		
		if( $this->isActivated( $instance, "revision" ))
			$post_types[] = "revision";
			
		// Perform the search
		$last_time = 0;
		if( count( $post_types ) > 0 ) {
			$allposts = new WP_Query();
			$allposts->query( array( 'post_type' => $post_types, 'orderby' => $criteria, 'order' => 'DESC', 'ignore_sticky_posts' => 1 ));
				
			if( $allposts->have_posts()) {
				$allposts->the_post();
				$modified = strcmp( 'modified', $criteria ) == 0;
				if( $modified )
					$last_time = get_the_modified_time( 'U' );
				
				if( $last_time == 0 )
					$last_time = get_the_time( 'U' );
			}
		}
		
		// Deal with bookmarks
		if( $this->isActivated( $instance, 'bookmarks' )) {
			$bookmarks_time = get_option( "lex_lu_blogroll_date" );
			if( $bookmarks_time && $last_time < $bookmarks_time )
				$last_time = $bookmarks_time;
		}
		
		?>
		
		<ul><?php
			$format = $instance[ 'dateformat' ];
			if( empty( $format ))
				$format = $this->get_localized_format();
					
			echo "<li>" . date_i18n( $format, $last_time ) . "</li>";
		?></ul>
		
	<?php
		echo $after_widget;
	}
	
	
	/**
	 * Settings update.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ]);
		$instance[ 'dateformat' ] = trim( $new_instance[ 'dateformat' ]);
		$instance[ 'watched' ] = $new_instance[ 'watched' ];
		$instance[ 'criteria' ] = trim( $new_instance[ 'criteria' ]);
		return $instance;
	}
	
	
	/**
	 * Admin display.
	 */
	function form( $instance ) {
		$defaults = array( 
					'title' => __( 'Last Update', LEX_LU_TEXT_DOMAIN ), 
					'dateformat' => $this->get_localized_format(),
					'watched' => array( 'post' ),
					'criteria' => 'created'	);
					
		$instance = wp_parse_args((array) $instance, $defaults ); 
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', LEX_LU_TEXT_DOMAIN ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			
			<br /><br />
			<label for="<?php echo $this->get_field_id( 'dateformat' ); ?>"><?php _e( 'Date Format:', LEX_LU_TEXT_DOMAIN ); ?></label>
			<input id="<?php echo $this->get_field_id( 'dateformat' ); ?>" name="<?php echo $this->get_field_name( 'dateformat' ); ?>" value="<?php echo $instance[ 'dateformat' ]; ?>" style="width:100%;" />
			
			<br /><br />
			<label for="<?php echo $this->get_field_id( 'watched' ); ?>[]"><?php _e( 'Watched Elements:', LEX_LU_TEXT_DOMAIN ); ?></label><br />
			<input name="<?php echo $this->get_field_name( 'watched' ); ?>[]" value="post" type="checkbox" <?php if( $this->isActivated( $instance, "post" )) echo 'checked="true"'; ?>> 
			<?php _e( "Posts", LEX_LU_TEXT_DOMAIN ); ?></input><br />
			
			<input name="<?php echo $this->get_field_name( 'watched' ); ?>[]" value="page" type="checkbox" <?php if( $this->isActivated( $instance, "page" )) echo 'checked="true"'; ?>> 
			<?php _e( "Pages", LEX_LU_TEXT_DOMAIN ); ?></input><br />
			
			<input name="<?php echo $this->get_field_name( 'watched' ); ?>[]" value="revision" type="checkbox" <?php if( $this->isActivated( $instance, "revision" )) echo 'checked="true"'; ?>> 
			<?php _e( "Revisions", LEX_LU_TEXT_DOMAIN ); ?></input><br />
			
			<input name="<?php echo $this->get_field_name( 'watched' ); ?>[]" value="bookmarks" type="checkbox" <?php if( $this->isActivated( $instance, "bookmarks" )) echo 'checked="true"'; ?>> 
			<?php _e( "Bookmarks", LEX_LU_TEXT_DOMAIN ); ?></input><br />
			
			<br />
			<label for="<?php echo $this->get_field_id( 'criteria' ); ?>"><?php _e( 'Modification Criteria for Posts and Pages:', LEX_LU_TEXT_DOMAIN ); ?></label>
			<select name="<?php echo $this->get_field_name( 'criteria' ); ?>" style="width:100%;">
				<option value="created" <?php if( strcmp( "created", $instance[ 'criteria' ]) == 0 ) echo 'selected="yes"'; ?>><?php _e( 'Creation', LEX_LU_TEXT_DOMAIN ); ?></option>
				<option value="modified" <?php if( strcmp( "modified", $instance[ 'criteria' ]) == 0 ) echo 'selected="yes"'; ?>><?php _e( 'Modification', LEX_LU_TEXT_DOMAIN ); ?></option>
			</select>
		</p>
	<?php
	}
	
	
	/**
	 * @return the localized format for the date
	 */
	function get_localized_format() {
		return __( "l, F j, Y", LEX_LU_TEXT_DOMAIN );
	}
	
	
	/**
	 * Verifies whether a checkbox is activated or not (in the options).
	 * @param $instance the edited instance
	 * @param $watched_field the field whose activation must be verified
	 * @return true if it is activated, false otherwise
	 */
	function isActivated( $instance, $watched_field ) {
	
		$result = false;
		$watched = $instance[ 'watched' ];
		if( is_array( $watched )) {
			foreach( $watched as $field ) {
				if( strcmp( $field, $watched_field ) == 0 ) {
					$result = true;
					break;
				}
			}
		}
		
		return $result;
	}
}


/**
 * A function to register the widget and load the translations.
 **/
function lex_last_update() {
	register_widget( "Lex_Last_Update_Widget" );
}


/**
 * A function to register the last modification date in the blogroll.
 * @param $link_id the link ID (not used but required by the hook)
 */
function lex_last_update_blogroll_date( $link_id ) {
	update_option( "lex_lu_blogroll_date", time());
}


// Widget initialization
add_action( 'widgets_init', 'lex_last_update' );
load_plugin_textdomain( LEX_LU_TEXT_DOMAIN, false, basename( dirname(__FILE__)) . '/translations/' );


// Update the last modification date in the blogroll
add_action( "add_link", "lex_last_update_blogroll_date" );
add_action( "delete_link", "lex_last_update_blogroll_date" );
add_action( "edit_link", "lex_last_update_blogroll_date" );

?>