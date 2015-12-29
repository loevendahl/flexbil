<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * class bizz_custom_editor (Some parts of code used from 'thesis_custom_editor' @Chris Pearson)
 *
 * @package bizzthemes
 * @since 6.0.0
 */
class bizz_custom_editor {
	function get_custom_files() {		
		$files = array();
		$directory = opendir(BIZZ_LIB_CUSTOM); // Open the directory
		$exts = array('.php', '.css', '.js', '.txt', '.inc', '.htaccess', '.html', '.htm'); // What type of files do we want?

		while ($file = readdir($directory)) { // Read the files
			if ($file != '.' && $file != '..' && $file != 'layout.css') { // Only list files within the _current_ directory
				$extension = substr($file, strrpos($file, '.')); // Get the extension of the file

				if ($extension && in_array($extension, $exts)) // Verify extension of the file; we can't edit images!
					$files[] = $file; // Add the file to the array
			}
		}

		closedir($directory); // Close the directory
		return $files; // Return the array of editable files
	}

	function is_custom_writable($file, $files) {
		if (!in_array($file, $files))
			$error = "<p><strong>" . __('Attention!', 'bizzthemes') . '</strong> ' . __('For security reasons, the file you are attempting to edit cannot be modified via this screen.', 'bizzthemes') . '</p>';
		elseif (!file_exists(BIZZ_LIB_CUSTOM)) // The custom/ directory does not exist
			$error = "<p><strong>" . __('Attention!', 'bizzthemes') . '</strong> ' . __('Your <code>custom/</code> directory does not appear to exist. Have you remembered to rename <code>/custom-sample</code>?', 'bizzthemes') . '</p>';
		elseif (!is_file(BIZZ_LIB_CUSTOM . '/' . $file)) // The selected file does not exist
			$error = "<p><strong>" . __('Attention!', 'bizzthemes') . '</strong> ' . __('The file you are attempting does not appear to exist.', 'bizzthemes') . '</p>';
		elseif (!is_writable(BIZZ_LIB_CUSTOM . '/custom.css')) // The selected file is not writable
			$error = "<p><strong>" . __('Attention!', 'bizzthemes') . '</strong> ' . sprintf(__('Your <code>/custom/%s</code> file is not writable by the server, and in order to modify the file via the admin panel, BizzThemes needs to be able to write to this file. All you have to do is set this file&#8217;s permissions to 666, and you&#8217;ll be good to go.', 'bizzthemes'), $file) . '</p>';

		if ( isset($error) ) { // Return the error + markup, if required
			$error = "<div class=\"warning\">\n\t$error\n</div>\n";
			return $error;
		}

		return false;
	}

	static function bizzthemes_editor() {				
		$custom_editor = new bizz_custom_editor;
?>
<div id="bizz_options" class="wrap<?php if (get_bloginfo('text_direction') == 'rtl') { echo ' rtl'; } ?>">
<?php

    // options header
	bizzthemes_options_header( $options_title = 'Custom Editor', $toggle = false );
	
	// filesystem: START
	// ---------------
	$url = wp_nonce_url('admin.php?page=bizz-editor');
	$form_fields = array ('custom_file_submit'); // this is a list of the form field contents I want passed along between page views
	$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
	if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
		// if we get here, then we don't have credentials yet,
		// but have just produced a form for the user to fill in, 
		// so stop processing for now
		return true; // stop the normal page form from displaying
	}
	// now we have some credentials, try to get the wp_filesystem running
	if ( ! WP_Filesystem($creds) ) {
		// our credentials were no good, ask the user for them again
		request_filesystem_credentials($url, $method, true, false, $form_fields);
		return true;
	}
	// by this point, the $wp_filesystem global should be working, so let's use it to create a file
	global $wp_filesystem;
	// ---------------
	// filesystem: END
	
	// save
	if ( isset($_GET['save']) && $_GET['save'] == 'true' ) {
		if (!current_user_can('edit_theme_options'))
			wp_die(__('Easy there, homey. You don&#8217;t have admin privileges to access theme options.', 'bizzthemes'));

		if (isset($_POST['custom_file_submit'])) {
			$contents = stripslashes($_POST['newcontent']); // Get new custom content
			$file = $_POST['file']; // Which file?
			$allowed_files = $custom_editor->get_custom_files(); // Get list of allowed files

			if (!in_array($file, $allowed_files)) // Is the file allowed? If not, get outta here!
				wp_die(__('You have attempted to modify an ineligible file. Only files within the BizzThemes <code>/custom</code> folder may be modified via this interface. Thank you.', 'bizzthemes'));
				
			$file_path = BIZZ_LIB_CUSTOM . '/' . $file;			
			if (!$wp_filesystem->put_contents($file_path, $contents))
				return false;

			$updated = '&updated=true'; // Display updated message
		}

		if (isset($_POST['custom_file_jump'])) {
			$file = $_POST['custom_files'];
			$updated = '';
		}
		
	}
	
	if (file_exists(BIZZ_LIB_CUSTOM)) {
		// Determine which file we're editing. Default to something harmless, like custom.css.
		$file = (isset($_GET['file'])) ? $_GET['file'] : 'custom.css';
		$files = $custom_editor->get_custom_files();
		$extension = substr($file, strrpos($file, '.'));

		// Determine if the custom file exists and is writable. Otherwise, this page is useless.
		$error = $custom_editor->is_custom_writable($file, $files);

		if ($error)
			echo $error;
		else {
			// Get contents of custom.css
			if (filesize(BIZZ_LIB_CUSTOM . '/' . $file) > 0) {
				$content = $wp_filesystem->get_contents(BIZZ_LIB_CUSTOM . '/' . $file);
				$content = htmlspecialchars($content);
			}
			else
				$content = '';
		}
?>
<div class="one_col">
	<h3><?php printf(__('Currently editing: <code>%s</code>', 'bizzthemes'), "custom/$file"); ?></h3>
	<p>
<?php
	if ($extension == '.php')
		echo "\t\t\t<div class=\"updated below-h2\"><p>" . __('<strong>Note:</strong> Make sure you have <acronym title="File Transfer Protocol">FTP</acronym> server access, before you start modifying <acronym title="PHP: Hypertext Preprocessor">PHP</acronym> files. Bad code will make your site temporarily unusable.', 'bizzthemes') . "</p></div>\n";
		
	if ( isset($_GET['save']) )
		echo "\t\t\t<div class=\"updated below-h2\"><p>" . __('File successfully updated.', 'bizzthemes') . "</p></div>\n";
?>
	<h2 class="nav-tab-wrapper">
<?php
		foreach ($files as $f) { // An option for each available file
			$selected = ($f == $file) ? ' nav-tab-active' : '';
?>
			<a class="nav-tab<?php echo $selected; ?>" href="<?php echo admin_url("admin.php?page=bizz-editor&file=$f"); ?>" title="Edit <?php echo $f; ?>"><?php echo $f; ?></a>
<?php
		}
?>
	</h2>
	</p>
		
	    <form class="file_editor" method="post" id="template" name="template" action="<?php echo admin_url("admin.php?page=bizz-editor&file=$file&save=true"); ?>">
			<input type="hidden" id="file" name="file" value="<?php echo $file; ?>" />
			<p><textarea id="newcontent" name="newcontent" rows="25" cols="50" class="large-text editor-area"><?php echo $content; ?></textarea></p>
			<p>
				<input type="submit" class="button button-primary" id="custom_file_submit" name="custom_file_submit" value="<?php _e('Save', 'bizzthemes'); ?>" />
			</p>
		</form>
		
</div>
<?php
	}
	else {
	    // echo '<div id="message" class="updated"><p>Rename your <code>custom-sample</code> folder to <code>custom</code>, otherwise theme will not work properly.</p></div>'."\n";
	}
	
	// options footer
	bizzthemes_options_footer();
?>
</div>
<?php
	}
}