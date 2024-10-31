<h1>PluginBear LikeLock</h1>

<?php
	global $wpdb;
	
	// Defaults
		$directory = '/wp-content/plugins/pluginbear-likelock';
		$table_name = $wpdb->prefix."pluginbear_likelock";
		
	// Update Settings
		if (isset($_POST['like_text'])) {
			$wpdb->update($table_name,
				array(
					'app_id' => $_POST['app_id'],
					'like_text' => $_POST['like_text'],
					'text_color' => $_POST['text_color'],
					'bg_color' => $_POST['bg_color'],
					'opacity' => $_POST['opacity'],
					'custom_url' => $_POST['custom_url'],
					'posts' => $_POST['likelock_posts'],
					'pages' => $_POST['likelock_pages'],
				),
				array(
					'id' => $_POST['id']
				)
			);
		}
		
	// Get Settings
		$settings = $wpdb->get_row('SELECT * FROM '.$table_name);
		$bg_color = hex2rgb($settings->bg_color);
?>

<div id="pluginbear_container" class="clearfix">
	<?php  if (isset($_POST['like_text'])) { ?>
    <div id="likelock_message" class="updated">
        <p>Global settings updated.</p>
    </div>
    <?php } ?>
    <div id="content_container">
        <h3>Settings</h3>
        <?php if (!$settings->app_id) { ?>
            <div id="likelock_message" class="error">
                <p>You need to enter your Facebook App ID before the LikeLock will function properly.</p>
            </div>
        <?php } ?>
        <form action="" method="post" id="likelock_update">
            <dl class="clearfix">
              <dt><label for="app_id">Facebook App ID</label></dt>
                <dd><input type="text" name="app_id" id="app_id" value="<?php echo $settings->app_id; ?>"> (<a href="http://developers.facebook.com/setup" target="_blank">Get your App ID</a>)</dd>
                <dt><label for="like_text">Like Text</label></dt>
                <dd><input type="text" name="like_text" id="like_text" value="<?php echo $settings->like_text; ?>"></dd>
                <dt><label for="text_color">Text Color</label></dt>
                <dd><input type="text" name="text_color" id="text_color" value="<?php echo $settings->text_color; ?>"></dd>
                <dt><label for="bg_color">Background Color</label></dt>
                <dd><input type="text" name="bg_color" id="bg_color" value="<?php echo $settings->bg_color; ?>"></dd>
                <dt><label for="opacity">Background Opacity</label></dt>
                <dd class="clearfix"><div id="likelock_slider"></div> <input type="text" name="opacity" id="opacity" value="<?php echo $settings->opacity; ?>"></dd>
                <dt><label for="custom_url">Custom URL</label></dt>
                <dd><input type="text" name="custom_url" id="custom_url" value="<?php echo $settings->custom_url; ?>"></dd>
            </dl>
            <p>
                <label for="likelock_posts"> <input type="checkbox" name="likelock_posts" id="likelock_posts" value="1" <?php if ($settings->posts=="1") { echo 'checked="checked"'; } ?>> LikeLock all posts</label><br>
                <label for="likelock_pages"> <input type="checkbox" name="likelock_pages" id="likelock_pages" value="1" <?php if ($settings->pages=="1") { echo 'checked="checked"'; } ?>> LikeLock all pages</label>
            </p>
            <input type="hidden" name="id" value="<?php echo $settings->id; ?>">
            <input type="submit" value="Update global settings"> <strong>Or copy the shortcode below to create a LikeLock with its own style:</strong>
        </form>
        
        <p id="shortcode_snippet">[likelock like_text="<span id="like_text_snippet"><?php echo $settings->like_text; ?></span>" text_color="<span id="text_color_snippet"><?php echo $settings->text_color; ?></span>" bg_color="<span id="bg_color_snippet"><?php echo $settings->bg_color; ?></span>" opacity="<span id="opacity_snippet"><?php echo $settings->opacity; ?></span>" url="<span id="custom_url_preview"><?php echo $settings->custom_url; ?></span>"]CONTENT[/likelock]</p>
        
        <h3>Preview</h3>
        
        <div id="likelock_demo" class="likelock">
            <div class="likelock_container" style="background:rgba(<?php echo $bg_color[0]; ?>,<?php echo $bg_color[1]; ?>,<?php echo $bg_color[2]; ?>,<?php echo $settings->opacity; ?>);color:<?php echo $settings->text_color; ?>;">
                <div class="likelock_text">
                    <div class="likelock_fb">
                        <img class="fb-like" src="<?php echo $directory; ?>/images/demo.png">
                    </div>
                    <span id="like_text_preview"><?php echo $settings->like_text; ?></span>
                </div>
            </div>
            <p class="likelock_content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque rhoncus aliquam metus. Pellentesque nulla mauris, laoreet et porttitor in, adipiscing et lacus. Phasellus dapibus sem quis purus congue pulvinar. Sed placerat mattis urna a feugiat. Integer tempor lacus nec sapien adipiscing vitae dictum felis dignissim. Phasellus dignissim nisl orci. Quisque magna est, congue et volutpat eu, lacinia eu leo. Nam iaculis tincidunt leo nec sodales. Quisque placerat purus eget odio lacinia rutrum. Duis elementum nunc eget magna iaculis id consequat metus consectetur. Nulla facilisi. Phasellus in ligula ante. Proin urna tortor, feugiat iaculis tincidunt vitae, aliquet vitae mi.</p>
        </div>
    </div>
    <div id="donations_container">
        <h3>Keep this plugin free!</h3>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="7JHGJL8PNY6X8">
            <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
        </form>
        <p>Thanks for trying out our plugin! We hope it does what you need. If you have any feature requests or need some support, please email us at <a href="mailto:support@pluginbear.com">support@pluginbear.com</a> and we&rsquo;ll see if we can help you out.</p>
    </div>
</div>