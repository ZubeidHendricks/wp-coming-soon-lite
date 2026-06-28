<?php
/**
 * Uninstall cleanup.
 *
 * @package ComingSoonLite
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'coming-soon-lite_options' );
