<?php
/**
 * Included Widget Output File
 *
 * @since  1.0
 *
 * @package   widget/output
 */
if ( ! empty( $instance['type'] ) ) {
	$type = $instance['type'];
}

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

$user = get_option('tf_username') ? get_option('tf_username') : null;
$exclude_from_timeline = get_option('tf_exclude_feeds_from_timeline' ) ? get_option('tf_exclude_feeds_from_timeline' ) : null;

$getfield = '?screen_name='.$user.'&count=50&exclude_replies='.$exclude_from_timeline.'&include_rts=true';

$current_time = time();
$expire_time = 1 * 60 * 60; // Hourly
if(file_exists($file_name_tweet)) {
	$file_time = filemtime($file_name_tweet);
}

if( file_exists($file_name_tweet) && ($current_time - $expire_time < $file_time) ) {
	//Returning from the cached file
	$strings = json_decode(file_get_contents($file_name_tweet), true);
} else {
	$twitter = new TwitterAPIExchange($settings);
	$jstrings = $twitter->setGetfield($getfield)
	->buildOauth($url, 'GET')
	->performRequest();
	//Put the Json in File but dont load from here for now.
	file_put_contents($file_name_tweet, $jstrings);
	//Load the data from here
	$strings = json_decode($jstrings, true);

}

$number_of_posts = $instance['tf_no_of_tweets'] ? $instance['tf_no_of_tweets'] : '5'; //Defined in Optiosn
$count_posts = 1;
$tf_theme = $instance['tf_widget_theme']; //Defined in Optiosn
if( $tf_theme == 'tf_block_widget_theme' ) {
	wp_enqueue_style( TF_PLUGIN_SLUG . '-theme-blue', TF_URL . 'assets/css/block-theme-widget.css' );
} else if($tf_theme == 'tf_black_widget_theme') {
	wp_enqueue_style( TF_PLUGIN_SLUG . '-theme-black', TF_URL . 'assets/css/black-theme-widget.css' );
}

if( !empty($strings['errors']) ) {
	$result = '<div class="tf_error_generated">'.$strings['errors'][0]['message'].'</div>';
} else {
	$widget_height = !empty($instance['tf_widget_height']) ? $instance['tf_widget_height'] : '600px';
	$result = '<div class="tf-widget-wrapper" id="tf-widget-wrapper" style="height:'.$widget_height.'">';
	$count = 1;
	foreach($strings as $string) {
		if($count_posts <= $number_of_posts) {
			$created_at = TwitterAPIhelper::convert_time($string['created_at']);
			$result.= "<div class='tf_widget_common_class tf_widget_feeds_".$count++."'>";
			if( !empty( $instance['tf_show_profile_thumnails'] ) ) {
				$result.= "<div class='tf_widget_content_wrapper_with_thumbnail'>";
				$result.= "<div class='tf_widget_image_thumb_wrap'><img src=". $string['user']['profile_image_url'] ." alt='twitter_img_thumb'></div>";
				$result.= "<div class='tf-widget-screen-name'><a href='https://twitter.com/intent/retweet?tweet_id=".$string['id']."' class='tf-logo-popup'><img id='tf-twitter-retweet-logo' src='".TF_URL . 'assets/img/retweet.png' ."'></a><li><a target='_blank' href='https://twitter.com/".$string['user']['screen_name']."'>@".$string['user']['screen_name'] .'</a></li><li>'. $created_at."</li></div>";
				$result.= "<div class='clear'></div>";
			} else {
				$result.= "<div class='tf_widget_content_wrapper_no_thumbnail'>";
				$result.= "<div class='tf-wid-screen-name'><a target='_blank' href='https://twitter.com/".$string['user']['screen_name']."'>@".$string['user']['screen_name'] .'</a><span>'. $created_at."</span>";
				$result.= "<a href='https://twitter.com/intent/retweet?tweet_id=".$string['id']."' class='tf-logo-popup'><img id='tf-twitter-retweet-logo' src='".TF_URL . 'assets/img/retweet.png' ."'></a></div>";
				
			}

			$filtered_strings = TwitterAPIhelper::tf_tweet_links_filter($string);
			$result.= "<p class='tf-widget-text-status'>". $filtered_strings ."</p>";
			if( !empty($string['entities']['media']) ) {
				if( !empty( $instance['tf_post_images']) ) {
					$result.= "<div class='tf-widget-media-image'><a class='image-popup-no-margins' href=". $string['entities']['media'][0]['media_url'] ."><img src=". $string['entities']['media'][0]['media_url'] ." alt='Media Image' width=".$string['entities']['media'][0]['sizes']['medium']['w']." height=".$string['entities']['media'][0]['sizes']['medium']['h']."></a></div>";
				}
			}
			$result.= "</div><div class='clear'></div></div>";
		}
		$count_posts++;
	}
	$result.= '</div>';

	if( !empty( $instance['tf_scroll_color']) ) {
		$tf_scroll_color = $instance['tf_scroll_color'];
	} else {
		$tf_scroll_color = '#000';
	}
	if( !empty( $instance['tf_scroll_width']) ) {
		$tf_scroll_width = $instance['tf_scroll_width'];
	}else {
		$tf_scroll_width = '2px';
	}
	if( !empty( $instance['tf_enable_touch']) ) {
		$tf_enable_touch = $instance['tf_enable_touch'];
	} else {
		$tf_enable_touch = false;
	}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var tf_scroll_color = '<?php echo $tf_scroll_color; ?>';
			var tf_scroll_width = '<?php echo $tf_scroll_width; ?>';
			var tf_enable_touch = '<?php echo $tf_enable_touch; ?>';
			if(tf_enable_touch == "on") {
				tf_enable_touch = true;
			}
			$(".tf-widget-wrapper").niceScroll(
			{
				cursorborder:"#000",
				cursorcolor: tf_scroll_color,
				cursorwidth: tf_scroll_width,
				mousescrollstep: 60,
				touchbehavior: tf_enable_touch,
			}
			); 
		});
	</script>
	<?php
}
echo $result;

