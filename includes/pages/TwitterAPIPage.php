<?php
/**
 * Class that controls the Settings Page in Admin Panel
 *
 * @since  1.0
 * @author  Deepen
 *
 * @package  pages/TwitterAPIPage
 */

if( !class_exists('TwitterAPIPage') ) {

	class TwitterAPIPage {

		public function __construct() {
			add_action('admin_menu', array($this, 'tf_create_pages') );
			add_action('wp_ajax_tf_delete_json', array($this, 'tf_delete_json'));
			add_action('wp_ajax_tf_flush_keys', array($this, 'tf_flush_keys'));
		}

		/**
		 * Creating Pages
		 * @since 1.0
		 * @author  Deepen
		 */
		public function tf_create_pages() {
			add_options_page( 'Twitter', 'Twitter Settings', 'manage_options', TF_PLUGIN_SLUG, array($this, 'tf_twitter_settings') );
		}
		
		/**
		 * Settings Content
		 * @since 1.0
		 * @author  Deepen
		 */
		public function tf_twitter_settings() {
			?>
			<h2><?php _e('Twitter API Settings', 'tf'); ?></h2>
			<?php
			if( isset($_REQUEST['publish_settings_tf']) ) {
				if( !current_user_can( 'manage_options' ) ) {
					return;
				}

				if( !wp_verify_nonce( $_REQUEST['settings_wpnonce'], 'tf_settings' ) ) {
					return;
				}

				if( $_REQUEST['publish_settings_tf'] ) {
					//Necessary Settings
					$tf_username = sanitize_text_field( $_REQUEST['tf_username'] );
					$tf_oauth_access_token = sanitize_text_field( $_REQUEST['tf_oauth_access_token'] );
					$tf_access_secret_token = sanitize_text_field( $_REQUEST['tf_access_secret_token'] );
					$tf_consumer_key = sanitize_text_field( $_REQUEST['tf_consumer_key'] );
					$tf_consumer_secret_key = sanitize_text_field( $_REQUEST['tf_consumer_secret_key'] );

					//Basic Settings
					$tf_feeds_count = sanitize_text_field( (intval( $_REQUEST['tf_feeds_count'])) );
					$tf_followers = isset($_REQUEST['tf_followers']);
					$tf_following = isset($_REQUEST['tf_following']);
					$tf_exclude_feeds_from_timeline = isset($_REQUEST['tf_exclude_feeds_from_timeline']);
					$tf_profile_thumbnail = isset($_REQUEST['tf_profile_thumbnail']);
					$tf_post_thumbmnails = isset($_REQUEST['tf_post_thumbmnails']);
					$tf_retweet_icon = isset($_REQUEST['tf_retweet_icon']);
					$tf_theme = isset($_REQUEST['tf_theme']);
					$tf_follow_btn = isset($_REQUEST['tf_follow_btn']);
					
					$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
					foreach( $file_arr as $single ) {
						$file =  TF_JSON_PATH . $single;
						if( file_exists($file) ) {
							unlink( TF_JSON_PATH . $single );
						}
					}

					if( $tf_username == null || $tf_oauth_access_token == null || $tf_access_secret_token == null || $tf_consumer_key == null || $tf_consumer_secret_key == null ) {
						?>
						<div class="wrap">
							<div class="notice notice-error"><p><strong><?php _e('Awww !! We hate this red bar. Perhaps its causing because one of the necessary field is blank. :)', 'tf'); ?></p></strong></div>
						</div>
						<?php
					} else {
						if(isset($_REQUEST['tf_action_type'])) {
							update_option( 'tf_username', $tf_username );
							update_option( 'tf_oauth_access_token', $tf_oauth_access_token );
							update_option( 'tf_access_secret_token', $tf_access_secret_token );
							update_option( 'tf_consumer_key', $tf_consumer_key );
							update_option( 'tf_consumer_secret_key', $tf_consumer_secret_key );
							update_option( 'tf_feeds_count', $tf_feeds_count );
							update_option( 'tf_followers', $tf_followers );
							update_option( 'tf_following', $tf_following );
							update_option( 'tf_exclude_feeds_from_timeline', $tf_exclude_feeds_from_timeline );
							update_option( 'tf_profile_thumbnail', $tf_profile_thumbnail );
							update_option( 'tf_post_thumbmnails', $tf_post_thumbmnails );
							update_option( 'tf_theme', $tf_theme );
							update_option( 'tf_retweet_icon', $tf_retweet_icon );
							update_option( 'tf_follow_btn', $tf_follow_btn );

							$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
							foreach( $file_arr as $single ) {
								$file =  TF_JSON_PATH . $single;
								if( file_exists($file) ) {
									unlink( TF_JSON_PATH . $single );
								}
							}
							?>
							<div class="wrap">
								<div class="notice notice-warning notice-custom-tf is-dismissible">
									<p><?php _e( '<strong>Successfully Updated. Clear the Twitter Cache for fetching new data. <a id="tf-flush-cache" href="javascript:void(0);">Clear</a></strong>', 'tf' ); ?></p>
								</div>
							</div>
							<?php
						} else {
							add_option( 'tf_username', $tf_username );
							add_option( 'tf_oauth_access_token', $tf_oauth_access_token );
							add_option( 'tf_access_secret_token', $tf_access_secret_token );
							add_option( 'tf_consumer_key', $tf_consumer_key );
							add_option( 'tf_consumer_secret_key', $tf_consumer_secret_key );
							add_option( 'tf_feeds_count', $tf_feeds_count );
							add_option( 'tf_followers', $tf_followers );
							add_option( 'tf_following', $tf_following );
							add_option( 'tf_exclude_feeds_from_timeline', $tf_exclude_feeds_from_timeline );
							add_option( 'tf_profile_thumbnail', $tf_profile_thumbnail );
							add_option( 'tf_post_thumbmnails', $tf_post_thumbmnails );
							add_option( 'tf_theme', $tf_theme );
							add_option( 'tf_retweet_icon', $tf_retweet_icon );
							add_option( 'tf_follow_btn', $tf_follow_btn );

							$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
							foreach( $file_arr as $single ) {
								$file =  TF_JSON_PATH . $single;
								if( file_exists($file) ) {
									unlink( TF_JSON_PATH . $single );
								}
							}
							?>
							<div class="wrap">
								<div class="notice notice-success notice-custom-tf is-dismissible">
									<p><?php _e( 'Successfully Added !', 'tf' ); ?></p>
								</div>
							</div>
							<?php
						}
					}
				}
			}

			require_once( TF_PATH . 'includes/pages/html/tf_settings.php' );
		}

		/**
		 * Ajax Calls for the DELETING Event of Flushing JSON Data
		 * @since 1.0
		 * * @author  Deepen
		 */
		public function tf_delete_json() {
			$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
			foreach( $file_arr as $single ) {
				$file =  TF_JSON_PATH . $single;
				if( file_exists($file) ) {
					unlink( TF_JSON_PATH . $single );
				}
			}
			wp_die();
		}

		/**
		 * Ajax Call for Flushing the Keys
		 * @since 1.0
		 * @author  Deepen
		 */
		public function tf_flush_keys() {
			delete_option( 'tf_consumer_secret_key' );
			delete_option( 'tf_consumer_key' );
			delete_option( 'tf_access_secret_token' );
			delete_option( 'tf_oauth_access_token' );
			delete_option( 'tf_username' );

			$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json', 'twitter-single-tweets.json' );
			foreach( $file_arr as $single ) {
				$file =  TF_JSON_PATH . $single;
				if( file_exists($file) ) {
					unlink( TF_JSON_PATH . $single );
				}
			}
			wp_die();
		}

	}


	new TwitterAPIPage();
}

?>