<?php
/**
 * Fire During the Activation of the Plugin
 *
 * @since 1.0
 * @author Deepen
 *
 * @package  activators/Tf_Activator
 */

if( !class_exists('Tf_Activator') ) {
	class Tf_Activator {
		public static function activate() {
			global $wp_version;

			$min_wp_version = '4.4.2';
			$exit_msg = sprintf( __( TF_PLUGIN_NAME .' requires %s or newer.', TF_PLUGIN_SLUG ), $min_wp_version );
			if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
				exit( $exit_msg );
			}

			if( version_compare(PHP_VERSION, TF_REQUIRED_PHP_VERSION, "<") ) {
				$exit_msg = '<div class="error"><h3>' . __( 'Warning! It is not possible to activate this plugin as it requires above PHP 5.4 and on this server the PHP version installed is: ')	. '<b>'.PHP_VERSION.'</b></h3><p>' . __( 'For security reasons we <b>suggest</b> that you contact your hosting provider and ask to update your PHP to latest stable version.' ). '</p><p>' . __( 'If they refuse for whatever reason we suggest you to <b>change provider as soon as possible</b>.' ). '</p></div>';
				exit( $exit_msg );
			}
		//Default Theme Set
			add_option( 'tf_theme', 1 );
		//Set Retweet Icon Enabled
			add_option( 'tf_retweet_icon', 1 );
		//Settings Profile thumnail Image
			add_option( 'tf_profile_thumbnail', 1 );
		//Setting the Post thumnails
			add_option( 'tf_post_thumbmnails', 1 );
		//Settings the exclude Parameter
			add_option( 'tf_exclude_feeds_from_timeline', 1 );
		//Setting the Follow button on
			add_option('tf_follow_btn', 1);

			$file_arr = array('twitter-mentions.json', 'twitter-user.json', 'twitter-home.json', 'twitter-retweets.json' );
			foreach( $file_arr as $single ) {
				$file =  TF_JSON_PATH . $single;
				if( file_exists($file) ) {
					unlink( TF_JSON_PATH . $single );
				}
			}
		}
	}
}
?>
