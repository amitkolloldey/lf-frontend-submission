<?php
/*
 * Plugin Name: Lf Front End Submission
 * Description: This is a Lf Front End Submission. Use shortcode [contact] to display form on page or use the widget. For more info please check readme file.
 * Version: 8.6
 * Author: Guido
 * Author URI: https://www.guido.site
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lf-frontend-submission
 * Domain Path: /translation
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load plugin text domain
function lffs_init() { 
	load_plugin_textdomain( 'lf-frontend-submission', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'lffs_init');

// enqueues plugin scripts
function lffs_scripts() {	
	if(!is_admin())	{
		wp_enqueue_style('lffs_style', plugins_url('/css/lffs-style.css',__FILE__));
	}
}
add_action('wp_enqueue_scripts', 'lffs_scripts');

// the sidebar widget
function register_lffs_widget() {
	register_widget( 'lffs_widget' );
}
add_action( 'widgets_init', 'register_lffs_widget' );

// form submissions
$list_submissions_setting = esc_attr(get_option('lffs-setting-2'));
if ($list_submissions_setting == "yes") {
	// create submission post type
		function lffs_custom_postype() { 
			$lffs_args = array( 
				'labels' => array('name' => __( 'Submissions', 'lf-frontend-submission' )), 
				'public' => false, 
				'can_export' => true, 
				'show_in_nav_menus' => false, 
				'show_ui' => true, 
				'show_in_rest' => true, 
				'capability_type' => 'post', 
				'capabilities' => array('create_posts' => 'do_not_allow'), 
				'map_meta_cap' => true, 
 				'supports' => array('title', 'editor'), 
			); 
			register_post_type( 'submission', $lffs_args); 
		}
		add_action( 'init', 'lffs_custom_postype' ); 

	// dashboard submission columns
	function lffs_custom_columns( $columns ) { 
		$columns['name_column'] = __( 'Name', 'lf-frontend-submission' ); 
		$columns['email_column'] = __( 'Email', 'lf-frontend-submission' ); 
		$custom_order = array('cb', 'title', 'name_column', 'email_column', 'date');
		foreach ($custom_order as $colname) {
			$new[$colname] = $columns[$colname];
		}
		return $new;
	} 
	add_filter( 'manage_submission_posts_columns', 'lffs_custom_columns', 10 );

	function lffs_custom_columns_content( $column_name, $post_id ) { 
		if ( 'name_column' == $column_name ) { 
			$name = get_post_meta( $post_id, 'name_sub', true ); 
			echo $name; 
		} 
		if ( 'email_column' == $column_name ) { 
			$email = get_post_meta( $post_id, 'email_sub', true ); 
			echo $email; 
		} 
	} 
	add_action( 'manage_submission_posts_custom_column', 'lffs_custom_columns_content', 10, 2 );

	// make name and email column sortable
	function lffs_column_register_sortable( $columns ) {
		$columns['name_column'] = 'name_sub';
		$columns['email_column'] = 'email_sub';
		return $columns;
	}
	add_filter( 'manage_edit-submission_sortable_columns', 'lffs_column_register_sortable' );

	function lffs_name_column_orderby( $vars ) {
		if(is_admin()) {
			if ( isset( $vars['orderby'] ) && 'name_sub' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => 'name_sub',
					'orderby' => 'meta_value'
				) );
			}
		}
		return $vars;
	}
	add_filter( 'request', 'lffs_name_column_orderby' );

	function lffs_email_column_orderby( $vars ) {
		if(is_admin()) {
			if ( isset( $vars['orderby'] ) && 'email_sub' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => 'email_sub',
					'orderby' => 'meta_value'
				) );
			}
		}
		return $vars;
	}
	add_filter( 'request', 'lffs_email_column_orderby' );
}

// add settings link
function lffs_action_links ( $links ) { 
	$settingslink = array( '<a href="'. admin_url( 'options-general.php?page=lffs' ) .'">'. __('Settings', 'lf-frontend-submission') .'</a>', ); 
	return array_merge( $links, $settingslink ); 
} 
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'lffs_action_links' ); 

// function to get ip of user
function lffs_get_the_ip() {
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
		return $_SERVER["HTTP_CLIENT_IP"];
	} else {
		return $_SERVER["REMOTE_ADDR"];
	}
}

// function to create from email header
function lffs_from_header() {
	if ( !isset( $from_email ) ) {
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		return 'wordpress@' . $sitename;
	}
}

// include form and widget files
include 'lffs-form.php';
include 'lffs-widget-form.php';
include 'lffs-widget.php';
include 'lffs-options.php';
