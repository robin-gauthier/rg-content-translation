<?php

/*
Plugin Name: rg-content-translation
Plugin URI: http://www.gauthier-robin.com/
Version: 1.0
Author: Robin Gauthier
Author uri: http://www.gauthier-robin.com/
Description: Adds multilingual capability to WordPress
*/

/*
 * THIS PLUGIN REQUIRE POLYLANG OR WPML
 */

if ( ! defined( 'ABSPATH' )) {
	//exit; // don't access directly
}

global $rgct_version;
global $rgct_table_name; 
global $translationTool;

$rgct_version = '1.0';
$rgct_table_name = 'rg_translation';
define('PLUGIN_NAME', 'rg-content-translation');

/*
 * Install
 * Create database
 */
require_once('install-table.php');
register_activation_hook( __FILE__, 'rgct_install' );

include('config.php');
include('functions.php');