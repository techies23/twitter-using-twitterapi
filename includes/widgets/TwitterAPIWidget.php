<?php

/**
 * Class that extends and registers the Widget
 *
 * @since  1.0
 *
 * @package   widget/TwitterAPIWidget
*/

if( !class_exists('TwitterAPIWidget') ) {

	class TwitterAPIWidget extends WP_Widget{

		public function __construct() {
			$widget_ops = array( 
				'classname' => 'tf-widget-class',
				'description' => 'Show the Twitter Feeds',
				);
			parent::__construct( 'TwitterAPIWidget', 'Twitter Feed Widget', $widget_ops );
		}

		public function widget($args, $instance) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo '<h2 class="widget-title tf-widget-title">' . apply_filters( 'widget_title', $instance['title'] ). '</h2>';

				$user = get_option('tf_username') ? get_option('tf_username') : null;
				if($instance['tf_follow_button'] == 'on') {
					echo "<a class='tf-widget-logo-follow' href='https://twitter.com/intent/follow?screen_name=".$user."&original_referer=".home_url()."' class='tf-logo-popup'>Follow @".$user."</a>";
				}
				echo "<div class='clear'></div>";
			}
			require_once( TF_PATH . 'includes/widgets/output/TwitterAPIOutput.php' );
			echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'tf' );
			$type = ! empty( $instance['type'] ) ? $instance['type'] : __( 'Type', 'tf' );
			$tf_show_profile_thumnails = ! empty( $instance['tf_show_profile_thumnails'] ) ? $instance['tf_show_profile_thumnails'] : false;
			$tf_post_images = ! empty( $instance['tf_post_images'] ) ? $instance['tf_post_images'] : false;
			$tf_widget_height = ! empty( $instance['tf_widget_height'] ) ? $instance['tf_widget_height'] : '600px';
			$tf_scroll_color = ! empty( $instance['tf_scroll_color'] ) ? $instance['tf_scroll_color'] : '#000';
			$tf_scroll_width = ! empty( $instance['tf_scroll_width'] ) ? $instance['tf_scroll_width'] : '2px';
			$tf_enable_touch = ! empty( $instance['tf_enable_touch'] ) ? $instance['tf_enable_touch'] : false;
			$tf_no_of_tweets = ! empty( $instance['tf_no_of_tweets'] ) ? $instance['tf_no_of_tweets'] : '5';
			$tf_follow_button = ! empty( $instance['tf_follow_button'] ) ? $instance['tf_follow_button'] : null;
			$tf_widget_theme = ! empty( $instance['tf_widget_theme'] ) ? $instance['tf_widget_theme'] : null;
			?>
			<div class="tf-feed-settings">
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:' ); ?></label> 
					<select class="widefat" name="<?php echo $this->get_field_name( 'type' ); ?>"> 
						<option <?php echo(esc_attr( $type ) == 'mentions_timeline') ? 'selected': null; ?> value="<?php _e( 'mentions_timeline', 'tf' ); ?>"><?php _e( 'Mentions Timeline' ); ?></option>					
						<option <?php echo(esc_attr( $type ) == 'user_timeline') ? 'selected': null; ?> value="<?php _e( 'user_timeline', 'tf' ); ?>"><?php _e( 'User Timeline' ); ?></option>
						<option <?php echo(esc_attr( $type ) == 'home_timeline') ? 'selected': null; ?> value="<?php _e( 'home_timeline', 'tf' ); ?><?php __( 'Type', 'tf' ); ?>"><?php _e( 'Home Timeline' ); ?></option>
						<option <?php echo(esc_attr( $type ) == 'retweets_own_by_others') ? 'selected': null; ?> value="<?php _e( 'retweets_own_by_others', 'tf' ); ?>"><?php _e( 'Own Retweets' ); ?></option>
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_show_profile_thumnails' ); ?>"><?php _e( 'Enable Profile thumbnail:' ); ?></label> 
					<input class="widefat" <?php echo ($tf_show_profile_thumnails == 'on') ? 'checked' : false; ?> id="<?php echo $this->get_field_id( 'tf_show_profile_thumnails' ); ?>" name="<?php echo $this->get_field_name( 'tf_show_profile_thumnails' ); ?>" type="checkbox" >
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_post_images' ); ?>"><?php _e( 'Enable Post Images:' ); ?></label> 
					<input class="widefat" <?php echo ($tf_post_images == 'on') ? 'checked' : false; ?> id="<?php echo $this->get_field_id( 'tf_post_images' ); ?>" name="<?php echo $this->get_field_name( 'tf_post_images' ); ?>" type="checkbox">
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_widget_height' ); ?>"><?php _e( 'Height:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'tf_widget_height' ); ?>" name="<?php echo $this->get_field_name( 'tf_widget_height' ); ?>" type="text" value="<?php echo esc_attr( $tf_widget_height ); ?>">
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_no_of_tweets' ); ?>"><?php _e( 'Number of Tweets:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'tf_no_of_tweets' ); ?>" name="<?php echo $this->get_field_name( 'tf_no_of_tweets' ); ?>" type="number" value="<?php echo esc_attr( $tf_no_of_tweets ); ?>">
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_follow_button' ); ?>"><?php _e( 'Follow Button:' ); ?></label> 
					<input class="widefat"  <?php echo ($tf_follow_button == 'on') ? 'checked' : false; ?> id="<?php echo $this->get_field_id( 'tf_follow_button' ); ?>" name="<?php echo $this->get_field_name( 'tf_follow_button' ); ?>" type="checkbox">
				</p>
			</div>
			<!--- SCROLLER PART -->
			<div class="tf-feed-scroller">
				<h3><?php _e('Scroller Styles', 'tf'); ?></h3>
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_scroll_color' ); ?>"><?php _e( 'Scroller Color' ); ?></label> 
				</p>
				<input class="widefat tf-color-picker" id="<?php echo $this->get_field_id( 'tf_scroll_color' ); ?>" name="<?php echo $this->get_field_name( 'tf_scroll_color' ); ?>" type="text" value="<?php echo esc_attr( $tf_scroll_color ); ?>">
				<p>
					<label for="<?php echo $this->get_field_id( 'tf_scroll_width' ); ?>"><?php _e( 'Scroller Width' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'tf_scroll_width' ); ?>" name="<?php echo $this->get_field_name( 'tf_scroll_width' ); ?>" type="text" value="<?php echo esc_attr( $tf_scroll_width ); ?>">
				</p>
				<p>
				<label for="<?php echo $this->get_field_id( 'tf_widget_theme' ); ?>"><?php _e( 'Choose Theme:' ); ?></label> 
					<select class="widefat" name="<?php echo $this->get_field_name( 'tf_widget_theme' ); ?>"> 
						<option <?php echo(esc_attr( $tf_widget_theme ) == 'tf_default_widget_theme') ? 'selected': null; ?> value="<?php _e( 'tf_default_widget_theme', 'tf' ); ?>"><?php _e( 'Default Theme' ); ?></option>		
						<option <?php echo(esc_attr( $tf_widget_theme ) == 'tf_block_widget_theme') ? 'selected': null; ?> value="<?php _e( 'tf_block_widget_theme', 'tf' ); ?>"><?php _e( 'Styled Box Theme' ); ?></option>
						<option <?php echo(esc_attr( $tf_widget_theme ) == 'tf_black_widget_theme') ? 'selected': null; ?> value="<?php _e( 'tf_black_widget_theme', 'tf' ); ?>"><?php _e( 'Black Theme' ); ?></option>
						
					</select>
				</p>
			</div>
			<?php 
		}

			/**
			 * Sanitize widget form values as they are saved.
			 *
			 * @see WP_Widget::update()
			 *
			 * @param array $new_instance Values just sent to be saved.
			 * @param array $old_instance Previously saved values from database.
			 *
			 * @return array Updated safe values to be saved.
			 */
			public function update( $new_instance, $old_instance ) {
				$instance = array();
				$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
				$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
				$instance['tf_show_profile_thumnails'] = ( ! empty( $new_instance['tf_show_profile_thumnails'] ) ) ? $new_instance['tf_show_profile_thumnails'] : '';
				$instance['tf_post_images'] = ( ! empty( $new_instance['tf_post_images'] ) ) ? $new_instance['tf_post_images'] : '';
				$instance['tf_widget_height'] = ( ! empty( $new_instance['tf_widget_height'] ) ) ? $new_instance['tf_widget_height'] : '';
				$instance['tf_scroll_color'] = ( ! empty( $new_instance['tf_scroll_color'] ) ) ? $new_instance['tf_scroll_color'] : '';
				$instance['tf_scroll_width'] = ( ! empty( $new_instance['tf_scroll_width'] ) ) ? $new_instance['tf_scroll_width'] : '';
				$instance['tf_enable_touch'] = ( ! empty( $new_instance['tf_enable_touch'] ) ) ? $new_instance['tf_enable_touch'] : '';
				$instance['tf_no_of_tweets'] = ( ! empty( $new_instance['tf_no_of_tweets'] ) ) ? $new_instance['tf_no_of_tweets'] : '';
				$instance['tf_follow_button'] = ( ! empty( $new_instance['tf_follow_button'] ) ) ? $new_instance['tf_follow_button'] : '';
				$instance['tf_widget_theme'] = ( ! empty( $new_instance['tf_widget_theme'] ) ) ? strip_tags( $new_instance['tf_widget_theme'] ) : '';

				$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
				foreach( $file_arr as $single ) {
					$file =  TF_JSON_PATH . $single;
					if( file_exists($file) ) {
						unlink( TF_JSON_PATH . $single );
					}
				}
				return $instance;
			}
		}
	}