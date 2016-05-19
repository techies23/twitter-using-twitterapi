<div class="wrap">
<?php if( (get_option( 'tf_oauth_access_token' ) == null) || ( get_option( 'tf_access_secret_token' ) == null ) || (get_option( 'tf_consumer_key' ) == null ) || (get_option( 'tf_consumer_secret_key' ) == null)): ?>
<div class="notice notice-warning"><p><?php _e('Required API Keys are missing !! See <a href="http://deepenbajracharya.com.np/documentation/twitter/1.0">documentation</a> of how to configure. ', 'tf'); ?></p></div>
<?php endif; ?>
	<form name="post" method="POST" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" id="post">
		<?php wp_nonce_field( 'tf_settings', 'settings_wpnonce' ); ?>
		<div id="poststuff"> 
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" style="position: relative;">
					<div class="notice notice-success notice-custom-tf is-dismissible cache_clear" style="display:none;"></div>
					<div id="tf_settings_div_id" class="postbox">
						<h3 class="hndle"><?php _e('Credentials', 'tf'); ?></h3>
						<div class="inside">
							<p><?php _e('Here lies your Crendentials found in Developer Account Section.', 'tf'); ?></p>
							<table class="tf_settings_details">
								<?php if( (get_option( 'tf_oauth_access_token' ) != null ) && (get_option( 'tf_access_secret_token' ) != null ) && ( get_option( 'tf_consumer_key' ) != null ) && (get_option( 'tf_consumer_secret_key' ) != null )): ?>
									<input type="hidden" name="tf_action_type" value="edit" />
								<?php endif; ?>
								<tr>
									<th><?php _e('Twitter Username @', 'tf'); ?></th>
									<td><input type="text" name="tf_username" value="<?php echo ( get_option( 'tf_username' ) != null ) ? get_option( 'tf_username' ) : null; ?>" class="tf_username" /></td>
								</tr>
								<tr>
									<th><?php _e('Access Token', 'tf'); ?></th>
									<td><input type="text" name="tf_oauth_access_token" value="<?php echo ( get_option( 'tf_oauth_access_token' ) != null ) ? get_option( 'tf_oauth_access_token' ) : null; ?>" class="tf_oauth_access_token" /></td>
								</tr>
								<tr>
									<th><?php _e('Access Secret Token', 'tf'); ?></th>
									<td><input type="text" name="tf_access_secret_token" value="<?php echo ( get_option( 'tf_access_secret_token' ) != null ) ? get_option( 'tf_access_secret_token' ) : null; ?>" class="tf_access_secret_token" /></td>
								</tr>
								<tr>
									<th><?php _e('Consumer Key', 'tf'); ?></th>
									<td><input type="text" name="tf_consumer_key" value="<?php echo ( get_option( 'tf_consumer_key' ) != null ) ? get_option( 'tf_consumer_key' ) : null; ?>" class="tf_consumer_key" /></td>
								</tr>
								<tr>
									<th><?php _e('Consumer Secret Key', 'tf'); ?></th>
									<td><input type="text" name="tf_consumer_secret_key" value="<?php echo ( get_option( 'tf_consumer_secret_key' ) != null ) ? get_option( 'tf_consumer_secret_key' ) : null; ?>" class="tf_consumer_secret_key" /></td>
								</tr>
							</table>
						</div>
					</div>
					<div id="tf_default_view" class="postbox">
						<h3 class="hndle"><?php _e('Basic Settings', 'tf'); ?></h3>
						<div class="inside">
							<table class="tf_basic_details">
								<tr>
									<th><?php _e('Feeds Count', 'tf'); ?></th>
									<td><input type="number" name="tf_feeds_count" value="<?php echo ( get_option( 'tf_feeds_count' ) != null ) ? get_option( 'tf_feeds_count' ) : null; ?>" class="tf_feeds_count" /></td>
								</tr>
								<tr>
									<th><?php _e('Show Followers', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_followers" <?php echo ( get_option( 'tf_followers' ) ) ? 'checked' : null; ?>></td>
								</tr>
								<tr>
									<th><?php _e('Show Following', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_following" <?php echo ( get_option( 'tf_following' ) ) ? 'checked' : null; ?>></td>
								</tr>
								<tr>
									<th><?php _e('Exclude Replies from Feeds<br>(For User Timeline)', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_exclude_feeds_from_timeline" <?php echo ( get_option( 'tf_exclude_feeds_from_timeline' ) ) ? 'checked' : null; ?>></td>
								</tr>
								<tr>
									<th><?php _e('Enable Profile thumbnail', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_profile_thumbnail" <?php echo ( get_option( 'tf_profile_thumbnail' ) ) ? 'checked' : null; ?>></td>
								</tr>
								<tr>
									<th><?php _e('Show Post Thumbnails', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_post_thumbmnails" <?php echo ( get_option( 'tf_post_thumbmnails' ) ) ? 'checked' : null; ?> ></td>
								</tr>
								<tr>
									<th><?php _e('Show Retweets Icon', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_retweet_icon" <?php echo ( get_option( 'tf_retweet_icon' ) ) ? 'checked' : null; ?> ></td>
								</tr>
								<tr>
									<th><?php _e('Show Follow Button', 'tf'); ?></th>
									<td><input type="checkbox" name="tf_follow_btn" <?php echo ( get_option( 'tf_follow_btn' ) ) ? 'checked' : null; ?> ></td>
								</tr>
							<!-- <tr>
								<th><?php _e('Theme', 'tf'); ?></th>
								<td class="tf_theme_element_td"><input type="radio" name="tf_theme" value="0" <?php echo ( get_option( 'tf_theme' ) == 0 ) ? 'checked' : null; ?>><?php _e('Default', 'tf'); ?></td>
								<td class="tf_theme_element_td"><input type="radio" name="tf_theme" value="1" <?php echo ( get_option( 'tf_theme' ) == 1 ) ? 'checked' : null; ?>><?php _e('Stylish Boxes', 'tf'); ?></td>
								<td class="tf_theme_element_td"><input type="radio" name="tf_theme" value="2" <?php echo ( get_option( 'tf_theme' ) == 2 ) ? 'checked' : null; ?>><?php _e('Black Theme', 'tf'); ?></td>
							</tr> -->
						</table>
					</div>
				</div>
			</div>

			<div id="postbox-container-1" class="postbox-container postbox-container-out">
				<div id="submitdiv" class="postbox">
					<h3 class="hndle"><span><?php _e('Save Settings', 'tf'); ?></span></h3>
					<div class="inside">
						<div class="submitbox" id="submitpost">
							<div id="minor-publishing">
								<div style="display:none;">
									<p class="submit"><input type="submit" name="save" id="save" class="button" value="Save"></p>
								</div>
								<div class="clear"></div>
							</div>
							<div id="major-publishing-actions">
								<span class="tf-clear-cache"><?php _e( '<strong><a id="tf-flush-cache" href="javascript:void(0);">Clear Twitter Cache</a></strong>', 'tf' ); ?></span><br>	
								<span class="tf-clear-cache"><?php _e( '<strong><a id="tf-flush-keys" href="javascript:void(0);">Flush Keys</a></strong>', 'tf' ); ?></span>
								<div id="publishing-action">
									<input name="twitter_settings_publish" type="hidden" id="twitter_settings_publish" value="Save">
									<input type="submit" name="publish_settings_tf" id="publish_settings" class="button button-primary button-large" value="Save" accesskey="p">
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
				<div id="submitdiv" class="postbox">
					<h3 class="hndle"><span><?php _e('Information', 'tf'); ?></span></h3>
					<div class="inside">
						<ul class="tf-information-sec">
							<li><a target="_blank" href="http://deepenbajracharya.com.np/documentation/twitter/1.0"><?php _e('Documentation', 'tf'); ?></a></li>
							<li><a target="_blank" href="http://deepenbajracharya.com.np/documentation/twitter/1.0/#faq"><?php _e('FAQ', 'tf'); ?></a></li>
							<li><a target="_blank" href="http://deepenbajracharya.com.np/know-me-more/"><?php _e('Developer', 'tf'); ?></a></li>
						</ul>
					</div>
				</div>
				<div class="inside">
					<p class="creator_of_all"><?php _e('Developed by <a target="_blank" href="http://deepenbajracharya.com.np">Deepen</a> <a target="_blank" href="https://twitter.com/techies23">(@techies23)</a><br>Plugin Version '.TF_PLUGIN_VERSION); ?></p>
				</div>
			</div>
		</div>
	</div>
</form>
</div>