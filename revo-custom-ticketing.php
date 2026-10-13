<?php
/*
Plugin Name: Revo Ticketing System
Plugin URI: http://www.revo.ph
Description: Revo Ticketing System
Version: 1.0
Author: RFD | romel@morphlabs.com
*/
error_reporting(E_ALL);


//EXIT if accessed directly 
if (!defined('ABSPATH')) {
    exit;
}

define( 'REVOTIKCETING__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( REVOTIKCETING__PLUGIN_DIR . 'class.revo-ticketing.php' );

if ( is_admin() ) {
    //require_once( REVOTIKCETING__PLUGIN_DIR . 'class.revo-ticketing-admin.php' );
}

$revo_ticketing_obj = new revoTicketing();
$revo_ticketing_obj->run();

//add_action( 'init', array( 'RevoTicketing', 'init' ) );
