<?php
/*
Plugin Name: Debug Queries
Plugin URI: http://bueltge.de/wordpress-performance-analysieren-plugin/558/
Description: List querie-actions only for admins; for debug purposes
Author: Frank B&uuml;ltge
Author URI: http://bueltge.de/
Version: 0.4.1
License: GPL
Last Change: 22.04.2009 09:33:20
*/

//avoid direct calls to this file, because now WP core and framework has been used
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( function_exists('add_action') ) {
	//WordPress definitions
	if ( !defined('WP_CONTENT_URL') )
		define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	if ( !defined('WP_CONTENT_DIR') )
		define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
	if ( !defined('WP_PLUGIN_URL') )
		define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}

if ( !defined('SAVEQUERIES') )
	define('SAVEQUERIES', true);

if ( !class_exists('DebugQueries') ) {
	class DebugQueries {
		
		// constructor
		function DebugQueries() {
			
			if ( function_exists('register_activation_hook') )
				register_activation_hook(__FILE__, array(&$this, 'activate') );
			if ( function_exists('register_uninstall_hook') )
				register_uninstall_hook(__FILE__, array(&$this, 'deactivate') );
			if ( function_exists('register_deactivation_hook') )
				register_deactivation_hook(__FILE__, array(&$this, 'deactivate') );
				
			add_action( 'wp_head', array(&$this, 'wp_head') );
			add_action( 'wp_footer', array(&$this, 'the_fbDebugQueries') );
		}

		// core
		function get_fbDebugQueries() {
			global $wpdb, $wp_object_cache;
			
			$debugQueries  = '';
			if ($wpdb->queries) {
				$x = 0;
				$total_query_time = 0;
				$class = ''; 
				$debugQueries .= '<ol>' . "\n";
				
				foreach ($wpdb->queries as $q) {
					if ( $x % 2 != 0 )
						$class = '';
					else
						$class = ' class="alt"';
					$q[0] = trim( ereg_replace('[[:space:]]+', ' ', $q[0]) );
					$total_query_time += $q[1];
					$debugQueries .= '<li' . $class . '><strong>' . __('Time:') . '</strong> ' . $q[1];
					if ( isset($q[1]) )
						$debugQueries .= '<br /><strong>' . __('Query:') . '</strong> ' . $q[0];
					if ( isset($q[2]) )
						$debugQueries .= '<br /><strong>' . __('Call from:') . '</strong> ' . $q[2];
					$debugQueries .= '</li>' . "\n";
					$x++;
				}
				
				$debugQueries .= '</ol>' . "\n\n";
			}
			
			$debugQueries .= '<ul>' . "\n";
			$debugQueries .= '<li><strong>' . __('Total query time:') . ' ' . $total_query_time . ' ' . __('for') . ' ' . count($wpdb->queries) . ' ' . __('queries.') . '</strong></li>' . "\n";
			$debugQueries .= '<li><strong>' . __('Total num_query time:') . ' ' . timer_stop() . ' ' . __('for') . ' ' . get_num_queries() . ' ' . __('num_queries.') . '</strong></li>' . "\n";
			if ( count($wpdb->queries) != get_num_queries() )
				$debugQueries .= '<li class="none_list">' . __('&raquo; Different values in num_query and query? - please set the constant') . ' <code>define(\'SAVEQUERIES\', true);</code>' . __('in your') . ' <code>wp-config.php</code></li>' . "\n";
			$debugQueries .= '</ul>' . "\n";
			
			return $debugQueries;
		}

		// echo in html-comment
		function fbDebugQueries() {
			
			if ( !current_user_can('DebugQueries') )
				return;
			
			$echo 	= '';
			$echo .= "\n\n" . __('<!-- Debug Queries by Frank Bueltge, bueltge.de');
			$echo .= "\n\t" . __('! Deaktivate after analysis !');
			$echo .= "\n" . $this->get_fbDebugQueries() . "\n" . ' -->' . "\n\n";
			
			echo $echo;
		}
		
		// echo in frontend
		function the_fbDebugQueries() {
			
			if ( !current_user_can('DebugQueries') )
				return;
			
			$echo 	= '';
			$echo .= '<div id="debugqueries" class="transparent">' . "\n";
			$echo .= '<h3><a href="http://bueltge.de/wordpress-performance-analysieren-plugin/558/">Debug Queries</a> ' . __('by Frank B&uuml;ltge') . ', <a href="http://bueltge.de/">bueltge.de</a></h3>' . "\n";
			$echo .= '<p>' . __('&raquo; Deaktivate after analysis!'). '</p>' . "\n";
			$echo .= $this->get_fbDebugQueries();
			$echo .= '</div>' . "\n\n";
			
			echo $echo;
		}
		
		// add user rights
		function activate() {
			global $wp_roles;
			
			$wp_roles->add_cap('administrator', 'DebugQueries');
		}
		
		// delete user rights
		function deactivate() {
			global $wp_roles;
			
			$wp_roles->remove_cap('administrator', 'DebugQueries');
		}
		
		// function for WP < 2.6
		function plugins_url($path = '', $plugin = '') {
			$scheme = 'http';
			$url = WP_PLUGIN_URL;
			if ( 0 === strpos($url, 'http') ) {
				$url = str_replace( 'http://', "{$scheme}://", $url );
			}
		
			if ( !empty($plugin) && is_string($plugin) )
			{
				$folder = dirname(plugin_basename($plugin));
				if ('.' != $folder)
					$url .= '/' . ltrim($folder, '/');
			}
		
			if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
				$url .= '/' . ltrim($path, '/');
		
			return apply_filters('plugins_url', $url, $path, $plugin);
		}
		
		// infos in frontend, add css to head
		function wp_head() {
			global $wp_version;
			
			if ( !current_user_can('DebugQueries') )
				return;
				
			if ( version_compare( $wp_version, '2.8dev', '>' ) )
				$style = plugins_url('css/style-frontend.css', __FILE__);
			else
				$style = $this->plugins_url('css/style-frontend.css', __FILE__);
			
			$return = '<link rel="stylesheet" href="' . $style . '" type="text/css" media="screen" />';
			
			echo $return;
		}
		
	}
	
	$DebugQueries = new DebugQueries();
}
?>