<?php
/**
 * Plugin Name:       Breadcrumbs Navigation
 * Description:       A flexible breadcrumbs block that displays hierarchical navigation trails based on post categories with customizable settings and full accessibility support. Bootstrapped with Automattic Telex
 * Version:           0.1.0
 * Requires at least: 6.7.0
 * Requires PHP:      8.0
 * Author:            K.J. Roelke
 * Author URI:        https://www.kjroelke.online/
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       breadcrumbs-navigation
 *
 * @package BreadcrumbsNavigation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function breadcrumbs_navigation_breadcrumbs_navigation_block_init() {
	register_block_type( __DIR__ . '/build/' );
}
add_action( 'init', 'breadcrumbs_navigation_breadcrumbs_navigation_block_init' );
