<?php

function wfpn_notification_settings()
{ ?>
<div id="wfpn" class="wrap nosubsub">
    <h1 class="wp-heading-inline" style="margin-bottom: 20px; padding-left: 50px">Settings</h1>
    <hr class="wp-header-end">
	<div class="content">
		<?php settings_errors(); ?>
		<div class="accordion heading" >
			<h5>General Settings</h5>
		</div>
		<div class="panel">
			<form method="post" action="options.php" id="api_key_form">
				<?php
					settings_fields('wfpn_notify_fields');
					do_settings_sections('wfpn_notify_settings');
					// submit_button();
					?>
			</form>
		</div>
		<div class="accordion heading" >
			<h5>Welcome Notification</h5>
		</div>
		<div class="panel">
			<form method="post" action="options.php" id="welcome_notify_form">
				<?php
					settings_fields('wfpn_notify_welcome_fields');
					do_settings_sections('wfpn_notify_welcome_settings');
					// submit_button();
					?>
			</form>
		</div>
		<div class="accordion heading" >
			<h5>New Post Notification</h5>
		</div>
		<div class="panel">
			<form method="post" action="options.php" id="new_post_notify_form">
				<?php
					settings_fields('wfpn_notify_new_post_fields');
					do_settings_sections('wfpn_notify_new_post_settings');
					// submit_button();
					?>
			</form>
		</div>
	</div>

	<?php }

function wfpn_notify_settings()
{
	register_setting('wfpn_notify_fields', 'notify_firebase_key');
	add_settings_section('wfpn_notify_fields', '', '', 'wfpn_notify_settings');

	add_settings_field('activate-notify-settings', '', 'wfpn_notify_api_key', 'wfpn_notify_settings', 'wfpn_notify_fields');
}
add_action('admin_init', 'wfpn_notify_settings');
function wfpn_notify_welcome_settings()
	{
		register_setting('wfpn_notify_welcome_fields', 'notify_welcome_title');
		register_setting('wfpn_notify_welcome_fields', 'notify_welcome_enable');
		register_setting('wfpn_notify_welcome_fields', 'notify_welcome_message');
		add_settings_section('wfpn_notify_welcome_fields', '', '', 'wfpn_notify_welcome_settings');

		add_settings_field('activate-notify-welcome-settings', '', 'wfpn_notify_welcome', 'wfpn_notify_welcome_settings', 'wfpn_notify_welcome_fields');
	}
	add_action('admin_init', 'wfpn_notify_welcome_settings');
	function wfpn_notify_new_post_settings()
	{
		register_setting('wfpn_notify_new_post_fields', 'notify_new_post_enable');
		register_setting('wfpn_notify_new_post_fields', 'notify_new_post_title');
		register_setting('wfpn_notify_new_post_fields', 'notify_new_post_message');
		add_settings_section('wfpn_notify_new_post_fields', '', '', 'wfpn_notify_new_post_settings');

		add_settings_field('activate-notify-welcome-settings', '', 'wfpn_notify_new_post', 'wfpn_notify_new_post_settings', 'wfpn_notify_new_post_fields');
	}
	add_action('admin_init', 'wfpn_notify_new_post_settings');
function wfpn_notify_api_key()
	{
		echo '<div class="row">';
		echo '<label >Firebase API Key : </label>';
		echo '<input type="text" style="width: 35%; " id="notify_firebase_key" name="notify_firebase_key" value="' . esc_attr(get_option('notify_firebase_key')) . '" />';
		echo '</div>';
		echo '<div class="row">';
		echo '<label></label>';
		submit_button();
		echo '<div>';
	}
	function wfpn_notify_welcome()
	{	 ?>
		<div class="row">

			<label>Enable Welcome Notification for New Users : </label>
			<label class="switch">
				<input type="checkbox" name="notify_welcome_enable" id="notify_welcome_enable" value="1" <?php checked( '1', get_option( 'notify_welcome_enable' ) ); ?>>
				<span class="slider round"></span>
			</label>
		</div>
		<div class="row">
			<label >Welcome Notification Title: </label>
			<input type="text" style="width: 35%; " id="notify_welcome_title" name="notify_welcome_title" value="<?php echo (esc_attr(get_option('notify_welcome_title')) ? esc_attr(get_option('notify_welcome_title')) : 'Welcome') ?>" />
		</div>
		<div class="row">
			<label >Welcome Notification Message: </label>
			<textarea type="text" style="width: 35%; " id="notify_welcome_message" name="notify_welcome_message" value=""><?php echo (esc_attr(get_option('notify_welcome_message')) ? esc_attr(get_option('notify_welcome_message')) : 'Welcome to our App') ?></textarea>
		</div>
		<div class="row">
			<label></label>
			<?php submit_button(); ?>
		<div>

	<?php
	}
	function wfpn_notify_new_post()
	{	 ?>
		<div class="row">

			<label>Enable New Post Notification for New Users : </label>
			<label class="switch">
				<input type="checkbox" name="notify_new_post_enable" id="notify_new_post_enable" value="1" <?php checked( '1', get_option( 'notify_new_post_enable' ) ); ?>>
				<span class="slider round"></span>
			</label>
		</div>
		<div class="row">
			<label >New Post Notification Title: </label>
			<input type="text" style="width: 35%; " id="notify_new_post_title" name="notify_new_post_title" value="<?php echo (esc_attr(get_option('notify_new_post_title')) ? esc_attr(get_option('notify_new_post_title')) : 'New Post') ?>" />
		</div>
		<div class="row">
			<label >New Post Notification Message: </label>
			<textarea type="text" style="width: 35%; " id="notify_new_post_message" name="notify_new_post_message" value=""><?php echo (esc_attr(get_option('notify_new_post_message')) ? esc_attr(get_option('notify_new_post_message')) : 'Checkout our new post') ?></textarea>
		</div>
		<div class="row">
			<label></label>
			<?php submit_button(); ?>
		<div>
		<script>
			
			var acc = document.getElementsByClassName("accordion");
			var i;

			for (i = 0; i < acc.length; i++) {
			acc[i].addEventListener("click", function() {
				/* Toggle between adding and removing the "active" class,
				to highlight the button that controls the panel */
				this.classList.toggle("active");

				/* Toggle between hiding and showing the active panel */
				var panel = this.nextElementSibling;
				if (panel.style.display === "block") {
				panel.style.display = "none";
				} else {
				panel.style.display = "block";
				}
			});
			}
		</script>																							
	<?php
	}