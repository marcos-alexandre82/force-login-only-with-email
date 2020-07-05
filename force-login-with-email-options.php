<?php
/**
 *
 * Page admin config by Force Login With Email
 *
 * @package FORCE_LOGIN_WITH_EMAIL_PLUGIN_FILE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

function force_login_with_email_get_default( $option ) {
	$defaults = array(
		'force-login-with-email-emailuser'      => true,
		'force-login-with-email-username'       => true,
		'force-login-with-email-username-admin' => true,
	);
	if ( isset( $defaults[ $option ] ) ) {
		return $defaults[ $option ];
	} else {
		return false;
	}
}

register_activation_hook( FORCE_LOGIN_WITH_EMAIL_PLUGIN_FILE, 'force_login_with_email_plugin_install' );
if ( is_admin() ) {
	add_action( 'admin_init', 'register_force_login_with_email_plugin_settings' );
	add_action( 'admin_menu', 'add_force_login_with_email_plugin_menu' );
}

function force_login_with_email_plugin_install() {
	add_option( 'force-login-with-email-emailuser', force_login_with_email_get_default( 'force-login-with-email-emailuser' ) );
	add_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) );
	add_option( 'force-login-with-email-username-admin', force_login_with_email_get_default( 'force-login-with-email-username-admin' ) );
}

function register_force_login_with_email_plugin_settings() {
	register_setting( 'force-login-with-email-option-group', 'force-login-with-email-emailuser' );
	register_setting( 'force-login-with-email-option-group', 'force-login-with-email-username' );
	register_setting( 'force-login-with-email-option-group', 'force-login-with-email-username-admin' );
}

function add_force_login_with_email_plugin_menu() {
	add_options_page(
		get_force_login_with_email_plugin_name(),
		get_force_login_with_email_plugin_name(),
		'manage_options',
		'force_login_with_email',
		'force_login_with_email_settings_page'
	);
}

function force_login_with_email_settings_page() {
	if ( ! get_option( 'force-login-with-email-emailuser', force_login_with_email_get_default( 'force-login-with-email-emailuser' ) ) ) {
		update_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) );
		update_option( 'force-login-with-email-username-admin', force_login_with_email_get_default( 'force-login-with-email-username-admin' ) );
	} elseif ( ! get_option( 'force-login-with-email-username', force_login_with_email_get_default( 'force-login-with-email-username' ) ) ) {
		update_option( 'force-login-with-email-username-admin', false );
	}
	print( '<div class="wrap">' );
	printf( '<h2>%s</h2>', get_force_login_with_email_plugin_name() );
	print( '<form method="post" action="options.php">' );
	settings_fields( 'force-login-with-email-option-group' );
	do_settings_sections( 'force-login-with-email-option-group' );
	print( '<table class="form-table">' );
	echo '<tr>
				<th>
					<label for="force-login-with-email-emailuser">';
						esc_html_e( 'Enable login with e-mail', 'force-login-with-email' );
					echo '</label>
				</th>
				<td>';
					printf(
						'<input type="checkbox" name="force-login-with-email-emailuser" id="force-login-with-email-emailuser"%s>',
						force_login_with_email_checked( 'force-login-with-email-emailuser' )
					);
					echo '<span class="text">';
						esc_html_e( 'Uncheck to restore default WordPress login authentication (with username).', 'force-login-with-email' );
					echo '</span>
				</td>
			</tr>';
	echo '<tr>
				<th>
					<label for="force-login-with-email-username">';
						esc_html_e( 'Enable login with username', 'force-login-with-email' );
					echo '</label>
				</th>
				<td>';
					printf(
						'<input type="checkbox" name="force-login-with-email-username" id="force-login-with-email-username"%s>',
						force_login_with_email_checked( 'force-login-with-email-username' )
					);
					echo '<span class="text">';
						esc_html_e( 'If \'Enable login with e-mail\' is off, this will be enabled automatically.', 'force-login-with-email' );
					echo '</span>
				</td>
			</tr>';
	echo '<tr>
				<th>
					<label for="force-login-with-email-username-admin">';
						esc_html_e( 'Enable login with \'admin\' username', 'force-login-with-email' );
					echo '</label>
				</th>
				<td>';
					printf(
						'<input type="checkbox" name="force-login-with-email-username-admin" id="force-login-with-email-username-admin"%s>',
						force_login_with_email_checked( 'force-login-with-email-username-admin' )
					);
					echo '<span class="text">';
						esc_html_e( 'This option is applied only if both options above are on. Uncheck to enhance security.', 'force-login-with-email' );
					echo '</span>	
				</td>
			</tr>';
	print( '</table>' );
	submit_button();
	print( '</form>' );
	print( '</div>' );
}

function force_login_with_email_checked( $option ) {
	if ( get_option( $option, force_login_with_email_get_default( $option ) ) ) {
		return ' checked';
	} else {
		return '';
	}
};
