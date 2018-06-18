<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// start session for captcha validation
if (!isset ($_SESSION)) session_start(); 
$_SESSION['lffs-rand'] = isset($_SESSION['lffs-rand']) ? $_SESSION['lffs-rand'] : rand(100, 999);

// the shortcode
function lffs_shortcode($lffs_atts) {
	$lffs_atts = shortcode_atts( array( 
		"email_to" => get_bloginfo('admin_email'),
		"from_header" => lffs_from_header(),
		"subject" => '',
		"hide_subject" => '',
		"auto_reply" => '',
		"auto_reply_message" => '',
		"message_success" => '',
		"scroll_to_form" => ''
	), $lffs_atts);

	// get custom settings from settingspage
	$list_submissions_setting = esc_attr(get_option('lffs-setting-2'));
	$auto_reply_setting = esc_attr(get_option('lffs-setting-3'));
	$privacy_setting = esc_attr(get_option('lffs-setting-4'));
	$ip_address_setting = esc_attr(get_option('lffs-setting-19'));

	// get custom messages from settingspage
	$server_error_message = esc_attr(get_option('lffs-setting-15'));
	$thank_you_message = esc_attr(get_option('lffs-setting-16'));
	$auto_reply_message = esc_attr(get_option('lffs-setting-17'));

	// get custom labels from settingspage
	$name_label = esc_attr(get_option('lffs-setting-5'));
	$email_label = esc_attr(get_option('lffs-setting-6'));
	$subject_label = esc_attr(get_option('lffs-setting-7'));
	$captcha_label = esc_attr(get_option('lffs-setting-8'));
	$message_label = esc_attr(get_option('lffs-setting-9'));
	$privacy_label = esc_attr(get_option('lffs-setting-18'));
	$submit_label = esc_attr(get_option('lffs-setting-10'));
	$error_input_label = esc_attr(get_option('lffs-setting-11'));
	$error_textarea_label = esc_attr(get_option('lffs-setting-12'));
	$error_email_label = esc_attr(get_option('lffs-setting-13'));
	$error_captcha_label = esc_attr(get_option('lffs-setting-14'));

	// show default label if no custom label is set
	if (empty($name_label)) {
		$name_label = esc_attr__( 'Name', 'lf-frontend-submission' );
	}
	if (empty($email_label)) {
		$email_label = esc_attr__( 'Email', 'lf-frontend-submission' );
	}
	if (empty($subject_label)) {
		$subject_label = esc_attr__( 'Subject', 'lf-frontend-submission' );
	}
	if (empty($captcha_label)) {
		$captcha_label = esc_attr__( 'Enter number %s', 'lf-frontend-submission' );
	}
	if (empty($message_label)) {
		$message_label = esc_attr__( 'Message', 'lf-frontend-submission' );
	}
	if (empty($privacy_label)) {
		$privacy_label = esc_attr__( 'I consent to having this website collect my personal data via this form.', 'lf-frontend-submission' );
	}
	if (empty($submit_label)) {
		$submit_label = esc_attr__( 'Submit', 'lf-frontend-submission' );
	}
	if (empty($error_input_label)) {
		$error_input_label = esc_attr__( 'Please enter at least 2 characters', 'lf-frontend-submission' );
	}
	if (empty($error_textarea_label)) {
		$error_textarea_label = esc_attr__( 'Please enter at least 10 characters', 'lf-frontend-submission' );
	}
	if (empty($error_email_label)) {
		$error_email_label = esc_attr__( 'Please enter a valid email', 'lf-frontend-submission' );
	}
	if (empty($error_captcha_label)) {
		$error_captcha_label = esc_attr__( 'Please enter the correct number', 'lf-frontend-submission' );
	}

	// show default message if no custom message is set
	if (empty($server_error_message)) {
		$server_error_message = esc_attr__( 'Error! Could not send form. This might be a server issue.', 'lf-frontend-submission' );
	}

	// thank you message
	$value = $thank_you_message;
	if (empty($lffs_atts['message_success'])) {
		if (empty($value)) {
			$thank_you_message = esc_attr__( 'Thank you! You will receive a response as soon as possible.', 'lf-frontend-submission' );
		} else {
			$thank_you_message = $value;
		}
	} else {
		$thank_you_message = $lffs_atts['message_success'];
	}

	// auto reply message
	$value = $auto_reply_message;
	if (empty($lffs_atts['auto_reply_message'])) {
		if (empty($value)) {
			$auto_reply_message = esc_attr__( 'Thank you! You will receive a response as soon as possible.', 'lf-frontend-submission' );
		} else {
			$auto_reply_message = $value;
		}
	} else {
		$auto_reply_message = $lffs_atts['auto_reply_message'];
	}

	// set variables 
	$form_data = array(
		'form_name' => '',
		'form_email' => '',
		'form_subject' => '',
		'form_captcha' => '',
		'form_privacy' => '',
		'form_firstname' => '',
		'form_lastname' => '',
		'form_message' => ''
	);
	$error = false;
	$sent = false;
	$fail = false;

	// processing form
	if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['lffs_send']) && isset( $_POST['lffs_nonce'] ) && wp_verify_nonce( $_POST['lffs_nonce'], 'lffs_nonce_action' ) ) {
		// sanitize content
		$post_data = array(
			'form_name' => sanitize_text_field($_POST['lffs_name']),
			'form_email' => sanitize_email($_POST['lffs_email']),
			'form_subject' => sanitize_text_field($_POST['lffs_subject']),
			'form_message' => wp_kses_post($_POST['lffs_message']),
			'form_captcha' => sanitize_text_field($_POST['lffs_captcha']),
			'form_privacy' => sanitize_key($_POST['lffs_privacy']),
			'form_firstname' => sanitize_text_field($_POST['lffs_firstname']),
			'form_lastname' => sanitize_text_field($_POST['lffs_lastname'])
		);

		// validate name
		$value = $post_data['form_name'];
		if ( strlen($value)<2 ) {
			$error_class['form_name'] = true;
			$error = true;
		}
		$form_data['form_name'] = $value;

		// validate email
		$value = $post_data['form_email'];
		if ( empty($value) ) {
			$error_class['form_email'] = true;
			$error = true;
		}
		$form_data['form_email'] = $value;

		// validate subject
		if ($lffs_atts['hide_subject'] != "true") {		
			$value = $post_data['form_subject'];
			if ( strlen($value)<2 ) {
				$error_class['form_subject'] = true;
				$error = true;
			}
			$form_data['form_subject'] = $value;
		}

		// validate message
		$value = $post_data['form_message'];
		if ( strlen($value)<10 ) {
			$error_class['form_message'] = true;
			$error = true;
		}
		$form_data['form_message'] = $value;

		// validate captcha
		$value = $post_data['form_captcha'];
		if ( $value != $_SESSION['lffs-rand'] ) { 
			$error_class['form_captcha'] = true;
			$error = true;
		}
		$form_data['form_captcha'] = $value;

		// validate first honeypot field
		$value = $post_data['form_firstname'];
		if ( strlen($value)>0 ) {
			$error = true;
		}
		$form_data['form_firstname'] = $value;

		// validate second honeypot field
		$value = $post_data['form_lastname'];
		if ( strlen($value)>0 ) {
			$error = true;
		}
		$form_data['form_lastname'] = $value;

		// validate privacy
		if ($privacy_setting == "yes") {
			$value = $post_data['form_privacy'];
			if ( $value !=  "yes" ) {
				$error_class['form_privacy'] = true;
				$error = true;
			}
			$form_data['form_privacy'] = $value;
		}

		// sending and saving form submission
		if ($error == false) {
			// hook to support plugin Contact Form DB
			do_action( 'lffs_before_send_mail', $form_data );
			// email admin
			$to = $lffs_atts['email_to'];
			// from email header
			$from = $lffs_atts['from_header'];
			// subject
			if (!empty($lffs_atts['subject'])) {	
				$subject = $lffs_atts['subject'];
			} elseif ($lffs_atts['hide_subject'] != "true") {
				$subject = "(".get_bloginfo('name').") " . $form_data['form_subject'];
			} else {
				$subject = get_bloginfo('name');
			}
			// auto reply to sender
			if ($lffs_atts['auto_reply'] == "true") {
				$auto_reply = true;
			} elseif ($lffs_atts['auto_reply'] == "false") {
				$auto_reply = false;
			} elseif ($auto_reply_setting == "yes") {
				$auto_reply = true;
			} else {
				$auto_reply = false;
			}
			// set consent
			$value = $post_data['form_privacy'];
			if ( $value ==  "yes" ) {
				$consent = esc_attr__( 'Yes', 'lf-frontend-submission' );
			} else {
				$consent = esc_attr__( 'No', 'lf-frontend-submission' );
			}
			// show or hide ip address
			if ($ip_address_setting == "yes") {
				$ip_address = '';
			} else {
				$ip_address = sprintf( esc_attr__( 'IP: %s', 'lf-frontend-submission' ), lffs_get_the_ip() );
			}
			// save form submission in database
			if ($list_submissions_setting == "yes") {
				$lffs_post_information = array(
					'post_title' => esc_attr($subject),
					'post_content' => $form_data['form_name'] . "\r\n\r\n" . $form_data['form_email'] . "\r\n\r\n" . $form_data['form_message'] . "\r\n\r\n" . sprintf( esc_attr__( 'Privacy consent: %s', 'lf-frontend-submission' ), $consent ) . "\r\n\r\n" . $ip_address,
					'post_type' => 'submission',
					'post_status' => 'pending',
					'meta_input' => array( "name_sub" => $form_data['form_name'], "email_sub" => $form_data['form_email'] )
				);
				$post_id = wp_insert_post($lffs_post_information);
			}
			// mail
			$content = $form_data['form_name'] . "\r\n\r\n" . $form_data['form_email'] . "\r\n\r\n" . $form_data['form_message'] . "\r\n\r\n" . sprintf( esc_attr__( 'Privacy consent: %s', 'lf-frontend-submission' ), $consent ) . "\r\n\r\n" . $ip_address; 
			$headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
			$headers .= "Content-Transfer-Encoding: 8bit" . "\r\n";
			$headers .= "From: ".$form_data['form_name']." <".$from.">" . "\r\n";
			$headers .= "Reply-To: <".$form_data['form_email'].">" . "\r\n";
			$auto_reply_content = $auto_reply_message . "\r\n\r\n" . $form_data['form_name'] . "\r\n\r\n" . $form_data['form_email'] . "\r\n\r\n" . $form_data['form_message'] . "\r\n\r\n" . $ip_address; 
			$auto_reply_headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
			$auto_reply_headers .= "Content-Transfer-Encoding: 8bit" . "\r\n";
			$auto_reply_headers .= "From: ".get_bloginfo('name')." <".$from.">" . "\r\n";
			$auto_reply_headers .= "Reply-To: <".$lffs_atts['email_to'].">" . "\r\n";

			if( wp_mail($to, $subject, $content, $headers) ) { 
				if ($auto_reply == true) {
					wp_mail($form_data['form_email'], $subject, $auto_reply_content, $auto_reply_headers);
				}
				$result = $thank_you_message;
				$sent = true;
			} else {
				$result = $server_error_message;
				$fail = true;
			}		
		}
	}

	// hide or display subject field 
	if ($lffs_atts['hide_subject'] == "true") {
		$hide_subject = true;
	}

	// hide or display privacy field 
	if ($privacy_setting != "yes") {
		$hide_privacy = true;
	}

	// set nonce field
	$nonce = wp_nonce_field( 'lffs_nonce_action', 'lffs_nonce', true, false ); 

	// scroll back to form location after submit
	if ($lffs_atts['scroll_to_form'] == "true") {
		$action = 'action="#lffs-anchor"';
		$anchor_begin = '<div id="lffs-anchor">';
		$anchor_end = '</div>';
	} else {
		$action = '';
		$anchor_begin = '';
		$anchor_end = '';
	}

	// contact form
	$email_form = '<form class="lffs" id="lffs" method="post" '.$action.'>
		<div class="form-group">
			<label for="lffs_name">'.esc_attr($name_label).': <span class="'.(isset($error_class['form_name']) ? "error" : "hide").'" >'.esc_attr($error_input_label).'</span></label>
			<input type="text" name="lffs_name" id="lffs_name" '.(isset($error_class['form_name']) ? ' class="form-control error"' : ' class="form-control"').' maxlength="50" value="'.esc_attr($form_data['form_name']).'" />
		</div>
		<div class="form-group">
			<label for="lffs_email">'.esc_attr($email_label).': <span class="'.(isset($error_class['form_email']) ? "error" : "hide").'" >'.esc_attr($error_email_label).'</span></label>
			<input type="email" name="lffs_email" id="lffs_email" '.(isset($error_class['form_email']) ? ' class="form-control error"' : ' class="form-control"').' maxlength="50" value="'.esc_attr($form_data['form_email']).'" />
		</div>
		<div'.(isset($hide_subject) ? ' class="hide"' : ' class="form-group"').'>
			<label for="lffs_subject">'.esc_attr($subject_label).': <span class="'.(isset($error_class['form_subject']) ? "error" : "hide").'" >'.esc_attr($error_input_label).'</span></label>
			<input type="text" name="lffs_subject" id="lffs_subject" '. (isset($error_class['form_subject']) ? ' class="form-control error"' : ' class="form-control"').' maxlength="50" value="'.esc_attr($form_data['form_subject']).'" />
		</div>
		<div class="form-group">
			<label for="lffs_captcha">'.sprintf(esc_attr($captcha_label), $_SESSION['lffs-rand']).': <span class="'.(isset($error_class['form_captcha']) ? "error" : "hide").'" >'.esc_attr($error_captcha_label).'</span></label>
			<input type="text" name="lffs_captcha" id="lffs_captcha" '.(isset($error_class['form_captcha']) ? ' class="form-control error"' : ' class="form-control"').' maxlength="50" value="'.esc_attr($form_data['form_captcha']).'" />
		</div>
		<div class="form-group hide">
			<input type="text" name="lffs_firstname" id="lffs_firstname" class="form-control" maxlength="50" value="'.esc_attr($form_data['form_firstname']).'" />
		</div>
		<div class="form-group hide">
			<input type="text" name="lffs_lastname" id="lffs_lastname" class="form-control" maxlength="50" value="'.esc_attr($form_data['form_lastname']).'" />
		</div>
		<div class="form-group">
			<label for="lffs_message">'.esc_attr($message_label).': <span class="'.(isset($error_class['form_message']) ? "error" : "hide").'" >'.esc_attr($error_textarea_label).'</span></label>
			<textarea name="lffs_message" id="lffs_message" rows="10" '.(isset($error_class['form_message']) ? ' class="form-control error"' : ' class="form-control"').'>'.wp_kses_post($form_data['form_message']).'</textarea>
		</div>
		<div'.(isset($hide_privacy) ? ' class="hide"' : ' class="form-group"').'>
			<input type="hidden" name="lffs_privacy" id="lffs_privacy_hidden" value="no">
			<label><input type="checkbox" name="lffs_privacy" id="lffs_privacy" class="custom-control-input" value="yes" '.checked( esc_attr($form_data['form_privacy']), "yes", false ).' /> <span class="'.(isset($error_class['form_privacy']) ? "error" : "").'" >'.esc_attr($privacy_label).'</span></label>
		</div>
		<div class="form-group hide">
			'. $nonce .'
		</div>
		<div class="form-group">
			<button type="submit" name="lffs_send" id="lffs_send" class="btn btn-primary">'.esc_attr($submit_label).'</button>
		</div>
	</form>';
	
	// after form validation
	if ($sent == true) {
		unset($_SESSION['lffs-rand']);
		return $anchor_begin . '<p class="lffs-info">'.esc_attr($result).'</p>' . $anchor_end;
	} elseif ($fail == true) {
		unset($_SESSION['lffs-rand']);
		return $anchor_begin . '<p class="lffs-info">'.esc_attr($result).'</p>' . $anchor_end;
	} else {
		return $anchor_begin .$email_form. $anchor_end;
	}
} 
add_shortcode('contact', 'lffs_shortcode');
