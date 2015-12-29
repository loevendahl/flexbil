<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * AUTOMATIC FRAMEWORK UPDATING
 *
 * update your theme framework automatically
 * @original Addon Author URI: http://woothemes.com
 * @since 7.0
 */
 
/* Framework updates header */
/*------------------------------------------------------------------*/
function bizzthemes_framework_update_head(){
	global $wp_filesystem, $themeid;
	
    if( isset($_REQUEST['page']) && $_REQUEST['page'] == 'bizz-update' ){
              
		// Setup Filesystem 
		$method = get_filesystem_method(); 
		
		// Get ftp credentials or use WP filesystem
		if(isset($_POST['bizz_ftp_cred'])){ 
			$cred = unserialize(base64_decode($_POST['bizz_ftp_cred']));
			$filesystem = WP_Filesystem($cred);
		} 
		else {
		   $filesystem = WP_Filesystem(); 
		}  
	
		// Filesystem preventing downloads
		if($filesystem == false && $_POST['upgrade'] != 'Proceed'){
			function bizzthemes_framework_update_filesystem_warning() {
				$method = get_filesystem_method();
				echo "<div id='filesystem-warning' class='updated fade'><p>Failed: Filesystem preventing downloads. (". $method .")</p></div>";
			}
			add_action('admin_notices', 'bizzthemes_framework_update_filesystem_warning');
			return;
		}
		
		// Upgrade
		if( isset($_REQUEST['bizz_update_save']) && $_REQUEST['bizz_update_save'] == 'save' ){
		
			if ( isset($_REQUEST['bizz_update_theme']) ){
				delete_transient('remote_f_version_' . $themeid);
				delete_transient('remote_t_version_' . $themeid);
				$temp_file_addr = download_url('http://www.bizzthemes.com/files/'.strtolower($themeid).'.zip');
				$to = $wp_filesystem->wp_content_dir() . "/themes/";
			}
			elseif ( isset($_REQUEST['bizz_update_frame']) ){
				delete_transient('remote_f_version_' . $themeid);
				$temp_file_addr = download_url('http://www.bizzthemes.com/framework/lib_frame.zip');
				$to = $wp_filesystem->wp_content_dir() . "/themes/" . get_option('template') . "/lib_frame/";
			}
		
		    // Error with upgade
			if ( is_wp_error($temp_file_addr) ) {
			    $error = $temp_file_addr->get_error_code();
				if($error == 'http_no_url') {
				    //The source file was not found or is invalid
					function bizzthemes_framework_update_missing_source_warning() {
					    echo "<div id='source-warning' class='updated fade'><p>Failed: Invalid URL Provided</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_missing_source_warning');
				}
				else {
				    function bizzthemes_framework_update_other_upload_warning() {
					    echo "<div id='source-warning' class='updated fade'><p>Failed: Upload - $error</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_other_upload_warning');
				}
				return;
			}
			
			//Unzipp it
			$dounzip = unzip_file($temp_file_addr, $to);
			unlink($temp_file_addr); // Delete Temp File
			
			if ( is_wp_error($dounzip) ) {
			
			    //DEBUG
				$error = $dounzip->get_error_code();
				$data = $dounzip->get_error_data($error);
				// echo $error. ' - ';
				// print_r($data);
				
				if($error == 'incompatible_archive') {
				    //The source file was not found or is invalid
				    function bizzthemes_framework_update_no_archive_warning() {
					    echo "<div id='bizz-no-archive-warning' class='updated fade'><p>Failed: Incompatible archive</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_no_archive_warning');
				}
				if($error == 'empty_archive') {
				    function bizzthemes_framework_update_empty_archive_warning() {
					    echo "<div id='bizz-empty-archive-warning' class='updated fade'><p>Failed: Empty Archive</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_empty_archive_warning');
				}
				if($error == 'mkdir_failed') {
				    function bizzthemes_framework_update_mkdir_warning() {
					    echo "<div id='bizz-mkdir-warning' class='updated fade'><p>Failed: mkdir Failure</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_mkdir_warning');
				}
				if($error == 'copy_failed') {
				    function bizzthemes_framework_update_copy_fail_warning() {
					    echo "<div id='bizz-copy-fail-warning' class='updated fade'><p>Failed: Copy Failed</p></div>";
					}
					add_action('admin_notices', 'bizzthemes_framework_update_copy_fail_warning');
				}
				return;
				
			}
			
			// Force refresh of theme update information
			wp_clean_themes_cache();
			
			function bizzthemes_framework_updated_success() {
			    echo "<div id='framework-upgraded' class='updated fade'><p>New files successfully downloaded, extracted and updated.</p></div>";
			}
			add_action('admin_notices', 'bizzthemes_framework_updated_success');
		
	    }
	} //End user input save part of the update

}                     
add_action('admin_head','bizzthemes_framework_update_head');

/* Framework updates page */
/*------------------------------------------------------------------*/
function bizzthemes_framework_update_page(){
    global $bizz_package, $themeid, $frameversion, $bloghomeurl;
		
	/* WP Filesystem
	------------------------------------*/
	$method = get_filesystem_method();	
    if ( isset($_POST['password']) ) {
        $cred 		= $_POST;
        $filesystem = WP_Filesystem($cred);
    }
    elseif ( isset($_POST['bizz_ftp_cred']) ) {
        $cred 		= unserialize(base64_decode($_POST['bizz_ftp_cred']));
        $filesystem = WP_Filesystem($cred);  
    }
	else
        $filesystem = WP_Filesystem(); 
	
	/* URL redirect
	------------------------------------*/
    $url = admin_url('admin.php?page=bizz-update');
		
	echo "\t<div class=\"wrap themes-page\">\n";
	
	// options header
	bizzthemes_options_header( $options_title = 'Version Control', $toggle = false );
		
    if ( $filesystem == false )
	    request_filesystem_credentials ( $url );
	elseif ( $bizz_package == 'ZnJlZQ==' ) {
		
		echo "\t\t<span style=\"display:none\">" . $method . "</span>\n";
            			
			// CONNECTION ALERT
			echo "\t\t<h2>" . __('Updates Control Panel', 'bizzthemes') . "</h2>\n";
			echo "\t\t\t<div class=\"error below-h2\"><p>" . sprintf(__('To use automatic framework updating system, please <a href="%s/wp-admin/admin.php?page=bizz-license">Upgrade to Standard or Agency Theme Package</a>.'), site_url()) . "</p></div>\n";
					
		// options footer
		bizzthemes_options_footer();
	
	}
	elseif ( is_child_theme() ) {
		echo 'Your are using the child theme that needs to be manually maintained.';
	}
	else {
			
		echo "\t\t<span style=\"display:none\">" . $method . "</span>\n";
		echo "\t\t<form method=\"post\"  enctype=\"multipart/form-data\" id=\"bizzform\" action=\"\">\n";
            
			wp_nonce_field('update-options');
			
			// THEME UPDATES
			echo "\t\t<h2>" . __('Theme Update Control', 'bizzthemes') . "</h2>\n";
			
			if ( function_exists('wp_get_theme') ) {
				$this_theme_data = wp_get_theme();
				$this_theme_version = $this_theme_data->Version;
			}
			else {
				$this_theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
				$this_theme_version = $this_theme_data['Version'];
			}
			$remote_changelog = 'http://demo.bizzthemes.com/'.strtolower($themeid).'/wp-content/themes/'.strtolower($themeid).'/lib_theme/changelog.txt';
			$remote_theme = 'http://bizzthemes.com/files/'.strtolower($themeid).'.zip';
			$remote_theme_version = get_transient('remote_t_version_' . $themeid);
			
			echo "\t\t\t<ul class=\"file-download\">\n";
			echo "\t\t\t<li>Your theme version: <b>$this_theme_version</b></li>\n";
			echo "\t\t\t<li>Current theme version: <b>$remote_theme_version</b></li>\n";
			echo "\t\t\t</ul>\n";
			if ($remote_theme_version != 'Currently Unavailable') {
			echo "\t\t\t<ul class=\"file-download\">\n";
			echo "\t\t\t<li><a href=\"$remote_changelog\" class=\"button\">View Theme Changelog</a></li>\n";
			echo "\t\t\t</ul>\n";
			}
			
			$this_theme_version = trim(str_replace('.','',$this_theme_version));
            $remote_theme_version = trim(str_replace('.','',$remote_theme_version));

            if(strlen($this_theme_version) == 2) { $this_theme_version = $this_theme_version . '0'; }
            if(strlen($remote_theme_version) == 2) { $remote_theme_version = $remote_theme_version . '0'; }

			if($this_theme_version < $remote_theme_version && $remote_theme_version != 'Currently Unavailable') {
			    
				echo "\t\t\t<div class=\"error below-h2\"><p>" . __('Theme update is available.', 'bizzthemes') . "</p></div>\n";
				echo "\t\t\t<p>" . __('To update your theme go through following steps:', 'bizzthemes') . "</p>\n";
				echo "\t\t\t<ol class=\"file-download\">\n";
				echo "\t\t\t<li>". __('Backup your old theme files and database (preferably via FTP).', 'bizzthemes') . "</li>\n";
				echo "\t\t\t<li>". __('Make sure your definitely backup &#8217;custom&#8217; folder in root theme directory.', 'bizzthemes') . "</li>\n";
				echo "\t\t\t<li>" . __('Update all theme files by clicking the button bellow.', 'bizzthemes') . "</li>\n";
				echo "\t\t\t<li>". __('That&#8217;s it!', 'bizzthemes') . "</li>\n";
				echo "\t\t\t</ol>\n";
				echo '<input type="submit" class="button" name=\"bizz_update_theme\" value="'.__('Update Theme', 'bizzthemes').'" onclick="return confirm(\''.__('Old theme files will be lost! Click OK to proceed.', 'bizzthemes').'\');" />'."\n";
				
				echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_theme\" value=\"upgrade\" />\n";
				echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_save\" value=\"save\" />\n";
				echo "\t\t\t<input type=\"hidden\" name=\"bizz_ftp_cred\" value=\"" . base64_encode(serialize($_POST)) . "\" />\n";
			
			}
			else {
			    
				if ($remote_theme_version != 'Currently Unavailable')
					echo "\t\t\t<div class=\"updated below-h2\"><p>" . __('You are using the latest theme version.', 'bizzthemes') . "</p></div>\n"; 
				
				// FRAMEWORK UPDATES
				echo "\t\t<h2>" . __('Framework Update Control', 'bizzthemes') . "</h2>\n";
				
				$localversion = $frameversion;
				$remoteversion = get_transient('remote_f_version_' . $themeid);
				$fw_remote_changelog = 'http://www.bizzthemes.com/framework/changelog.txt';
				$fw_remote_frame = 'http://www.bizzthemes.com/framework/lib_frame.zip';
					
				echo "\t\t\t<ul class=\"file-download\">\n";
				echo "\t\t\t<li>Your frame version: <b>$localversion</b></li>\n";
				echo "\t\t\t<li>Current frame version: <b>$remoteversion</b></li>\n";
				echo "\t\t\t</ul>\n";
				echo "\t\t\t<ul class=\"file-download\">\n";
				echo "\t\t\t<li><a href=\"$fw_remote_changelog\" class=\"button\">View Framework Changelog</a></li>\n";
				echo "\t\t\t</ul>\n";
					
				$localversion = trim(str_replace('.','',$localversion));
				$remoteversion = trim(str_replace('.','',$remoteversion));

				if(strlen($localversion) == 2){$localversion = $localversion . '0'; }
				if(strlen($remoteversion) == 2){$remoteversion = $remoteversion . '0'; }
				
				if($localversion == $remoteversion) {
					echo '<input class="button" type="submit" value="'.__('Re-install Frame', 'bizzthemes').'" onclick="return confirm(\''.__('Old theme files will be lost! Click OK to proceed.', 'bizzthemes').'\');" />'."\n";
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_save\" value=\"save\" />\n";
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_frame\" value=\"upgrade\" />\n";
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_ftp_cred\" value=\"" . base64_encode(serialize($_POST)) . "\" />\n";
					echo "\t\t\t<a class=\"button\" href=\"$fw_remote_frame\">".__('Download', 'bizzthemes')."</a>\n";
					echo "\t\t\t<br/><br/>\n";
				}
				
				if($localversion < $remoteversion) {
					
					echo "\t\t\t<div class=\"error below-h2\"><p>" . __('Framework update is available.', 'bizzthemes') . "</p></div>\n";
					echo "\t\t\t<p>" . __('To update your theme framework go through following steps:', 'bizzthemes') . "</p>\n";
					echo "\t\t\t<ol class=\"file-download\">\n";
					echo "\t\t\t<li>" . __('Backup your old theme files and database.', 'bizzthemes') . "</li>\n";
					echo "\t\t\t<li>" . __('Update all files in lib_frame folder with new ones by clicking the button bellow.', 'bizzthemes') . "</li>\n";
					echo "\t\t\t<li>" . __('That&#8217;s it!', 'bizzthemes') . "</li>\n";
					echo "\t\t\t</ol>\n";
					echo "\t\t\t<input class=\"button\" type=\"submit\" value=\"Update Framework\" />\n";
					
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_save\" value=\"save\" />\n";
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_update_frame\" value=\"upgrade\" />\n";
					echo "\t\t\t<input type=\"hidden\" name=\"bizz_ftp_cred\" value=\"" . base64_encode(serialize($_POST)) . "\" />\n";
				
				}
				else
					echo "\t\t\t<div class=\"updated below-h2\"><p>" . __('You are using the latest framework version.', 'bizzthemes') . "</p></div>\n"; 
	
			}
			
		echo "\t\t</form>\n";
	
	}
	
	// options footer
	bizzthemes_options_footer();
	
    echo "\t</div>\n";
	
}

/* Framework updates periodical check */
/*------------------------------------------------------------------*/
add_action('admin_head','bizzthemes_updates_check');

function bizzthemes_updates_check() {
	global $themeid, $frameversion;
	
	// print_r( get_transient('remote_f_version_' . $themeid) );
	// delete_transient('remote_f_version_' . $themeid);
	// print_r( get_transient('remote_t_version_' . $themeid) );
	// delete_transient('remote_t_version_' . $themeid);
	
	/* Transient check (remote framework)
	------------------------------------*/
	if ( false === ( $remote_f_version = get_transient( 'remote_f_version_' . $themeid ) ) && !isset($_GET['activated']) ) {
		if ( bizz_is_online() ) {
			$remote_fw_changelog = 'http://www.bizzthemes.com/framework/changelog.txt';
			$remote_fw_version = bizz_get_fw_version($remote_fw_changelog);
			set_transient( 'remote_f_version_' . $themeid, $remote_fw_version, 16*3600 ); #check every 16 hours
		}
		else
			set_transient( 'remote_f_version_' . $themeid, $frameversion, 600 );
	}
	/* Transient check (remote theme)
	------------------------------------*/
	if ( false === ( $remote_t_version = get_transient( 'remote_t_version_' . $themeid ) ) && !isset($_GET['activated']) ) {
		if ( bizz_is_online() ) {
			$remote_th_changelog = 'http://demo.bizzthemes.com/'.strtolower($themeid).'/wp-content/themes/'.strtolower($themeid).'/lib_theme/changelog.txt';
			$remote_th_version = bizz_get_fw_version($remote_th_changelog);
			set_transient( 'remote_t_version_' . $themeid, $remote_th_version, 24*3600 ); #check every 24 hours
		}
		else {
			if ( function_exists('wp_get_theme') ) {
				$this_th_data = wp_get_theme();
				$this_th_version = $this_th_data->Version;
			}
			else {
				$this_th_data = get_theme_data(TEMPLATEPATH . '/style.css');
				$this_th_version = $this_th_data['Version'];
			}
			set_transient( 'remote_t_version_' . $themeid, $this_th_version, 600 );
		}
	}
		
}

/* Get framework version */
/*------------------------------------------------------------------*/
function bizz_get_fw_version($url = ''){
	
	// Changelog URL?
	if(!empty($url))
		$fw_url = $url;
	else
    	$fw_url = 'http://www.bizzthemes.com/framework/changelog.txt';
		
    // Loop thorugh changelog
	$temp_file_addr = download_url($fw_url);
	if(!is_wp_error($temp_file_addr) && $file_contents = file($temp_file_addr)) {
        foreach ($file_contents as $line_num => $line) { 
			$current_line =  $line;
			if($line_num > 1){    # not the first or second... dodgy :P
				if (preg_match('/^[0-9]/', $line)) {
					$current_line = stristr($current_line,"version");
					$current_line = preg_replace('~[^0-9,.]~','',$current_line);
					$output = $current_line;
					break;
				}
			}
        }
        unlink($temp_file_addr);
        return $output;
    }
	else
        return 'Currently Unavailable';

}

