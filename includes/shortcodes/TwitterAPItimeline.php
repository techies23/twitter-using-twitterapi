<?php

/**
 * Fires When User Timeline Shortcode is called
 *
 * @since 1.0
 *
 * @version modified on 1.1
 * 
 * @author deepen
 *
 * @package  shortcodes/TwitterAPItimeline
 * 
 */
if( !class_exists('TwitterAPItimeline') ){

	class TwitterAPItimeline {

		public function __construct() {
			add_shortcode( 'tf_user_timeline', array($this, 'tf_timeline') );
			add_shortcode( 'tf_tweet_id', array($this, 'tf_specific_tweet') );
		}

		/**
		 * Shortcode Function for Timeline
		 * @since 1.0
		 * @author  Deepen
		 */
		static function tf_timeline($atts, $content = null) {
			$a = shortcode_atts( array(
				'id' => 'tf_twitter_feeds',
				'class' => 'tf_twitter_feeds', 	
				'type' => 'user_timeline',
				), $atts );

			$strings = self::get_contents( esc_attr( $a['type'] ) ); //From the get_contents method
			// echo '<pre>';
			// var_dump($strings);
			// echo '</pre>';
			$number_of_posts = get_option('tf_feeds_count') ? get_option('tf_feeds_count') : '5'; //Defined in Optiosn
			$count_posts = 1;
			ob_start();
			if( !empty($strings['errors']) ) {
				$result = '<div class="tf_error_generated">'.$strings['errors'][0]['message'].'</div>';
			} else {
				$result = '<div class="'.$a['class'].'" id="'.$a['id'].'">';
				$count = 1;

				$user = get_option('tf_username') ? get_option('tf_username') : null;
				if(get_option('tf_follow_btn')) {
					echo "<div class='tf-logo-follow-wrap'><a class='tf-logo-follow' href='https://twitter.com/intent/follow?screen_name=".$user."&original_referer=".home_url()."' class='tf-logo-popup'>Follow @".$user."</a></div>";
				}
				if(get_option('tf_followers')) {
					$result.= "<div class='tf-followers'>Followers: ". $strings[0]['user']['followers_count'] ."</div>";
				}
				if(get_option('tf_following')) {
					$result.= "<div class='tf-following'>Following: ". $strings[0]['user']['friends_count'] ."</div>";
					$result.= "<div class='clear'></div>";
				}

				foreach($strings as $string) {
					if($count_posts <= $number_of_posts) {
						$created_at = TwitterAPIhelper::convert_time($string['created_at']);
						$result.= "<div class='tf_common_class tf_tweets_feeds_".$count++."'>";
						if(get_option('tf_profile_thumbnail')) {
							$result.= "<div class='tf_list_image_thumb_wrap'><img src=". $string['user']['profile_image_url'] ." alt='twitter_img_thumb'></div>";
							$result.= "<div class='tf_list_content_wrapper_with_thumbnail'>";
						} else {
							$result.= "<div class='tf_list_content_wrapper_no_thumbnail'>";
						}
						$result.= "<p class='tf-screen-name'><a target='_blank' href='https://twitter.com/".$string['user']['screen_name']."'>@".$string['user']['screen_name'] .'</a><span>'. $created_at."</span>";
						if( get_option('tf_retweet_icon') ) {
							$result.= "<a href='https://twitter.com/intent/retweet?tweet_id=".$string['id']."' class='tf-logo-popup'><img id='tf-twitter-retweet-logo' src='".TF_URL . 'assets/img/retweet.png' ."' alt='retweet'></a></p>";
						}

						$filtered_strings = TwitterAPIhelper::tf_tweet_links_filter($string);
						$result.= "<p class='tf-text-status'>". $filtered_strings ."</p>";
						if( !empty($string['entities']['media']) ) {
							if(get_option('tf_post_thumbmnails')) {
								$result.= "<div class='tf-media-image'><a class='image-popup-no-margins' href=". $string['entities']['media'][0]['media_url'] ."><img src=". $string['entities']['media'][0]['media_url'] ." alt='Media Image' width=".$string['entities']['media'][0]['sizes']['medium']['w']." height=".$string['entities']['media'][0]['sizes']['medium']['h']."></a></div>";
							}
						}
						$result.= "</div><div class='clear'></div></div>";
					}
					$count_posts++;
				}
				$result.= '</div>';
				$result.= '<div class="tf_follow_counts">';
				$result.= '</div>';
			}
			return $result;
			ob_get_clean();
		}

		/**
		 * Private function that gets the cached content as well as twitter feeds.
		 * @param  $type = Mention Timeline/ User timeline so on
		 * @since 1.0
		 */
		private static function get_contents($type) {
			$settings = TwitterAPIMain::tf_configure_keys();

			switch( $type ) {
				case 'mentions_timeline':
				$url = "https://api.twitter.com/1.1/statuses/mentions_timeline.json";
				$file_name_tweet = TF_JSON_PATH . 'twitter-mentions.json';
				break;
				case 'user_timeline':
				$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
				$file_name_tweet = TF_JSON_PATH . 'twitter-user.json';
				break;
				case 'home_timeline':	
				$url = "https://api.twitter.com/1.1/statuses/home_timeline.json";
				$file_name_tweet = TF_JSON_PATH . 'twitter-home.json';
				break;
				case 'retweets_own_by_others':
				$url = "https://api.twitter.com/1.1/statuses/retweets_of_me.json";
				$file_name_tweet = TF_JSON_PATH . 'twitter-retweets.json';
				break;
				default:
				$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
				$file_name_tweet = TF_JSON_PATH . 'twitter-user.json';
				break;
			}

			$user = get_option('tf_username') ? get_option('tf_username') : false;
			$exclude_from_timeline = get_option('tf_exclude_feeds_from_timeline' ) ? get_option('tf_exclude_feeds_from_timeline' ) : false;

			$getfield = '?screen_name='.$user.'&count=60&exclude_replies='.$exclude_from_timeline.'&include_rts=true';

			$current_time = time();
			$expire_time = 1 * 60 * 60; // Hourly
			if(file_exists($file_name_tweet)) {
				$file_time = filemtime($file_name_tweet);
			}
			
			if( file_exists($file_name_tweet) && ($current_time - $expire_time < $file_time) ) {
				//Returning from the cached file
				$strings = json_decode(file_get_contents($file_name_tweet), true);
				return $strings;
			} else {
				$twitter = new TwitterAPIExchange($settings);
				$jstrings = $twitter->setGetfield($getfield)
				->buildOauth($url, 'GET')
				->performRequest();
				//Put the Json in File but dont load from here for now.
				file_put_contents($file_name_tweet, $jstrings);
				//Load the data from here
				$strings = json_decode($jstrings, true);
				return $strings;
			}
		}

		/**
		 * Useful for Testimonials
		 * @since 1.1
		 * 
		 * @author  Deepen
		 */
		static function tf_specific_tweet($atts, $content = null) {
			$a = shortcode_atts( array(
				'id' => 'tf_specific_tweets',
				'class' => 'tf_specific_tweets', 	
				'tweet_id' => null,
				), $atts );

			$settings = TwitterAPIMain::tf_configure_keys();
			$url = 'https://api.twitter.com/1.1/statuses/show.json';
			$file_name_tweet = TF_JSON_PATH . 'twitter-single-tweets.json';
			$tweet_ids = explode(',', $a['tweet_id']);
			$strings = array();

			$current_time = time();
			$expire_time = 1 * 60 * 60; // Hourly
			if(file_exists($file_name_tweet)) {
				$file_time = filemtime($file_name_tweet);
			}
			
			if( file_exists($file_name_tweet) && ($current_time - $expire_time < $file_time) ) {
				//Returning from the cached file
				$strings = json_decode(file_get_contents($file_name_tweet), true);
			} else {
				foreach( $tweet_ids as $tweet_id ) {
					$getfield = '?id='.$tweet_id;
					$twitter = new TwitterAPIExchange($settings);
					$jstrings = $twitter->setGetfield($getfield)
					->buildOauth($url, 'GET')
					->performRequest();
					$decode_chunk_data = json_decode($jstrings, true);
					$strings[] = $decode_chunk_data;
				}
				file_put_contents($file_name_tweet, json_encode($strings));
			}

			// echo '<pre>';
			// print_r($strings);
			// echo '</pre>';
			// die;
			
			$count = 1;
			$result = '';
			$user = get_option('tf_username') ? get_option('tf_username') : false;
			foreach($strings as $string) {
				if( isset($string['created_at']) ) {
					$created_at = TwitterAPIhelper::convert_time($string['created_at']);
					if( !empty($string['entities']['media']) ) {
						if(get_option('tf_post_thumbmnails')) {
							$result.= "<div class='tf-media-image-single'><a class='image-popup-no-margins' href=". $string['entities']['media'][0]['media_url'] ."><img src=". $string['entities']['media'][0]['media_url'] ." alt='Media Image' width=".$string['entities']['media'][0]['sizes']['medium']['w']." height=".$string['entities']['media'][0]['sizes']['medium']['h']."></a></div>";
						}
					}
					$result.= "<div class='tf_single_tweet tf_tweets_feeds_".$count++."'>";
					if(get_option('tf_profile_thumbnail')) {
						$result.= "<div class='tf_list_image_thumb_wrap_single'><img src=". $string['user']['profile_image_url'] ." alt='twitter_img_thumb'></div>";
						$result.= "<div class='tf_list_content_wrapper_with_thumbnail_single'>";
					} else {
						$result.= "<div class='tf_list_content_wrapper_no_thumbnail_single'>";
					}
					$result.= "<p class='tf-screen-name-single'><a target='_blank' href='https://twitter.com/".$string['user']['screen_name']."'>@".$string['user']['screen_name'] .'</a><span>'. $created_at."</span>";
					if( get_option('tf_retweet_icon') ) {
						$result.= "<a class='tf-widget-logo-follow-single' href='https://twitter.com/intent/follow?screen_name=".$user."&original_referer=".home_url()."' class='tf-logo-popup'>Follow @".$user."</a>";
						$result.= "<a href='https://twitter.com/intent/retweet?tweet_id=".$string['id']."' class='tf-logo-popup'><img id='tf-twitter-retweet-logo-single' src='".TF_URL . 'assets/img/retweet.png' ."'></a></p>";
					}

					$filtered_strings = TwitterAPIhelper::tf_tweet_links_filter($string);
					$result.= "<p class='tf-text-status'>". $filtered_strings ."</p>";
					
					$result.= "</div><div class='clear'></div></div>";
				} else {
					$result.= "<div class='tf_error_generated'>Incorrect Tweet ID format. Please check documentation on how to http://deepenbajracharya.com.np/documentation/twitter/1.0/#tf_tweet_id</div>";
				} 
			}
			echo $result;

		}
	}

	new TwitterAPItimeline();
}
?>
