<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add admin options page
function lffs_menu_page() {
    add_options_page( __( 'LFFS Options', 'lf-frontend-submission' ), __( 'LFFS Options', 'lf-frontend-submission' ), 'manage_options', 'lffs', 'lffs_options_page' );
}
add_action( 'admin_menu', 'lffs_menu_page' );

// add admin settings and such 
function lffs_admin_init() {
	add_settings_section( 'lffs-section', __( 'General', 'lf-frontend-submission' ), '', 'lffs' );

	add_settings_field( 'lffs-field', __( 'Remove', 'lf-frontend-submission' ), 'lffs_field_callback', 'lffs', 'lffs-section' );
	register_setting( 'lffs-options', 'lffs-setting', 'esc_attr' );

	add_settings_field( 'lffs-field-2', __( 'Module Submissions', 'lf-frontend-submission' ), 'lffs_field_callback_2', 'lffs', 'lffs-section' );
	register_setting( 'lffs-options', 'lffs-setting-2', 'esc_attr' );

	add_settings_field( 'lffs-field-3', __( 'Activate Reply', 'lf-frontend-submission' ), 'lffs_field_callback_3', 'lffs', 'lffs-section' );
	register_setting( 'lffs-options', 'lffs-setting-3', 'esc_attr' );

	add_settings_field( 'lffs-field-4', __( 'Privacy', 'lf-frontend-submission' ), 'lffs_field_callback_4', 'lffs', 'lffs-section' );
	register_setting( 'lffs-options', 'lffs-setting-4', 'esc_attr' );

	add_settings_field( 'lffs-field-19', __( 'Privacy', 'lf-frontend-submission' ), 'lffs_field_callback_19', 'lffs', 'lffs-section' );
	register_setting( 'lffs-options', 'lffs-setting-19', 'esc_attr' );

	add_settings_section( 'lffs-section-2', __( 'Labels', 'lf-frontend-submission' ), '', 'lffs' );

	add_settings_field( 'lffs-field-5', __( 'Name', 'lf-frontend-submission' ), 'lffs_field_callback_5', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-5', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-6', __( 'Email', 'lf-frontend-submission' ), 'lffs_field_callback_6', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-6', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-7', __( 'Subject', 'lf-frontend-submission' ), 'lffs_field_callback_7', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-7', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-8', __( 'Captcha', 'lf-frontend-submission' ), 'lffs_field_callback_8', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-8', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-9', __( 'Message', 'lf-frontend-submission' ), 'lffs_field_callback_9', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-9', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-18', __( 'Privacy', 'lf-frontend-submission' ), 'lffs_field_callback_18', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-18', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-10', __( 'Submit', 'lf-frontend-submission' ), 'lffs_field_callback_10', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-10', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-11', __( 'Error input field', 'lf-frontend-submission' ), 'lffs_field_callback_11', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-11', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-12', __( 'Error textarea', 'lf-frontend-submission' ), 'lffs_field_callback_12', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-12', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-13', __( 'Error email', 'lf-frontend-submission' ), 'lffs_field_callback_13', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-13', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-14', __( 'Error captcha', 'lf-frontend-submission' ), 'lffs_field_callback_14', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-14', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-15', __( 'Server error message', 'lf-frontend-submission' ), 'lffs_field_callback_15', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-15', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-16', __( 'Thank you message', 'lf-frontend-submission' ), 'lffs_field_callback_16', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-16', 'sanitize_text_field' );

	add_settings_field( 'lffs-field-17', __( 'Reply message', 'lf-frontend-submission' ), 'lffs_field_callback_17', 'lffs', 'lffs-section-2' );
	register_setting( 'lffs-options', 'lffs-setting-17', 'sanitize_text_field' );
}
add_action( 'admin_init', 'lffs_admin_init' );

function lffs_field_callback() {
	$value = esc_attr( get_option( 'lffs-setting' ) );
	?>
	<input type='hidden' name='lffs-setting' value='no'>
	<label><input type='checkbox' name='lffs-setting' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Do not delete form submissions and settings.', 'lf-frontend-submission' ); ?></label>
	<?php
}

function lffs_field_callback_2() {
	$value = esc_attr( get_option( 'lffs-setting-2' ) );
	?>
	<input type='hidden' name='lffs-setting-2' value='no'>
	<label><input type='checkbox' name='lffs-setting-2' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'List form submissions in dashboard.', 'lf-frontend-submission' ); ?></label>
	<?php
}

function lffs_field_callback_3() {
	$value = esc_attr( get_option( 'lffs-setting-3' ) );
	?>
	<input type='hidden' name='lffs-setting-3' value='no'>
	<label><input type='checkbox' name='lffs-setting-3' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Activate confirmation email to sender.', 'lf-frontend-submission' ); ?></label>
	<?php
}

function lffs_field_callback_4() {
	$value = esc_attr( get_option( 'lffs-setting-4' ) );
	?>
	<input type='hidden' name='lffs-setting-4' value='no'>
	<label><input type='checkbox' name='lffs-setting-4' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Activate privacy checkbox on form.', 'lf-frontend-submission' ); ?></label>
	<?php
}

function lffs_field_callback_19() {
	$value = esc_attr( get_option( 'lffs-setting-19' ) );
	?>
	<input type='hidden' name='lffs-setting-19' value='no'>
	<label><input type='checkbox' name='lffs-setting-19' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Disable collection of IP address.', 'lf-frontend-submission' ); ?></label>
	<?php
}

function lffs_field_callback_5() {
	$lffs_placeholder = esc_attr__( 'Name', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-5' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-5' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_6() {
	$lffs_placeholder = esc_attr__( 'Email', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-6' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-6' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_7() {
	$lffs_placeholder = esc_attr__( 'Subject', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-7' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-7' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_8() {
	$lffs_placeholder = esc_attr__( 'Enter number %s', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-8' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-8' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_9() {
	$lffs_placeholder = esc_attr__( 'Message', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-9' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-9' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_18() {
	$lffs_placeholder = esc_attr__( 'I consent to having this website collect my personal data via this form.', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-18' ) );
	echo "<input type='text' size='40' maxlength='200' name='lffs-setting-18' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_10() {
	$lffs_placeholder = esc_attr__( 'Submit', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-10' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-10' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_11() {
	$lffs_placeholder = esc_attr__( 'Please enter at least 2 characters', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-11' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-11' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_12() {
	$lffs_placeholder = esc_attr__( 'Please enter at least 10 characters', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-12' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-12' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_13() {
	$lffs_placeholder = esc_attr__( 'Please enter a valid email', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-13' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-13' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_14() {
	$lffs_placeholder = esc_attr__( 'Please enter the correct number', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-14' ) );
	echo "<input type='text' size='40' maxlength='50' name='lffs-setting-14' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_15() {
	$lffs_placeholder = esc_attr__( 'Error! Could not send form. This might be a server issue.', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-15' ) );
	echo "<input type='text' size='40' maxlength='200' name='lffs-setting-15' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_16() {
	$lffs_placeholder = esc_attr__( 'Thank you! You will receive a response as soon as possible.', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-16' ) );
	echo "<input type='text' size='40' maxlength='200' name='lffs-setting-16' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

function lffs_field_callback_17() {
	$lffs_placeholder = esc_attr__( 'Thank you! You will receive a response as soon as possible.', 'lf-frontend-submission' ); 
	$lffs_setting = esc_attr( get_option( 'lffs-setting-17' ) );
	echo "<input type='text' size='40' maxlength='200' name='lffs-setting-17' placeholder='$lffs_placeholder' value='$lffs_setting' />";
}

// display admin options page
function lffs_options_page() {
?>
<div class="wrap"> 
	<div id="icon-plugins" class="icon32"></div> 
	<h1><?php _e( 'Lf Front End Submission', 'lf-frontend-submission' ); ?></h1> 
	<form action="options.php" method="POST">
	<?php settings_fields( 'lffs-options' ); ?>
	<?php do_settings_sections( 'lffs' ); ?>
	<?php submit_button(); ?>
	</form> 
</div>
<?php
}
