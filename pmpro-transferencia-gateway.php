<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Pmpro_Transferencia_Gateway
 *
 * @wordpress-plugin
 * Plugin Name:       pmpro-transferencia-gateway
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       Para el correcto funcionamiento del plugin, se requiere modificar el archivo wp-content\plugins\paid-memberships-pro\pages\confirmation.php. Y después de la siguiente linea de código <global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt;> Agregar la acción : do_action('pmpro_gateway_transferencia');
 * 
 * Version:           1.0.0
 * Author:            Jean Carlos LO
 * Author URI:        http://dsprog.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pmpro-transferencia-gateway
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !function_exists( 'is_plugin_active' ) ):
	require_once ABSPATH.'/wp-admin/includes/plugin.php';
endif;

if( !is_plugin_active('paid-memberships-pro/paid-memberships-pro.php') ):
	return ;
endif;


define("PMPRO_TRANSFERENCIA_GATEWAY", dirname(__FILE__));

define("PMPRO_PLUGIN_NAME", "pmpro-transferencia-gateway");

/***
 * Load funciones de ayuda
 */

require_once(PMPRO_TRANSFERENCIA_GATEWAY . "/includes/functions.php");


//load payment gateway class
require_once(PMPRO_TRANSFERENCIA_GATEWAY . "/classes/PMProGateway_transferencia.php");