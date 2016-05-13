<?php
/**
 * Helper Class for Helper FUnctions
 *
 * @since  1.0 
 * @author Deepen
 * @category Awsomness
 * @package  helpers/TwitterAPIPage
 * 
 */
if( !class_exists('TwitterAPIhelper') ) {
class TwitterAPIhelper {
	/**
	 * Convert Time
	 * @since 1.0
	 */
	public static function convert_time($created_at) {
			//Stringify time
			$created_time = strtotime($created_at);
			$now_time = time(); //Current Time
			$difference_time = $now_time - $created_time; //Time difference

			$years = intval($difference_time / 31104000);
			$months = intval($difference_time / 2592000);
			$days = intval($difference_time / 86400);
			$hours = intval($difference_time / 3600);	

			if( $difference_time <= 3600 ) {
				$minutes = intval($difference_time / 60);
				return ($minutes > 1) ? $minutes . ' minutes ago': $minutes . ' minute ago';
			}

			if( $difference_time <= 86400 && $difference_time >= 3600 ) {
				$hours = intval($difference_time / 3600);	
				return ($hours > 1) ? $hours . ' hours ago': $hours . ' hour ago';
			} 

			if( $difference_time <= 2592000 && $difference_time >= 86400 ) {
				$days = intval($difference_time / 86400);	
				return ($days > 1) ? $days . ' days ago': $days . ' day ago';
			}

			if( $difference_time <= 31104000 && $difference_time >= 2592000 ) {
				$months = intval($difference_time / 2592000);
				return ($months > 1) ? $months . ' months ago' : $months . ' month ago';
			}

			if( $difference_time >= 31104000 ) {
				$years = intval($difference_time / 31104000);	
				return ($years > 1) ? $years . ' years ago': $years . ' year ago';
			}

		}

		/**
		 * Check Links and Convert to necessary
		 * @since 1.0
		 */
		static function tf_tweet_links_filter( $tweet ) {
			// actual tweet as a string
			$tweetText = $tweet['text'];
			
			// create an array to hold urls
			$tweetEntites = array();

			// add each url to the array
			foreach( $tweet['entities']['urls'] as $url ) {
				$tweetEntites[] = array (
						'type'    => 'url',
						'curText' => $url['url'],
						'newText' => "<a href='".$url['expanded_url']."' target='_blank'>".$url['url']."</a>"
					);
			}  // end foreach

			// add each user mention to the array
			foreach ( $tweet['entities']['user_mentions'] as $mention ) {
				$string = mb_substr( $tweetText, $mention['indices'][0], ( $mention['indices'][1] - $mention['indices'][0] ), 'UTF-8' );
				$tweetEntites[] = array (
					'type'    => 'mention',
					'curText' => $string,
					'newText' => "<a href='http://twitter.com/".$mention['screen_name']."' target='_blank'>".$string."</a>"
					);
			}  // end foreach

			// add each hashtag to the array
			foreach ( $tweet['entities']['hashtags'] as $tag ) {
				$string = mb_substr( $tweetText, $tag['indices'][0], ( $tag['indices'][1] - $tag['indices'][0] ), 'UTF-8' );
				$tweetEntites[] = array (
					'type'    => 'hashtag',
					'curText' => $string,
					'newText' => "<a href='http://twitter.com/search?q=%23".$tag['text']."&src=hash' target='_blank'>".$string."</a>"
					);
			} 

			// replace the old text with the new text for each entity
			foreach ( $tweetEntites as $entity ) {
				$tweetText = str_replace( $entity['curText'], $entity['newText'], $tweetText );
			} // end foreach

			return $tweetText;
		} 
	}
	new TwitterAPIhelper();
}

?>