<?php
	/*
	Plugin Name: PluginBear LikeLock
	Plugin URI: http://www.pluginbear.com/likelock
	Description: Lock content from users until they like your page URL.
	Version: 1.3
	Author: PluginBear
	Author URI: http://www.pluginbear.com
	*/
	
	// Defaults
		$directory = '/wp-content/plugins/pluginbear-likelock';
	
	// Install Database
		global $db_version;
		$db_version = "1.0";
		
		function db_install() {
			global $wpdb;
			global $db_version;
			
			$table_name = $wpdb->prefix."pluginbear_likelock";
			
			$sql = "CREATE TABLE ".$table_name." (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				app_id VARCHAR(255) DEFAULT '' NOT NULL,
				like_text VARCHAR(255) DEFAULT '' NOT NULL,
				text_color VARCHAR(255) DEFAULT '' NOT NULL,
				bg_color VARCHAR(255) DEFAULT '' NOT NULL,
				opacity VARCHAR(255) DEFAULT '' NOT NULL,
				custom_url VARCHAR(255) DEFAULT '' NULL,
				posts INT NOT NULL,
				pages INT NOT NULL,
				UNIQUE KEY id (id)
			);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			add_option("db_version", $db_version);
		}
		
		function db_install_data() {
			global $wpdb;
			
			$table_name = $wpdb->prefix."pluginbear_likelock";
			
			$db_content = array(
			  'like_text' => 'Like this page to unlock the content',
			  'text_color' => '#FFFFFF',
			  'bg_color' => '#000000',
			  'opacity' => 0.95,
			  'custom_url' => ''
		   );
			
			$wpdb->insert($table_name,array('like_text'=>$db_content['like_text'],'text_color'=>$db_content['text_color'],'bg_color'=>$db_content['bg_color'],'opacity'=>$db_content['opacity'],'custom_url'=>$db_content['custom_url'],'posts'=>0,'pages'=>0));
		}
		
		register_activation_hook(__FILE__,'db_install');
		register_activation_hook(__FILE__,'db_install_data');
		
	// Get Settings
		global $wpdb;
		$table_name = $wpdb->prefix."pluginbear_likelock";
		$settings = $wpdb->get_row('SELECT * FROM '.$table_name);
	
	// Create WP Menu
		if (!function_exists('pluginbear_menu')) {
			add_action('admin_menu', 'pluginbear_menu');
			
			function pluginbear_menu() {
				add_menu_page('PluginBear', 'PluginBear', 'manage_options', 'pluginbear', 'pluginbear_function');
			}
			
			function pluginbear_function() {
				if (!current_user_can('manage_options'))  {
					wp_die( __('You do not have sufficient permissions to access this page.') );
				}
				echo '<p>PluginBear Homepage</p>';
			}
		}
	
	// Create LikeLock Submenu
		add_action('admin_menu', 'pluginbear_likelock_submenu');
		
		function pluginbear_likelock_submenu() {
			add_submenu_page("pluginbear", "PluginBear LikeLock", "LikeLock", 0, "pluginbear_likelock", "pluginbear_likelock_function");
		}
		
		function pluginbear_likelock_function() {
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			include("settings.php");
		}
	
	if (is_admin()) {
		// Add Admin Styles
			wp_enqueue_style('pluginbear-colorpicker',$directory.'/css/pluginbear_colorpicker.css',false,'1.0','all');
			wp_enqueue_style('pluginbear-slider',$directory.'/css/pluginbear_slider.css',false,'1.0','all');
			wp_enqueue_style('pluginbear-likelock',$directory.'/css/pluginbear_likelock.css',false,'1.0','all');
		
		// Add Admin Scripts
			wp_enqueue_script('pluginbear-colorpicker-js',$directory.'/js/pluginbear_colorpicker.js',array('jquery'),'1.0',true);
			wp_enqueue_script('pluginbear-slider-js',$directory.'/js/pluginbear_slider.js',array('jquery'),'1.0',true);
			wp_enqueue_script('pluginbear-likelock-admin',$directory.'/js/pluginbear_likelock_admin.js',array('jquery'),'1.0',true);
			wp_enqueue_script('pluginbear-likelock',$directory.'/js/pluginbear_likelock.js',array('jquery'),'1.0',true);
	}
	
	if (!is_admin()) {
		// Add Public Styles
			wp_enqueue_style('pluginbear-likelock',$directory.'/css/pluginbear_likelock.css',false,'1.0','all');
		
		// Add Public Scripts
			wp_enqueue_script('pluginbear-facebook','http://connect.facebook.net/en_US/all.js','1.0',true);
			wp_enqueue_script('pluginbear-likelock',$directory.'/js/pluginbear_likelock.js',array('jquery'),'1.0',true);
			wp_enqueue_script('pluginbear-cookie',$directory.'/js/pluginbear_cookie.js',array('jquery'),'1.0',true);
	}
	
	
	// Add Shortcode
		function register_shortcodes(){
		   add_shortcode('likelock', 'pluginbear_likelock_shortcode');
		}
		
		function pluginbear_likelock_shortcode($atts, $content = null){
			global $post;
			global $settings;
			
			extract(shortcode_atts(array(
				'like_text' => $settings->like_text,
				'text_color' => $settings->text_color,
				'bg_color' => $settings->bg_color,
				'opacity' => $settings->opacity,
				'custom_url' => $settings->custom_url
			), $atts));
			
			$bg_color = hex2rgb($bg_color);
			
			if ($custom_url) { $url = $custom_url; } else { $url = get_permalink(); }
			
			return '
				<div id="pluginbear_likelock_post'.$post->ID.'" class="likelock">
					<div class="likelock_container" style="background:rgba('.$bg_color[0].','.$bg_color[1].','.$bg_color[2].','.$opacity.');color:'.$text_color.';">
						<div class="likelock_text">
							<div class="likelock_fb">
								<div class="fb-like" data-href="'.$url.'" data-send="false" data-layout="button_count" data-width="70" data-show-faces="false"></div>
							</div>
							'.$like_text.'
						</div>
					</div>
					<p class="likelock_content">'.do_shortcode($content).'</p>
				</div>
			';
		}
		
		add_action('init', 'register_shortcodes');
		
	// Add Global Settings
		function likelock_posts($content){
			global $settings;
			global $post;
			
			if ($settings->posts==1 && get_post_type()=="post" || $settings->pages==1 && get_post_type()=="page") {
				$bg_color = hex2rgb($settings->bg_color);
				extract(shortcode_atts(array(
					'like_text' => $settings->like_text,
					'text_color' => $settings->text_color,
					'bg_color' => 'rgba('.$bg_color[0].','.$bg_color[1].','.$bg_color[2].','.$settings->opacity.')',
					'custom_url' => $settings->custom_url
				), $atts));
			
			if ($custom_url) { $url = $custom_url; } else { $url = get_permalink(); }
				
				echo '
					<div id="pluginbear_likelock_post'.$post->ID.'" class="likelock">
						<div class="likelock_container" style="background:'.$bg_color.';color:'.$text_color.';">
							<div class="likelock_text">
								<div class="likelock_fb">
									<div class="fb-like" data-href="'.$url.'" data-send="false" data-layout="button_count" data-width="70" data-show-faces="false"></div>
								</div>
								'.$like_text.'
							</div>
						</div>
						<div class="likelock_content">'.do_shortcode($content).'</div>
					</div>
				';
			} else {
				return do_shortcode($content);	
			}
		}
		
		add_action('the_content','likelock_posts');
	
	// Add Facebook API
		if (!is_admin()) {
			function facebook_api(){
				global $post;
				global $directory;
				global $settings;
				
				echo '
					<div id="fb-root"></div>
					<script>
						jQuery(function(){
							FB.init({
								appId :\''.$settings->app_id.'\',
								status : true,
								cookie : true,
								xfbml : true,
								oauth : true
							});
							FB.Event.subscribe(\'edge.create\',function(){
								jQuery.cookie("pluginbear_likelock_post'.$post->ID.'","liked");
								jQuery("#pluginbear_likelock_post'.$post->ID.' .likelock_container").fadeOut();
								jQuery(".likelock_content").css("padding","0px");
							});
							if (jQuery.cookie("pluginbear_likelock_post'.$post->ID.'")=="liked") {
								jQuery("#pluginbear_likelock_post'.$post->ID.' .likelock_container").remove();
								jQuery(".likelock_content").css("padding","0px");
							}
						});
					</script>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, "script", "facebook-jssdk"));</script>
				';	
			}
			add_action('wp_footer','facebook_api');
		}
		
	// Uninstall
		function likelock_uninstall() {
			global $wpdb;
			
			$table_name = $wpdb->prefix."pluginbear_likelock";
			$sql = "DROP TABLE ".$table_name;
			$wpdb->query($sql);
		}
		
		register_deactivation_hook(__FILE__, 'likelock_uninstall');
		
	// Misc Functions
		function hex2rgb($hex) {
			$hex = str_replace("#", "", $hex);
			
			if(strlen($hex) == 3) {
				$r = hexdec(substr($hex,0,1).substr($hex,0,1));
				$g = hexdec(substr($hex,1,1).substr($hex,1,1));
				$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
				$r = hexdec(substr($hex,0,2));
				$g = hexdec(substr($hex,2,2));
				$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
			
			return $rgb;
		}