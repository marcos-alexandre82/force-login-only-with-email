<?php
/**
 * Plugin Name: Force Login With Email
 * Plugin URI:        https://github.com/marcos-alexandre82/force-login-with-email
 * GitHub Plugin URI: https://github.com/marcos-alexandre82/force-login-with-email
 * Description: Enable login in WordPress only with user e-mail address.
 * Version:     1.0
 * Requires PHP:      5.6
 * Requires at least: 4.0
 * WC requires at least: 3.0.0
 * WC tested up to: 4.3.0
 * Author:      MarcosAlexandre
 * Author URI:        https://marcosalexandre.dev/
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: force-login-with-email
 * Domain Path: /languages
 *
 * @package FORCE_LOGIN_WITH_EMAIL_PLUGIN_FILE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define( 'FORCE_LOGIN_WITH_EMAIL_PLUGIN_FILE', __FILE__ );

require_once 'force-login-with-email-options.php';

if ( get_option( 'force-login-with-email-emailuser', force_login_with_email_get_default( 'force-login-with-email-emailuser' ) ) ) {
	function force_login_with_email( $user, $username, $password ) {
		if ( is_email( $username ) ) {
			$user_by_email = get_user_by( 'email', $username );
			if ( $user_by_email instanceof WP_User ) {
				$user     = null;
				$username = $user_by_email->user_login;
			}
			return wp_authenticate_username_password( $user, $username, $password );
		} elseif ( get_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) ) ) {
			if ( 'admin' === $username && ! get_option( 'force-login-with-email-username-admin', force_login_with_email_get_default( 'force-login-with-email-username-admin' ) ) ) {
				return new WP_Error( 'invalid_username', sprintf( __( '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password?</a>' ), wp_lostpassword_url() ) );
			}
			return wp_authenticate_username_password( $user, $username, $password );
		}
	}
	add_filter( 'authenticate', 'force_login_with_email', 20, 3 );

	if ( ! get_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) ) ) {
		remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
	}

	function force_login_with_email_username_label( $translated_text, $untranslated_text, $domain ) {
		if ( 'Username' === $untranslated_text ) {
			remove_filter( current_filter(), __FUNCTION__ );
			if ( get_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) ) ) {
				$translated_text .= ' / ' . __( 'E-mail' );
			} else {
				$translated_text = __( 'E-mail' );
			}
		}
		return $translated_text;
	}

	function register_force_login_with_email_label() {
		add_filter( 'gettext', 'force_login_with_email_username_label', 99, 3 );
	}
	add_filter( 'login_init', 'register_force_login_with_email_label' );
}

function get_force_login_with_email_plugin_name() {
	return esc_html__( 'Force Login With E-mail', 'force-login-with-email' );
}
