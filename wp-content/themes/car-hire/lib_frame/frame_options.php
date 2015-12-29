<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*
*** GLOBAL FRAMEWORK OPTIONS
*/

add_action( 'init', 'bizz_frame_options' );
function bizz_frame_options() {
	global $shortname, $bizz_package, $options;

	$options[] = array(	'type' => 'maintabletop');

		////// General Framework Settings

		$options[] = array(	'name' => __('General Settings', 'bizzthemes'),
							'type' => 'heading');
			
			$options[] = array(	'name' => __('Logo &amp; Favicon', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');

				$options[] = array(	'name' => __('Choose Your Custom Logo', 'bizzthemes'),
									'desc' => __('Upload your image or paste the full URL address to it next to upload button. <span class="important">Uploaded logo will be applied to Logo widget, which you may edit separately inside Layout control section, but your uploaded logo will be visible by default.</span>', 'bizzthemes'),
									'id' => $shortname.'_logo_url',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'upload');
									
				$options[] = array(	'name' => __('Choose Your Favicon Image', 'bizzthemes'),
									'desc' => __('Upload your favicon image or paste the full URL address to it next to upload button. Use 16x16px image, if you don&#8217;t have one use free <a href="'.esc_url('www.favicon.cc/').'">Favicon tool</a> and start rocking those browsers. <span class="important">Save your settings after upload is finished.</span>', 'bizzthemes'),
									'id' => $shortname.'_favicon',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'upload');
			
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Syndication / Feed', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');			
						
				$options[] = array( 'name' => __('RSS Feed Address', 'bizzthemes'),
									'desc' => __('If you are using a service like Feedburner to manage your RSS feed, enter full URL to your feed into box above. If you&#8217;d prefer to use the default WordPress feed, simply leave this box blank.', 'bizzthemes'),
									'id' => $shortname.'_feedburner_url',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'text');	
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Image Setup', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
						
				$options[] = array(	'name' => __('Display Thumbnails?', 'bizzthemes'),
									'label' => __('Display Thumbnails', 'bizzthemes'),
									'desc' => __('If you want to show image thumbnails, check this option.', 'bizzthemes'),
									'id' => $shortname.'_thumb_show',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');

				$options[] = array(	'name' => __('Resize Images Dynamically?', 'bizzthemes'),
									'label' => __('Resize Images Dynamically', 'bizzthemes'),
									'desc' => __('Resize images with thumb.php script &rarr; smooth pics ;)', 'bizzthemes'),
									'id' => $shortname.'_resize',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');					
									
				$options[] = array(	'name' => __('Automatic Image Handling?', 'bizzthemes'),
									'label' => __('Automatic Image Handling', 'bizzthemes'),
									'desc' => __('If no image in the custom field then first uploaded image is used.', 'bizzthemes'),
									'id' => $shortname.'_auto_img',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');	
									
				$options[] = array(	'name' => __('Show in RSS feed?', 'bizzthemes'),
									'label' => __('Show in RSS feed', 'bizzthemes'),
									'desc' => __('Show thumbnail images in RSS feeds.', 'bizzthemes'),
									'id' => $shortname.'_image_rss',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Stats and Scripts', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');	
						
				$options[] = array(	'name' => __('Header Scripts (just before the <code>&lt;/head&gt;</code> tag)', 'bizzthemes'),
									'desc' => __('If you need to add scripts to your header (like <a href="'.esc_url('haveamint.com/').'">Mint</a> tracking code), do so here. These scripts will be included just before the <code>&lt;/head&gt;</code> tag. You may paste multiple scripts.', 'bizzthemes'),
									'id' => $shortname.'_scripts_header',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'textarea');
									
				$options[] = array(	'name' => __('Body Scripts (just after the <code>&lt;body&gt;</code> tag)', 'bizzthemes'),
									'desc' => __('If you need to add scripts to your body (like <a href="'.esc_url('www.google.com/analytics').'">Google Analytics</a> tracking code), do so here. These scripts will be included just after the <code>&lt;body&gt;</code> tag. You may paste multiple scripts.', 'bizzthemes'),
									'id' => $shortname.'_scripts_body',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'textarea');
									
				$options[] = array(	'name' => __('Footer Scripts (just before the <code>&lt;/body&gt;</code> tag)', 'bizzthemes'),
									'desc' => __('If you need to add scripts to your footer (like <a href="'.esc_url('www.google.com/analytics').'">Google Analytics</a> tracking code), do so here. These scripts will be included just before the <code>&lt;/body&gt;</code> tag. You may paste multiple scripts.', 'bizzthemes'),
									'id' => $shortname.'_google_analytics',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'textarea');
			
			$options[] = array(	'type' => 'subheadingbottom');
						
		$options[] = array(	'type' => 'maintablebreak');
		
		/// Theme Branding
				
		$options[] = array(	'name' => __('Framework Branding', 'bizzthemes'),
							'type' => 'heading');
						
			$options[] = array(	'name' => __('Footer Logo', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
									
				$options[] = array(	'name' => __('Front-end Branding Options', 'bizzthemes'),
									'desc' => __('By applying front-end branding options users will acknowledge this website as your own product, with your own logo and optional backlink to theme developer. As this is GPL licensed theme, leave credits in code intact.<span class="important">These options are applied to BizzThemes Branding widget, which you may add/move/remove inside Layout control section.</span>', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('Remove footer credits alltogether', 'bizzthemes'),
									'id' => $shortname.'_branding_front_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');									
									
				$options[] = array(	'name' => __('Custom Image', 'bizzthemes'),
									'desc' => __('Upload your image or paste the full URL address to it next to upload button. Choose small image (recommended dimension within 115x30px limits). <span class="important">Your upload will start after you save changes</span>', 'bizzthemes'),
									'id' => $shortname.'_branding_front_logo',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'upload');
									
				$options[] = array(	'name' => __('Custom Link', 'bizzthemes'),
									'desc' => __('Add custom link - where your logo points to. Including <code>http://</code>.', 'bizzthemes'),
									'id' => $shortname.'_branding_front_link',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'text');
													
				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
									
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Theme Name', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
									
				$options[] = array(	'name' => __('Theme Name', 'bizzthemes'),
									'desc' => __('Rename this theme to whatever name you like.', 'bizzthemes'),
									'id' => $shortname.'_branding_back_name',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'text');
				
				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
				
				}
										
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Admin Bar', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
									
				$options[] = array(	'name' => __('Remove Theme Options', 'bizzthemes'),
									'desc' => __('Remove theme options from admin bar.', 'bizzthemes'),
									'label' => __('Remove theme options from admin bar', 'bizzthemes'),
									'id' => $shortname.'_admin_bar_options_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');

				$options[] = array(	'name' => __('Remove Admin Bar', 'bizzthemes'),
									'desc' => __('Remove admin bar altogether for both, logged in and logged out users.', 'bizzthemes'),
									'label' => __('Remove admin bar alltogether', 'bizzthemes'),
									'id' => $shortname.'_admin_bar_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');						
													
				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
									
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Login Screen', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
																		
				$options[] = array(	'name' => __('Login Logo', 'bizzthemes'),
									'desc' => __('Upload your image or paste the full URL address to it next to upload button. Choose small image (recommended dimension within 115x30px limits). <span class="important">Your upload will start after you save changes</span>', 'bizzthemes'),
									'id' => $shortname.'_branding_login_logo',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'upload');
																						
				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
									
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Hide Menu Labels', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
									
				$options[] = array(	'name' => __('Back-end Menu Labels', 'bizzthemes'),
									'desc' => __('If you are developing sites for your clients, it is important to lock your work after you are done. Hide your layout engine, custom designs, update notifications, API key and disable custom editor.', 'bizzthemes'),
									'type' => 'help');

				$options[] = array(	'label' => __('Hide template builder', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_layout',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide design options', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_design',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide license control', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_license',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide custom editor', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_editor',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide custom tools', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_tools',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide updates control', 'bizzthemes'),
									'id' => $shortname.'_adminmenu_version',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');

				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
									
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Hide Notifications', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ==' && $bizz_package != 'c3RhbmRhcmQ='){
									
				$options[] = array(	'name' => __('Hide notifications', 'bizzthemes'),
									'desc' => __('If you are developing sites for your clients, it is important to hide update notifications and stop them from nagging your clients.', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('Hide WordPress update notifications', 'bizzthemes'),
									'id' => $shortname.'_wp_update_notice_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Hide all theme notifications', 'bizzthemes'),
									'id' => $shortname.'_theme_notice_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
													
				} else {
								
				$options[] = array(	'name' => sprintf(__('To use these options, <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Agency Theme Package or Become a Club member</a>.', 'bizzthemes'), site_url()),
									'type' => 'help');
									
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
		$options[] = array(	'type' => 'maintablebreak');
						
	$options[] = array(	'type' => 'maintablebottom');

	$options[] = array(	'type' => 'maintabletop');
		
		/// SEO Options
				
		$options[] = array(	'name' => __('Complete SEO Control', 'bizzthemes'),
							'type' => 'heading');
						
			$options[] = array(	'name' => __('General Options', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){
									
				$options[] = array(	'name' => __('Deactivate theme SEO?', 'bizzthemes'),
									'label' => __('Disable default Bizz SEO', 'bizzthemes'),
									'desc' => __('In case you want to use another SEO plugin, you can disable the whole theme SEO controls.', 'bizzthemes'),
									'id' => $shortname.'_seo_remove',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Head <code>&lt;title&gt;</code> tags', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Site name in Title on inner pages?', 'bizzthemes'),
									'label' => __('Show site name in title (on your homepage). Example: Sitename', 'bizzthemes'),
									'desc' => sprintf(__('You may edit Site name (Blog Title) <a href="%s/wp-admin/options-general.php">here</a>.', 'bizzthemes'), site_url()),
									'id' => $shortname.'_title_title',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'name' => __('Tagline in Title on homepage?', 'bizzthemes'),
									'label' => __('Show site tagline in title (on your homepage). Example: Tagline|Sitename', 'bizzthemes'),
									'desc' => sprintf(__('You may edit Tagline <a href="%s/wp-admin/options-general.php">here</a>.', 'bizzthemes'), site_url()),
									'id' => $shortname.'_title_tagline',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Head <code>&lt;meta&gt;</code> tags', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Meta Description', 'bizzthemes'),
									'desc' => __('You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.', 'bizzthemes'),
									'id' => $shortname.'_meta_description',
									'std' => array(
										'value' => '', 
										'css' => ''
									),
									'type' => 'textarea');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Head <code>&lt;noindex&gt;</code> tags', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Options for <code>noindex</code> tag', 'bizzthemes'),
									'desc' => __('By adding <code>noindex</code> robot meta tag you are significantly improving your site SEO and prevent search engines from indexing very large database or pages that are very transitory. This way your are preventing spiders from indexing pages that only worsen your search results and keep you from ranking as well as you should.', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to category archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_category',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to tag archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_tag',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
				
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to author archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_author',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
				
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to daily archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_daily',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
				
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to monthly archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_monthly',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
				
				$options[] = array(	'label' => __('Add <code>&lt;noindex&gt;</code> to yearly archives.', 'bizzthemes'),
									'id' => $shortname.'_noindex_yearly',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
								
				$options[] = array(	'name' => __('Add <code>&lt;noindex&gt;</code> to checked pages.', 'bizzthemes'),
									'desc' => __('Check all pages you would like to hide from search engine spiders.', 'bizzthemes'),
									'type' => 'help');
												
				$options = pages_exclude_seo($options);
				
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.', 'bizzthemes'), site_url()),
									"type" => "help");
				
				}
					
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Head <code>&lt;noodp&gt;</code> <code>&lt;noydir&gt;</code> attributes', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Options for <code>noodp</code> <code>noydir</code> tag', 'bizzthemes'),
									'desc' => __('By adding <code>noodp</code> <code>noydir</code> robot meta tags you are preventing search engines from displaying Open Directory Project (DMOZ) and Yahoo! Directory listings in your meta descriptions.', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('Add <code>noodp</code> meta tag</code>', 'bizzthemes'),
									'id' => $shortname.'_noodp_meta',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');

				$options[] = array(	'label' => __('Add <code>noydir</code> meta tag</code>', 'bizzthemes'),
									'id' => $shortname.'_noydir_meta',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Link <code>&lt;nofollow&gt;</code> attributes', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Options for <code>nofollow</code> tag', 'bizzthemes'),
									'desc' => __('By adding <code>nofolow</code> rel attribute to specific links you are reducing the effectiveness of certain types of search engine spam, thereby improving the quality of search engine results and preventing spamdexing from occurring.', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('<code>nofollow</code> Home link</code>', 'bizzthemes'),
									'id' => $shortname.'_nofollow_home',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');

				$options[] = array(	'label' => __('<code>nofollow</code> Author Links</code>', 'bizzthemes'),
									'id' => $shortname.'_nofollow_author',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				$options[] = array(	'label' => __('<code>nofollow</code> Post Tags</code>', 'bizzthemes'),
									'id' => $shortname.'_nofollow_tags',
									'std' => array(
										'value' => false, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
			$options[] = array(	'name' => __('Canonical URLs', 'bizzthemes'),
								'toggle' => 'true',
								'type' => 'subheadingtop');
								
				if ($bizz_package != 'ZnJlZQ=='){

				$options[] = array(	'name' => __('Options for canonical URLs', 'bizzthemes'),
									'desc' => __('Canonical URL: the search engine friendly URL that you want the search engines to treat as authoritative.  In other words, a canonical URL is the URL that you want visitors to see.', 'bizzthemes'),
									'type' => 'help');
									
				$options[] = array(	'label' => __('Enable Canonical URLs', 'bizzthemes'),
									'id' => $shortname.'_canonical_url',
									'std' => array(
										'value' => true, 
										'css' => ''
									),
									'type' => 'checkbox');
									
				} else {
				
				$options[] = array(	"name" => sprintf(__('To use these options, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()),
									"type" => "help");
				
				}
						
			$options[] = array(	'type' => 'subheadingbottom');
			
		$options[] = array(	'type' => 'maintablebreak');
						
	$options[] = array(	'type' => 'maintablebottom');

}

/*
*** GLOBAL SEO OPTIONS
*/

add_filter( 'bizz_meta_boxes', 'bizz_seo_metaboxes' );
function bizz_seo_metaboxes( $meta_boxes ) {
	$prefix = 'bizzthemes_';
	
	$meta_boxes[] = array(
		'id' => 'bizzthemes_seo_meta',
		'title' => __('Bizz &rarr; SEO', 'bizzthemes'),
		'pages' => array( 'page', 'post' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('SEO Title', 'bizzthemes'),
				'desc' => __('Most search engines use a maximum of 60 chars.', 'bizzthemes'),
				'id' => $prefix . 'title',
				'type' => 'text_counter'
			),
			array(
				'name' => __('SEO Meta Description', 'bizzthemes'),
				'desc' => __('Most search engines use a maximum of 160 chars.', 'bizzthemes'),
				'id' => $prefix . 'description',
				'type' => 'textarea_counter'
			),
			array(
				'name' => __('Noindex this Post/Page', 'bizzthemes'),
				'desc' => __('Prevent search engines from indexing this post/page.', 'bizzthemes'),
				'id' => $prefix . 'noindex',
				'type' => 'checkbox'
			),
			array(
				'name' => __('301 Redirect', 'bizzthemes'),
				'desc' =>  __('Users will get redirected to the <acronym title="Uniform Resource Locator">URL</acronym> above, whenever they visit this post/page.', 'bizzthemes'),
				'id' => $prefix . 'redirect',
				'type' => 'text'
			),
		)
	);
	
	return $meta_boxes;
}

