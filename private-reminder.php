<?php
/*
Plugin Name: Private Reminder
Plugin URI: http://www.cvanmeer.nl/photo/plugins/private-reminder.zip
Description: Shows a reminder when your post is private
Version: 1.0
Author: Christian van Meer
Author URI: http://www.cvanmeer.nl/photo
*/

/*  Copyright 2009  PrivateReminder  (email : cvanmeer@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$pr_plugin_name    = 'Private Reminder';
$pr_plugin_version = '1.0';

function pr_activate()
{
	add_option('pr_reminder_text', 'Reminder: This post is a private post');
	add_option('pr_reminder_css', 'navBar');
}

function pr_deactivate()
{
	delete_option('pr_reminder_text');
	delete_option('pr_reminder_css');
}

function pr_admin_actions() 
{
	add_submenu_page('options-general.php', 'Private Reminder', 'Private Reminder', 10, __FILE__, 'pr_options');
}
	
function pr_options() 
{
	global $pr_plugin_name, $pr_plugin_version;
	
	if (isset($_POST['pr_admin_update'])) {
		update_option('pr_reminder_text', $_POST['pr_reminder_text']);
		update_option('pr_reminder_css', $_POST['pr_reminder_css']);

		echo '<div class="updated"><p><strong>Your options have been updated.</strong></p></div>';
	}

	?>
	
	<div class="wrap">
	<h2><?php echo $pr_plugin_name.' v '.$pr_plugin_version.' - Settings</h2>'; ?>

	<form method="post">
	<?php wp_nonce_field('update-options'); ?>

	<table class="form-table">

		<tr valign="top">
			<th scope="row">Reminder text:</th>
			<td><input type="text" name="pr_reminder_text" value="<?php echo get_option('pr_reminder_text'); ?>" size="50"/></td>
		</tr>
 
		<tr valign="top">
			<th scope="row">CSS Style:</th>
			<td><input type="text" name="pr_reminder_css" value="<?php echo get_option('pr_reminder_css'); ?>" /></td>
		</tr>

	</table>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="pr_reminder_text,pr_reminder_css" />

	<p class="submit">
		<input type="submit" name="pr_admin_update" class="button-primary" value="Save Changes" />
	</p>

	</form>
	</div>
	
	<?php
}

function pr_show()
{

	$queried_post = get_post($POST->id);
	if ($queried_post->post_status == "private")
	{
		?>
		<div class="<?php echo get_option('pr_reminder_css'); ?>">		
		<center><h2><?php echo get_option('pr_reminder_text'); ?></h2></center>
		</div>
		<?php
	}

}


register_activation_hook(__FILE__, 'pr_activate');
register_deactivation_hook(__FILE__, 'pr_deactivate');

add_action('loop_start', 'pr_show');
add_action('admin_menu', 'pr_admin_actions');

?>