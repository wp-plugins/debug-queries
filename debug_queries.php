<?php
/*
Plugin Name: Debug Queries
Plugin URI: http://bueltge.de/wordpress-performance-analysieren-plugin/558/
Description: List querie-actions in html-comment only for admins
Author: Frank B&uuml;ltge
Author URI: http://bueltge.de/
Version: 0.2
License: GPL
Last Change: 30.03.2009 20:09:12
*/

if ( !defined('SAVEQUERIES') )
	define('SAVEQUERIES', true);

// core
function get_fbDebugQueries() {
	global $wpdb;
	
	$total_query_time = 0;
	
	$debugQueries = '';
	foreach ($wpdb->queries as $q) {
		$q[0] = trim(ereg_replace('[[:space:]]+', ' ', $q[0]));
		$total_query_time += $q[1];
		$debugQueries .= $q[1] . "\t" . $q[0]. "\n" . $q[2] . "\n\n";
	}
	
	$debugQueries .= __('Total query time: $total_query_time for') . ' ' . count($wpdb->queries) . ' ' . __('queries.');
	
	return $debugQueries;
}

// echo in html-comment
function fbDebugQueries() {
	echo "\n\n" . __('<!-- Debug Queries by Frank Bueltge, bueltge.de');
	echo "\n\t" . __('! Deaktivate after analysis !');
	echo "\n" . get_fbDebugQueries() . "\n" . ' -->' . "\n\n";
}

// check user can
function fbDebugQueries_user_can() {
	global $wp_version;

	if ( version_compare($wp_version, '2.1dev', '<') ) {
		require (ABSPATH . WPINC . '/pluggable-functions.php'); // < WP 2.1
	} else {
		require (ABSPATH . WPINC . '/pluggable.php'); // >= WP 2.1	
	}

	$admin_role = get_role('administrator');
	$admin_caps = $admin_role->capabilities;
	if ( array_key_exists('DebugQueries', $admin_caps) ) {
	} else {
		$admin_role->add_cap('DebugQueries', TRUE);
	}

	return current_user_can('DebugQueries');
}

// add hook
if ( fbDebugQueries_user_can() ) {
	add_action('wp_footer', 'fbDebugQueries');
}
?>