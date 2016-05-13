<?php
/**
* Fire During the Deactivation of the Plugin
*
* @since 1.0
* @author Deepen
*
* @package  activators/Tf_Deactivator
*/

if( !class_exists('Tf_Deactivator') ){
	class Tf_Deactivator {
		public function deactivate() {
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
