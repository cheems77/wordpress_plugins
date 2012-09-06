<?php
/*
 Plugin Name: Simple Random Number Generator
 Plugin URI:
 Description: Creates a settings page which shows a random number.
 Version: 0.1
 Author: David Smith
 Author URI: www.cibydesign.co.uk
 */

/*  Copyright 2009  David Smith  (email : david@cibydesign.co.uk)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Activation function
 * @return
 */
function rng_install() {
	
}

/**
 * Deactivate function
 * @return
 */
function rng_uninstall() {
	
}

/**
 * Add options menu
 * @return
 */
function rng_menu() {
	add_management_page('Simple Random Number Generator', 'Random Number', 'manage_options', 'rng-options', 'rng_options');
}

/**
 * Options page output
 * @return
 */
function rng_options() {
	include 'rng-options.php';
}



register_activation_hook(__FILE__, 'rng_install');
register_deactivation_hook(__FILE__, 'rng_uninstall');
add_action('admin_menu', 'rng_menu');

?>