<?php
/**
 * @package SpamifyGuard
 */
/*
Plugin Name: Spamify Guard
Plugin URI: https://www.spamifyguard.com/
Description: Spamify Guard <strong>protects your website from spam</strong>. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="https://app.spamifyguard.com/" target="_blank">Sign up for a free plan</a> to get an API key, and 3) Go to your Spamify Guard configuration page, and save your API key.
Version: 1.0
Author: DigiSquare
Author URI: https://www.digisquare.be/
License: GPLv2 or later
Text Domain: spamifyguard
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 SpamifyGuard, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SPAMIFYGUARD_VERSION', '1.0' );
define( 'SPAMIFYGUARD__MINIMUM_WP_VERSION', '3.7' );
define( 'SPAMIFYGUARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'SpamifyGuard', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'SpamifyGuard', 'plugin_deactivation' ) );

require_once( SPAMIFYGUARD_PLUGIN_DIR . 'class.spamifyguard.php' );
add_action( 'init', array( 'SpamifyGuard', 'init' ) );

if ( is_admin() ) {
	require_once( SPAMIFYGUARD_PLUGIN_DIR . 'class.spamifyguard-admin.php' );
	add_action( 'init', array( 'SpamifyGuard_Admin', 'init' ) );
}